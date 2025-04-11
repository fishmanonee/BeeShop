@extends('admin.layout2')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Danh sách Blog</h1>

    <!-- Nút tạo blog -->
    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary mb-3">Tạo Blog Mới</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tiêu đề</th>
                        <th>Nội dung</th>
                        <th>Người tạo</th>
                        <th>Ảnh</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blogs as $blog)
                    <tr>
                        <td>{{ $blog->title }}</td>
                        <td>{{ Str::limit($blog->content, 50) }}</td> <!-- Giới hạn nội dung để hiển thị ngắn gọn -->
                        <td>{{ $blog->user->name }}</td> <!-- Giả sử bạn đã định nghĩa quan hệ 'user' trong model Blog -->
                        <td>
                            @if ($blog->image)
                                <img src="{{ asset($blog->image) }}" alt="Ảnh blog" width="100">
                            @else
                                <span>Không có ảnh</span>
                            @endif
                        </td>
                        <td>
                            <!-- Liên kết đến trang chi tiết blog -->
                            <a href="{{ route('admin.blogs.show', $blog->id) }}" class="btn btn-info btn-sm">Xem</a> | 
                            <!-- Các hành động khác như sửa, xóa -->
                            <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-warning btn-sm">Sửa</a> | 
                            <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa blog này không?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Phân trang nếu cần -->
            {{ $blogs->links() }}
        </div>
    </div>
</div>
@endsection
