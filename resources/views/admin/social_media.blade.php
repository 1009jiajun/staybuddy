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
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
  selector: '#message',
  menubar: false,
  height: 320,
  plugins: 'link lists image media table codesample code',
  toolbar: 'undo redo | formatselect | bold italic underline | bullist numlist | link image media table | removeformat | code',
  images_upload_url: '{{ route("admin.upload-image") }}', // our Laravel endpoint
  automatic_uploads: true,
  images_file_types: 'jpeg,jpg,png,gif,webp',
  file_picker_types: 'image',
  images_upload_credentials: true, // send cookies/CSRF
  setup: (editor) => {
    // ensure CSRF header for uploads
    editor.on('BeforeUpload', (e) => {
      e.blob(); // no-op; keeps TinyMCE happy
    });
  },
  // Attach CSRF header to TinyMCE’s internal XHR
  images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();
    xhr.withCredentials = true;
    xhr.open('POST', '{{ route("admin.upload-image") }}');
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

    xhr.upload.onprogress = (e) => {
      progress(e.loaded / e.total * 100);
    };

    xhr.onload = () => {
      if (xhr.status < 200 || xhr.status >= 300) {
        return reject('HTTP Error: ' + xhr.status);
      }
      let json = {};
      try { json = JSON.parse(xhr.responseText); } catch (err) {}
      if (!json || typeof json.location !== 'string') {
        return reject('Invalid JSON: ' + xhr.responseText);
      }
      resolve(json.location); // TinyMCE will insert the image using this URL
    };

    xhr.onerror = () => reject('Image upload failed due to a XHR Transport error.');

    const formData = new FormData();
    formData.append('file', blobInfo.blob(), blobInfo.filename());
    xhr.send(formData);
  })
});

// Keep your existing toast/handlers…
const toast = document.getElementById('toast');
function showToast(message, type = 'success') {
  toast.textContent = message;
  toast.className = 'toast ' + type;
  toast.style.display = 'block';
  setTimeout(() => toast.style.display = 'none', 4000);
}

// Login button
document.getElementById('xLoginBtn')?.addEventListener('click', function() {
  window.location.href = '/x-auth/redirect';
});

// Post to X (we’ll send HTML; if you need plain text, strip it below)
document.getElementById('xPostForm')?.addEventListener('submit', function(e) {
  e.preventDefault();
  const html = tinymce.get('message').getContent();          // HTML with images/styles
  const plain = tinymce.get('message').getContent({ format: 'text' }); // fallback for X
  const payload = { message: html, message_plain: plain };

  fetch('/post-to-x', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify(payload)
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

