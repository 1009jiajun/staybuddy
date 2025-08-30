<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    public function tinymce(Request $request)
    {
        $request->validate([
            'file' => ['required','image','mimes:jpeg,jpg,png,gif,webp','max:4096'], // 4MB
        ]);

        $path = $request->file('file')->store('public/x-images');
        $url  = Storage::url($path); // e.g. /storage/x-images/abc.png

        return response()->json(['location' => $url]);
    }
}
