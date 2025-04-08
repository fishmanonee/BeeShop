@extends('admin.layout2')
@section('content')

<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Quản Lý <strong>Danh Mục</strong></h1>
        <a href="{{ route('admin.category.create') }}" class="btn btn-primary">Thêm Danh Mục</a>

        
            <table id="myTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Mô Tả</th>
                        <th>Ngày Tạo</th>
                        <th>Ngày Cập Nhật</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                        <td class="action-icons">
                            <a href="{{ route('admin.category.edit', $item->id) }}" >Chỉnh sửa</a>                             
                            <form action="{{ route('admin.category.destroy', $item->id) }}" method="POST" 
                                style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="border: none; background: none; color: red; cursor: pointer;"> 
                                    <span class="text-black">|</span> Xoá
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
    </div>
</main>

@endsection
