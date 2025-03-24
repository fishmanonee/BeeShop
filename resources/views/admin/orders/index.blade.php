@extends('admin.layout2')

@section('content')

<main class="content">
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">Quản Trị<strong> Đơn Hàng</strong></h1>
        <table id="myTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                    <th>Tổng tiền sản phẩm</th>
                    <th>Voucher</th>
                    <th>Mô tả</th>
                    <th>Tổng tiền</th>
                    <th>Hình thức thanh toán</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td><a style="font-weight: bolder" href="{{ route('orders.show', $order->id)}}">DH{{ $order->id }}</a></td>
                    <td>{{ $order->created_at }}</td>
                    <td><span class="badge bg-success">{{ $order->status->name }}</span></td>

                    <td>{{ number_format($order->subtotal, 0, ',', '.') }} đ</td>
                    <td>{{ $order->promotion->code }} </td>
                    <td>{{ $order->promotion->description }} </td>
                    <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                    <td>{{ $order->payment_method}}</td>
                    {{-- <td>
                        @if ($order->status_id == 1)
                            COD
                        @elseif ($order->status_id == 2)
                            ZaloPay
                        @else
                            {{ $order->payment_method == 'COD' ? 'COD' : 'ZaloPay' }}
                        @endif
                    </td> --}}
                    <td>
                        <a href="{{ route('orders.show', $order->id)}}" class="btn btn-primary btn-sm">Chi tiết</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>


@endsection
