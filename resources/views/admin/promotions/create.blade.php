@extends('admin.layout2')

@section('content')
<main class="content">
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0 text-white" style="font-weight: bolder;">Mã Khuyến Mãi Mới</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-primary">{{ session('success') }}</div>
                @endif
                <form action="{{ route('promotion.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Mã Giảm Giá</label>
                        <input type="text" name="code" class="form-control"  required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phần Trăm Giảm</label>
                        <input type="number" name="discount_percent" class="form-control" min="0"  required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" placeholder="Enter category description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày Bắt Đầu</label>
                        <input type="date" name="start_date" class="form-control"   required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày Kết Thúc</label>
                        <input type="date" name="end_date" class="form-control"   required>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Thêm Mã</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
