@extends('admin.layout2')
@section('content')
    <main class="content">
        <div  vclass="container-fluid p-0">
            <h1 class="h3 mb-3"><strong>Chi Tiết Sản Phẩm</strong></h1>
            <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">Quay lại</a>
            {{-- <a href="" class="btn btn-primary mb-3" target="_blank">Đánh giá</a> --}}
            {{-- {{ route('products.reviews', $product->id) }} --}}
            <button id="btn-show-reviews" class="btn btn-primary mb-3">Đánh giá</button>

            <div  class="card">
                <div class="card-header">
                    <img src="{{ asset($product->img) }}" width="200" alt="Product Image">
                    @if ($product)
                        <h4 class="mt-2">Sản Phẩm : {{ $product->name }}</h4>
                        {{-- <p><strong>Category:</strong> {{ optional($product->category)->name }}</p> --}}
                    @else
                        <p>Product not found.</p>
                    @endif


                </div>
                <div class="card-body">
                    <p><strong>Danh mục:</strong> {{ $product->category->name }}</p>
                    <p><strong>Loại:</strong> {{ $product->type->name }}</p>
                    {{-- <p><strong>Base Price:</strong> {{ number_format($product->price, 0, ',', '.') }} VNĐ</p> --}}
                    {{-- <p><strong>Discount Price:</strong> {{ number_format($product->discount_price, 0, ',', '.') }} VNĐ</p> --}}
                    <p><strong>Mô tả:</strong> {{ $product->description }}</p>
                    <p><strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
                    {{-- <p><strong>Updated At:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}</p> --}}

                    <h5><strong>Sizes & Tồn kho</strong></h5>
                    <table id="product-details" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Size</th>
                                <th>Tồn kho</th>
                                <th>Giá</th>
                                <th>Giá khuyến mãi</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product->productVariants as $variant)
                                <tr>
                                    <td>{{ $variant->size }}</td>
                                    <td>{{ $variant->stock_quantity }}</td>
                                    <td>{{ number_format($variant->price, 0, ',', '.') }} VNĐ</td>
                                    <td>{{ number_format($variant->discount_price, 0, ',', '.') }} VNĐ</td>
                                    <td>
                                        @if ($variant->stock_quantity > 0)
                                            <span class="badge bg-success">In Stock</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>



                </div>
            </div>
        </div>
        <!-- Khu vực hiển thị đánh giá -->
<div id="reviews-section" class="d-none">
    <h4>Đánh giá sản phẩm</h4>
    <div id="reviews-content"></div>
</div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $("#btn-show-reviews").click(function () {
            let productId = {{ $product->id }};
            let reviewSection = $("#reviews-section");
            let productDetails = $("#product-details");

            if (reviewSection.hasClass("d-none")) {
                // Nếu đang ẩn -> Hiện đánh giá, ẩn chi tiết sản phẩm
                $.ajax({
                    url: "/products/" + productId + "/reviews",
                    type: "GET",
                    success: function (data) {
                        productDetails.hide();
                        reviewSection.removeClass("d-none");
                        $("#reviews-content").html(data);
                        $("#btn-show-reviews").text("Thông tin"); // Đổi text của nút
                    },
                    error: function () {
                        alert("Không thể tải đánh giá, vui lòng thử lại!");
                    }
                });
            } else {
                // Nếu đang hiện -> Ẩn đánh giá, hiện lại chi tiết sản phẩm
                reviewSection.addClass("d-none");
                productDetails.show();
                $("#btn-show-reviews").text("Đánh giá"); // Đổi text của nút
            }
        });
    });
</script>


@endsection
