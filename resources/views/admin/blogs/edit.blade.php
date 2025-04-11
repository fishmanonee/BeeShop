@extends('admin.layout2')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Chỉnh sửa Blog</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title) }}">
        </div>

        <div class="form-group">
            <label for="content">Nội dung</label>
            <textarea name="content" rows="5" class="form-control">{{ old('content', $blog->content) }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Ảnh</label><br>
            @if($blog->image)
                <img src="{{ asset($blog->image) }}" alt="Ảnh blog" width="150" class="mb-2"><br>
            @endif
            <input type="file" name="image" class="form-control-file">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật Blog</button>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
