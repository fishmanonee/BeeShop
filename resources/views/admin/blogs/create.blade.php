@extends('admin.layout2')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tạo Blog mới</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Tiêu đề -->
                <div class="form-group mb-3">
                    <label for="title" class="font-weight-bold">Tiêu đề</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title') }}" placeholder="Nhập tiêu đề blog">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nội dung -->
                <div class="form-group mb-3">
                    <label for="content" class="font-weight-bold">Nội dung</label>
                    <textarea name="content" rows="5" class="form-control @error('content') is-invalid @enderror"
                              placeholder="Nhập nội dung blog">{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Ảnh -->
                <div class="form-group mb-4">
                    <label for="image" class="font-weight-bold">Ảnh</label>
                    <input type="file" name="image" class="form-control-file @error('image') is-invalid @enderror">
                    @error('image')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nút hành động -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">Quay lại</a>
                    <button type="submit" class="btn btn-primary">Tạo Blog</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
