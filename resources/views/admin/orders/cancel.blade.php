@extends('admin.layout2')

@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <h1 class="h3 mb-3">Quản Trị<strong> Đơn Hàng</strong></h1>
            <table id="myTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ngày cập nhật</th>
                        <th>Trạng thái</th>
                        
                        
                        <th>Tổng tiền</th>
                        <th>Hình thức thanh toán</th>
                        <th>Lý do</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>DH{{ $order->id }}</td>
                            <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                            <td><span class="badge bg-danger">{{ $order->status->name }}</span></td>
                            {{-- <td>
                                @if ($order->status_id == 1)
                                    <span class="badge bg-warning">Chờ xác nhận</span>
                                @elseif ($order->status_id == 2)
                                    <span class="badge bg-warning">Chờ thanh toán</span>
                                @elseif ($order->status_id == 3)
                                    <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </td> --}}
                            
                            
                            <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                            <td>{{ $order->payment_method }} </td>
                            <td>{{ $order->cancel_reason }} </td>
                            {{-- <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm">Chi tiết</a>
                                @if ($order->status_id == 3 || 4)
                                    <!-- Chỉ hiển thị khi đơn hàng đang Chờ xác nhận -->
                                    <form action="{{ route('orders.confirm', $order->id) }}" method="POST"
                                        style="display: inline-block;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('Bạn có chắc chắn muốn xác nhận đơn hàng này?')">
                                            Tiến Hành Giao Hàng
                                        </button>
                                    </form>
                                @endif
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection
