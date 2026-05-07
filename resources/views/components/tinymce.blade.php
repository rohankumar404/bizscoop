@props(['id' => 'editor', 'name' => 'content', 'value' => ''])

<div class="tinymce-editor">
    <textarea id="{{ $id }}" name="{{ $name }}">{{ $value }}</textarea>
</div>

@once
    @push('scripts')
        <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
        <script>
            tinymce.init({
                selector: 'textarea#{{ $id }}',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                height: 400,
                menubar: false,
                skin: 'oxide-dark',
                content_css: 'dark'
            });
        </script>
    @endpush
@endonce
