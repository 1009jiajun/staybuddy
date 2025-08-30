@extends('layouts.admin')

@section('page_title', 'Social Media Engagement')

@section('styles')
<style>
  .x-post-form {
    max-width: 700px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }
  .x-post-form label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
  }
  .x-post-form textarea,
  .x-post-form input {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ddd;
    margin-bottom: 16px;
  }
  .x-post-form button {
    background: #1da1f2;
    color: #fff;
    font-weight: 600;
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
  }
  .x-post-form button:hover { background: #0d95e8; }

  .toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #333;
    color: #fff;
    padding: 12px 20px;
    border-radius: 6px;
    display: none;
    z-index: 9999;
  }
  .toast.success { background: #28a745; }
  .toast.error { background: #dc3545; }

  .x-login { margin: 20px auto; text-align: center; }
</style>
@endsection

@section('content')
<div class="x-login" style="{{ $hasToken ? 'display:none;' : 'display:block;' }}">
  <button id="xLoginBtn" class="btn btn-primary">🔑 Login with X</button>
  <p id="xStatus"></p>
</div>

<div class="x-post-form" style="{{ $hasToken ? 'display:block;' : 'display:none;' }}">
  <h3>Promote Accommodation on X</h3>
  <form id="xPostForm">
    <label for="message">Post Message:</label>
    <textarea id="message" name="message" rows="5" placeholder="Write something about your accommodation..."></textarea>
    <button type="submit">Post to X</button>
  </form>
</div>

<form action="{{ url('/admin/x-logout') }}" method="POST" style="{{ $hasToken ? 'display:block;' : 'display:none;' }}">
    @csrf
    <button type="submit">Logout from X</button>
</form>

<div id="toast" class="toast"></div>
@endsection

@section('scripts')
<script>
const toast = document.getElementById('toast');

function showToast(message, type = 'success') {
  toast.textContent = message;
  toast.className = 'toast ' + type;
  toast.style.display = 'block';
  setTimeout(() => toast.style.display = 'none', 4000);
}

// Redirect to backend for X OAuth
document.getElementById('xLoginBtn').addEventListener('click', function() {
  window.location.href = '/x-auth/redirect';
});

// Post message to X
document.getElementById('xPostForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(this).entries());

  fetch('/post-to-x', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify(data)
  })
  .then(res => res.json())
  .then(result => {
    if (result.data?.id) {
      showToast('✅ Successfully posted to X!', 'success');
    } else {
      showToast('⚠️ Failed to post: ' + (result.error || 'Unknown error'), 'error');
    }
  })
  .catch(err => showToast('⚠️ Error: ' + err.message, 'error'));
});
</script>
@endsection