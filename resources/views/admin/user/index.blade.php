@extends('admin.layout2')
@section('content')


<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Quản Lý <strong>Khách Hàng</strong></h1>
            <table id="myTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số Điện Thoại</th>
                        <th>Địa Chỉ</th>
                        {{-- <th>Role</th> --}}
                        <th>Ngày Tạo</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ $item->address }}</td>
                        {{-- <td>{{ $item->roles->name }}</td> --}}
                        <td>{{ $item->created_at }}</td>
                        <td class="action-icons">
                            <a href="">Điều Chỉnh</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
</main>

@endsection
