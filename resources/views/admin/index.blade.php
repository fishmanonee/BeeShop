@extends('admin.layout2')
@section('content')
    <div class="wrapper">


        <div class="main">



            <main class="content">
                <div class="container-fluid p-0">

                    <h1 class=" mb-3"><strong>Thống Kê</strong> Tổng Quan </h1>

                    <div class="row">
                        <div class="col-xl-6 col-xxl-5 d-flex">
                            <div class="w-100">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title text-primary">Tổng Người Dùng</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="users"></i>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Tổng số người dùng -->
                                                <h1 class="mt-1 mb-3">{{ number_format($totalUsers) }}</h1>

                                                <!-- Hiển thị phần trăm tăng trưởng -->
                                                <div class="mb-0">
                                                    @if ($growthPercentage >= 0)
                                                        <span class="text-success">
                                                            <i class="mdi mdi-arrow-top-right"></i>
                                                            +{{ number_format($growthPercentage, 2) }}%
                                                        </span>
                                                    @else
                                                        <span class="text-danger">
                                                            <i class="mdi mdi-arrow-bottom-right"></i>
                                                            {{ number_format($growthPercentage, 2) }}%
                                                        </span>
                                                    @endif
                                                    <span class="text-muted">So với tuần trước</span>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title text-primary">Lượt Truy Cập</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="corner-up-right"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3">{{ number_format($totalVisitors) }}</h1>
                                                <div class="mb-0">
                                                    <span class="text-success">
                                                        <i class="mdi mdi-arrow-top-right"></i>
                                                        {{ number_format($todayVisitors) }} hôm nay
                                                    </span>
                                                    <span class="text-muted">Tổng số lượt truy cập</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title text-primary">Tổng Sản Phẩm</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="package"></i>
                                                        </div>
                                                    </div>
                                                </div>


                                                <h1 class="mt-1 mb-3">{{ number_format($totalProducts) }} Sản Phẩm</h1>
                                                <span class="text-muted">Trong <span class="text-success fw-bolder">
                                                        {{ $totalCategory }} </span> danh mục và <span
                                                        class="text-success fw-bolder"> {{ $totalType }}</span> loại
                                                </span>


                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title text-primary">Doanh Thu</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="dollar-sign"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3">{{ number_format($totalEarningsThisWeek) }} VNĐ</h1>
                                                <div class="mb-0">
                                                    <span
                                                        class="{{ $percentageChange >= 0 ? 'text-success' : 'text-danger' }}">
                                                        <i
                                                            class="mdi {{ $percentageChange >= 0 ? 'mdi-arrow-up-right' : 'mdi-arrow-down-right' }}"></i>
                                                        {{ number_format($percentageChange, 2) }}%
                                                    </span>
                                                    <span class="text-muted">So với tuần trước</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title text-primary">Đơn Hàng</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="shopping-cart"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3">{{ $completedOrders }}</h1>
                                                <div class="mb-0">
                                                    @if ($changePercentage >= 0)
                                                        <span class="text-success">
                                                            <i class="mdi mdi-arrow-up-bold"></i>
                                                            +{{ number_format($changePercentage, 2) }}%
                                                        </span>
                                                    @else
                                                        <span class="text-danger">
                                                            <i class="mdi mdi-arrow-down-bold"></i>
                                                            {{ number_format($changePercentage, 2) }}%
                                                        </span>
                                                    @endif
                                                    <span class="text-muted">So với tuần trước</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title text-primary">Đánh Giá</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="star"></i>
                                                        </div>
                                                    </div>
                                                </div>

                                                <h1 class="mt-1 mb-3" id="total-reviews"></h1>
                                                <div class="mb-0">
                                                    <div id="reviews-details"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-xxl-7">
                            <div class="card flex-fill w-100">
                                <div class="card-header d-flex align-items-center">
                                    <h5 class="text-primary card-title mb-0">Thống Kê Doanh Thu 7 Ngày Gần Nhất</h5>
                                </div>
                                <div class="card-body py-3">
                                    <div class="chart chart-sm">
                                        <canvas id="chartjs-dashboard-line-bao"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 col-xxl-3 d-flex order-3 order-xxl-1">
                            <div class="card flex-fill w-100">
                                <div class="card-header">

                                    <h5 class="text-primary card-title mb-0">Trạng Thái Đơn Hàng</h5>
                                </div>
                                <div class="card-body d-flex">
                                    <div class="align-self-center w-100">
                                        <div class="py-3">
                                            <div class="chart chart-xs">
                                                <canvas id="chartjs-dashboard-pie-bao"></canvas>
                                            </div>
                                        </div>

                                        <table class="table mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>Đã hoàn thành</td>
                                                    <td class="text-end" id="completed-orders">0</td>
                                                </tr>
                                                <tr>
                                                    <td>Đang giao</td>
                                                    <td class="text-end" id="shipping-orders">0</td>
                                                </tr>
                                                <tr>
                                                    <td>Huỷ đơn</td>
                                                    <td class="text-end" id="canceled-orders">0</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xxl-3 d-flex order-2 order-xxl-2">
                            <div class="card flex-fill">
                                <div class="card-header">

                                    <h5 class="text-primary card-title mb-0">Số Lượng Đơn Hàng Theo Tháng</h5>
                                </div>
                                <div class="card-body d-flex w-100">
                                    <div class="align-self-center chart chart-lg">
                                        <canvas id="chartjs-dashboard-bar-bao"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-xxl-6 d-flex order-1 order-xxl-3">
                            <div class="card flex-fill w-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0 text-primary">Top Sản Phẩm</h5>
                                    <div>
                                        <button class="btn btn-sm btn-outline-primary me-2" onclick="loadTopRatedProducts()">5★</button>
                                        <button class="btn btn-sm btn-outline-danger me-2" onclick="sortBy('favorite')">Yêu Thích</button>
                                        <button class="btn btn-sm btn-outline-success" onclick="sortBy('purchases')">Mua Nhiều</button>
                                    </div>
                                </div>
                                
                                <table id="myTable" class="table table-hover my-0">
                                    <thead>
                                        <tr>
                                            <th>Tên</th>
                                            <th class="d-none d-xl-table-cell">Hình Ảnh</th>
                                            <th class="d-none d-xl-table-cell">5★</th>
                                            <th>Lượt Mua</th>
                                            <th class="d-none d-md-table-cell">Yêu Thích</th>
                                        </tr>
                                    </thead>
                                    <tbody id="top-products-body">
                                        @foreach($products as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                <td class="d-none d-xl-table-cell">
                                                    <img src="{{ $product->img }}" width="50">
                                                </td>
                                                <td>{{ $product->five_star_count }}</td> <!-- Hiển thị số đánh giá 5 sao -->
                                                <td>{{ $product->total_purchased }}</td> <!-- Hiển thị số lượt mua -->
                                                <td>{{ $product->wishlist_count }}</td> <!-- Hiển thị số lượt yêu thích -->
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    
                                    
                                    
                                </table>
                            </div>
                        </div>
                    </div>



                </div>
            </main>


        </div>
    </div>

    <script src="js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let revenueChart;

        function fetchRevenueData(type) {
            fetch(`/api/revenue?type=${type}`)
                .then(response => response.json())
                .then(data => {
                    updateChart(data);
                })
                .catch(error => console.error("Lỗi lấy dữ liệu:", error));
        }

        function updateChart(data) {
            const labels = data.map(item => item.date);
            const revenues = data.map(item => item.revenue);

            if (revenueChart) {
                revenueChart.destroy(); // Xóa biểu đồ cũ
            }

            const ctx = document.getElementById("chartjs-dashboard-line-bao").getContext("2d");
            revenueChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Doanh thu",
                        data: revenues,
                        borderColor: "#007bff",
                        backgroundColor: "rgba(0, 123, 255, 0.1)",
                        fill: true
                    }]
                }
            });
        }

        document.querySelectorAll("#time-filter button").forEach(button => {
            button.addEventListener("click", function() {
                document.querySelectorAll("#time-filter button").forEach(btn => {
                    btn.classList.remove("btn-primary");
                    btn.classList.add("btn-secondary");
                });

                this.classList.remove("btn-secondary");
                this.classList.add("btn-primary");

                fetchRevenueData(this.dataset.type);
            });
        });

        // Gọi API mặc định khi tải trang (lấy doanh thu theo ngày)
        fetchRevenueData("day");
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('/api/revenue/last7days')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.date);
                    const revenues = data.map(item => item.revenue);

                    new Chart(document.getElementById("chartjs-dashboard-line-bao"), {
                        type: "line",
                        data: {
                            labels: labels,
                            datasets: [{
                                label: "Doanh thu (VND)",
                                data: revenues,
                                borderColor: "#007bff",
                                backgroundColor: "rgba(0, 123, 255, 0.1)",
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    display: true
                                },
                                y: {
                                    display: true
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error("Lỗi khi lấy dữ liệu:", error));
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("{{ route('admin.order.stats') }}") // Route backend trả về JSON thống kê
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById("chartjs-dashboard-pie-bao").getContext("2d");
                    new Chart(ctx, {
                        type: "pie",
                        data: {
                            labels: ["Đã hoàn thành", "Đang giao", "Huỷ đơn"],
                            datasets: [{
                                data: [data.completed, data.shipping, data.canceled],
                                backgroundColor: ["#28a745", "#ffc107", "#dc3545"],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });

                    // Cập nhật số liệu trong bảng
                    document.getElementById("completed-orders").innerText = data.completed;
                    document.getElementById("shipping-orders").innerText = data.shipping;
                    document.getElementById("canceled-orders").innerText = data.canceled;
                })
                .catch(error => console.error("Error fetching order stats:", error));
        });
    </script>


    <script>
        function loadTopRatedProducts() {
    fetch('/api/top-rated-products')
        .then(response => response.json())
        .then(data => {
            let tbody = document.getElementById('top-products-body');
            tbody.innerHTML = ''; // Xóa dữ liệu cũ
            data.forEach(product => {
                tbody.innerHTML += `
                    <tr>
                        <td>${product.name}</td>
                        <td class="d-none d-xl-table-cell">
                            <img src="${product.img}" width="50">
                        </td>
                        <td>${product.five_star_count}</td>
                        <td>---</td> <!-- Để trống vì đang chỉ hiển thị 5 sao -->
                        <td class="d-none d-md-table-cell">---</td>
                    </tr>
                `;
            });
        });
}

    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("{{ route('admin.reviewsSummary') }}")
                .then(response => response.json())
                .then(data => {
                    let totalReviews = Object.values(data).reduce((sum, num) => sum + num, 0);
                    document.getElementById("total-reviews").innerText = totalReviews.toLocaleString();

                    let reviewsDetails = "";
                    for (let rating = 5; rating >= 1; rating--) {
                        reviewsDetails += `<div>
                        <span class="text-warning">${"★".repeat(rating)}</span>
                        <span class="text-muted"> ${data[rating]} đánh giá</span>
                    </div>`;
                    }

                    document.getElementById("reviews-details").innerHTML = reviewsDetails;
                })
                .catch(error => console.error("Error fetching review summary:", error));
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("{{ route('admin.orders.byMonth') }}")
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById("chartjs-dashboard-bar-bao").getContext("2d");
                    new Chart(ctx, {
                        type: "bar",
                        data: {
                            labels: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6",
                                "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"
                            ],
                            datasets: [{
                                label: "Số đơn hàng",
                                data: Object.values(data),
                                backgroundColor: "#007bff",
                                borderColor: "#0056b3",
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error("Error fetching order stats:", error));
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var markers = [{
                    coords: [31.230391, 121.473701],
                    name: "Shanghai"
                },
                {
                    coords: [28.704060, 77.102493],
                    name: "Delhi"
                },
                {
                    coords: [6.524379, 3.379206],
                    name: "Lagos"
                },
                {
                    coords: [35.689487, 139.691711],
                    name: "Tokyo"
                },
                {
                    coords: [23.129110, 113.264381],
                    name: "Guangzhou"
                },
                {
                    coords: [40.7127837, -74.0059413],
                    name: "New York"
                },
                {
                    coords: [34.052235, -118.243683],
                    name: "Los Angeles"
                },
                {
                    coords: [41.878113, -87.629799],
                    name: "Chicago"
                },
                {
                    coords: [51.507351, -0.127758],
                    name: "London"
                },
                {
                    coords: [40.416775, -3.703790],
                    name: "Madrid "
                }
            ];
            var map = new jsVectorMap({
                map: "world",
                selector: "#world_map",
                zoomButtons: true,
                markers: markers,
                markerStyle: {
                    initial: {
                        r: 9,
                        strokeWidth: 7,
                        stokeOpacity: .4,
                        fill: window.theme.primary
                    },
                    hover: {
                        fill: window.theme.primary,
                        stroke: window.theme.primary
                    }
                },
                zoomOnScroll: false
            });
            window.addEventListener("resize", () => {
                map.updateSize();
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var date = new Date(Date.now() - 5 * 24 * 60 * 60 * 1000);
            var defaultDate = date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate();
            document.getElementById("datetimepicker-dashboard").flatpickr({
                inline: true,
                prevArrow: "<span title=\"Previous month\">&laquo;</span>",
                nextArrow: "<span title=\"Next month\">&raquo;</span>",
                defaultDate: defaultDate
            });
        });
    </script>
    <script>
        document.querySelectorAll("#time-filter button").forEach(button => {
            button.addEventListener("click", function() {
                document.querySelectorAll("#time-filter button").forEach(btn => {
                    btn.classList.remove("btn-primary");
                    btn.classList.add("btn-secondary");
                });
                this.classList.remove("btn-secondary");
                this.classList.add("btn-primary");

                // Xử lý cập nhật biểu đồ tại đây (nếu cần)
                console.log("Đã chọn:", this.dataset.type);
            });
        });
    </script>
@endsection
