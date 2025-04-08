@extends('admin.layout2')

@section('content')
<div class="container">
    <h2 class="mb-4">Chỉnh Sửa Sản Phẩm</h2>
    <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Tên Sản Phẩm</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
        </div>
        <h4>Biến thể</h4>
        <div id="variant-container">
            @foreach($product->productVariants as $index => $variant)
                <div class="variant-group mb-3 border p-3 rounded">
                    <label class="form-label fw-bolder">Size</label>
                    <select name="size[]" class="form-select" required>
                        @foreach(['S', 'M', 'L', 'XL'] as $size)
                            <option value="{{ $size }}" {{ $variant->size == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
        
                    <label class="form-label fw-bolder">Số Lượng</label>
                    <input type="number" name="stock_quantity[]" class="form-control" min="1" value="{{ $variant->stock_quantity }}" required>
        
                    <label class="form-label fw-bolder">Giá</label>
                    <input type="number" name="variant_price[]" class="form-control" min="1" value="{{ $variant->price }}" required>
        
                    <label class="form-label fw-bolder">Giá Khuyến Mãi (Nếu có)</label>
                    <input type="number" name="variant_discount_price[]" class="form-control" min="1" value="{{ $variant->discount_price }}">
        
                    <button type="button" class="btn btn-danger mt-2 remove-variant">Huỷ</button>
                </div>
            @endforeach
        </div>
        <button type="button" id="add-variant" class="btn btn-secondary mt-2">Thêm</button>

        <div class="row">
            <div class="col-md-6">
                <label for="price" class="form-label">Giá</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
            </div>
            <div class="col-md-6">
                <label for="discount_price" class="form-label">Giá Khuyến Mãi</label>
                <input type="number" class="form-control" id="discount_price" name="discount_price" value="{{ $product->discount_price }}">
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô Tả</label>
            <textarea class="form-control" id="description" name="description">{{ $product->description }}</textarea>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Danh Mục</label>
            <select class="form-control" id="category_id" name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="type_id" class="form-label">Loại</label>
            <select class="form-control" id="type_id" name="type_id" required>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ $product->type_id == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="img" name="img">
            @if($product->img)
                <img src="{{ asset($product->img) }}" alt="Product Image" width="100">
            @endif
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
<script>
    document.getElementById('add-variant').addEventListener('click', function () {
        const container = document.getElementById('variant-container');
        const html = `
            <div class="variant-group mb-3 border p-3 rounded">
                <label class="form-label fw-bolder">Size</label>
                <select name="size[]" class="form-select" required>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                </select>

                <label class="form-label fw-bolder">Số Lượng</label>
                <input type="number" name="stock_quantity[]" class="form-control" min="1" required>

                <label class="form-label fw-bolder">Giá</label>
                <input type="number" name="variant_price[]" class="form-control" min="1" required>

                <label class="form-label fw-bolder">Giá Khuyến Mãi (Nếu có)</label>
                <input type="number" name="variant_discount_price[]" class="form-control" min="1">

                <button type="button" class="btn btn-danger mt-2 remove-variant">Huỷ</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    });

    // Xử lý nút "Huỷ"
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-variant')) {
            e.target.closest('.variant-group').remove();
        }
    });
</script>

@endsection
