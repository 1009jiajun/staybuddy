@extends('layouts.admin')

@section('page_title', 'Social Media Engagement')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
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
  .x-post-form input,
  .x-post-form textarea {
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

  .quill-editor {
    height: 200px;
    margin-bottom: 16px;
  }

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
  <form id="xPostForm" enctype="multipart/form-data">
    <label for="message">Post Message:</label>
    <div id="editor" class="quill-editor"></div>

    <label for="image">Upload Images (max 4):</label>
    <input type="file" id="imageInput" name="images[]" accept="image/*" multiple>

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
<script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
<script>
const toast = document.getElementById('toast');

function showToast(message, type = 'success') {
  toast.textContent = message;
  toast.className = 'toast ' + type;
  toast.style.display = 'block';
  setTimeout(() => toast.style.display = 'none', 5000);
}

const quill = new Quill('#editor', {
  theme: 'snow',
  placeholder: "Write something about your accommodation..."
});

// Redirect to backend for X OAuth
document.getElementById('xLoginBtn')?.addEventListener('click', function() {
  window.location.href = '/admin/x-auth/redirect';
});

// Post message to X
document.getElementById('xPostForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();

  const plainText = quill.getText().trim();
  if (!plainText) {
    showToast("⚠️ Message cannot be empty", "error");
    return;
  }

  let formData = new FormData();
  formData.append("message", plainText);

  const imageFiles = document.getElementById('imageInput').files;
  if (imageFiles.length > 4) {
    showToast("⚠️ You can only upload up to 4 images.", "error");
    return;
  }

  for (let i = 0; i < imageFiles.length; i++) {
    formData.append("images[]", imageFiles[i]);
  }

  try {
    let res = await fetch('/admin/post-to-x', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: formData
    });

    let result = await res.json();
    if (result.data?.id) {
      let extraMsg = result.extra ? " (⚠️ " + result.extra + ")" : "";
      showToast("✅ Successfully posted to X!" + extraMsg, "success");
      quill.setText("");
      document.getElementById("imageInput").value = "";
    } else {
      showToast("⚠️ Failed to post: " + (result.error || 'Unknown error'), "error");
    }
  } catch (err) {
    showToast("⚠️ Error: " + err.message, "error");
  }
});
</script>
@endsection
