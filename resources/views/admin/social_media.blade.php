@extends('layouts.admin')

@section('page_title', 'Social Media Engagement')

@section('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
.x-post-form {
    max-width: 700px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
#editor {
    height: 200px;
    background: #fff;
}
.x-post-form label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
}
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
        <label for="editor">Post Message:</label>
        <div id="editor"></div>
        <button type="submit">Post to X</button>
    </form>
</div>

</br>

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
    setTimeout(() => toast.style.display = 'none', 4000);
}

// Initialize Quill editor with custom image handler
const quill = new Quill('#editor', {
    theme: 'snow',
    placeholder: 'Write something about your accommodation...',
    modules: {
        toolbar: {
            container: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                ['link', 'image', 'video'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['clean']
            ],
            handlers: {
                image: imageHandler
            }
        }
    }
});

function imageHandler() {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();

    input.onchange = async () => {
        const file = input.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);

        try {
            const res = await fetch('{{ route("admin.upload-image") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });
            const data = await res.json();
            if (data.location) {
                const range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', data.location);
            } else {
                showToast('⚠️ Failed to upload image', 'error');
            }
        } catch (err) {
            showToast('⚠️ Error uploading image: ' + err.message, 'error');
        }
    };
}

// Handle form submission
document.getElementById('xPostForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const htmlContent = quill.root.innerHTML;  // HTML content including images
    const plainText = quill.getText();

    fetch('/post-to-x', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message: htmlContent, message_plain: plainText })
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

// X Login button
document.getElementById('xLoginBtn')?.addEventListener('click', function() {
    window.location.href = '/x-auth/redirect';
});
</script>
@endsection
