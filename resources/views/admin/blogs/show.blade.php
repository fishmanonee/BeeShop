@extends('admin.layout2')

@section('content')
<div class="container mt-4">
    <h1>Chi tiết Blog</h1>
    <div class="card mt-3 p-4">
        <div class="mb-3">
            <strong>Tiêu đề:</strong>
            <p>{{ $blog->title }}</p>
        </div>

        <div class="mb-3">
            <strong>Nội dung:</strong>
            <p>{{ $blog->content }}</p>
        </div>

        <div class="mb-3">
            <strong>Người tạo:</strong>
            <p>{{ $blog->user->name ?? 'Không rõ' }}</p>
        </div>

        <div class="mb-3">
            <strong>Ảnh:</strong><br>
            @if ($blog->image)
                <img src="{{ asset($blog->image) }}" alt="Ảnh blog" style="max-width: 400px; border-radius: 8px;">
            @else
                <p>Không có ảnh</p>
            @endif
        </div>

        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách</a>
    </div>
</div>
@endsection
