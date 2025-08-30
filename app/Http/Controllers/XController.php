<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // For authenticated user info

class XController extends Controller
{
    public function redirectToX()
    {
        $clientId = env('X_CLIENT_ID');
        $redirectUri = urlencode(env('X_REDIRECT_URI')); // encode
        $state = bin2hex(random_bytes(16)); // CSRF protection
        $scope = urlencode('tweet.read users.read tweet.write offline.access'); // encode

        // Generate a random code_verifier
        $codeVerifier = bin2hex(random_bytes(64)); 
        $codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
        session(['x_code_verifier' => $codeVerifier]);

        $authUrl = "https://twitter.com/i/oauth2/authorize?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}&scope={$scope}&state={$state}&code_challenge={$codeChallenge}&code_challenge_method=S256";

        return redirect($authUrl);
    }

    // Handle callback from X OAuth
    public function handleXCallback(Request $request)
    {
        $code = $request->query('code');
        $codeVerifier = session('x_code_verifier'); // retrieve from session

        if (!$codeVerifier) {
            return redirect('/admin/social-media')->with('error', 'Missing code_verifier. Try logging in again.');
        }

        $credentials = base64_encode(env('X_CLIENT_ID') . ':' . env('X_CLIENT_SECRET'));

        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . $credentials,
        ])->asForm()->post('https://api.twitter.com/2/oauth2/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => env('X_REDIRECT_URI'),
            'code_verifier' => $codeVerifier,
        ]);

        $tokens = $response->json();

        if (isset($tokens['error'])) {
            return redirect('/admin/social-media')->with('error', $tokens['error_description'] ?? $tokens['error']);
        }

        // Save tokens
        Storage::disk('local')->put('x_token.json', json_encode([
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'] ?? null,
        ]));

        return redirect('/admin/social-media')->with('x_logged_in', true);
    }

   public function postToX(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:280',
            'media_ids' => 'array',
            'media_ids.*' => 'string'
        ]);

        if (!Storage::disk('local')->exists('x_token.json')) {
            return response()->json(['error' => 'No X access token found. Login first.'], 400);
        }

        $config = json_decode(Storage::disk('local')->get('x_token.json'), true);
        $accessToken = $config['access_token'] ?? null;

        if (!$accessToken) {
            return response()->json(['error' => 'No X access token found. Login first.'], 400);
        }

        $payload = ['text' => $request->message];

        if (!empty($request->media_ids)) {
            $payload['media'] = ['media_ids' => $request->media_ids];
        }

        $response = Http::withToken($accessToken)
            ->post('https://api.twitter.com/2/tweets', $payload);

        if ($response->failed()) {
            return response()->json(['error' => $response->json()], 400);
        }

        return response()->json($response->json());
    }

    public function uploadImageToX(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:5120',
        ]);

        if (!Storage::disk('local')->exists('x_token.json')) {
            return response()->json(['error' => 'No X access token found. Login first.'], 400);
        }

        $config = json_decode(Storage::disk('local')->get('x_token.json'), true);
        $accessToken = $config['access_token'] ?? null;

        $file = $request->file('file');
        $content = file_get_contents($file->getRealPath());

        $response = Http::withToken($accessToken)
            ->attach('media', $content, $file->getClientOriginalName())
            ->post('https://upload.twitter.com/1.1/media/upload.json');

        $result = $response->json();

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);
        }

        return response()->json([
            'media_id' => $result['media_id_string'],
            'url' => url('storage/' . $file->store('uploads')), // optional preview
        ]);
    }


    public function logoutFromX()
    {
        if (Storage::disk('local')->exists('x_token.json')) {
            Storage::disk('local')->delete('x_token.json');
        }

        return redirect('/admin/social-media')->with('success', 'Logged out from X successfully!');
    }
}
