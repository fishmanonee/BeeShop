<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="index.html">
            <span class="align-middle">Trang Quản Trị</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Mục Lục
            </li>

            <li class="sidebar-item {{ Request::routeIs('products.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('products.index') }}">
                    <i class="align-middle" data-feather="tag"></i>
                    <span class="align-middle">Sản Phẩm</span>
                </a>
            </li>
            
            <li class="sidebar-item {{ Request::routeIs('categories.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('categories.index') }}">
                    <i class="align-middle" data-feather="list"></i>
                    <span class="align-middle">Danh Mục</span>
                </a>
            </li>
            
            <li class="sidebar-item {{ Request::routeIs('types.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('types.index') }}">
                    <i class="align-middle" data-feather="grid"></i>
                    <span class="align-middle">Loại Sản Phẩm</span>
                </a>
            </li>
            
            <li class="sidebar-item {{ Request::routeIs('admin.users') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.users') }}">
                    <i class="align-middle" data-feather="user"></i>
                    <span class="align-middle">Người Dùng</span>
                </a>
            </li>
            


            <li class="sidebar-item {{ Request::routeIs('orders.*') ? 'active' : '' }}">
                <a class="sidebar-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#ordersMenu"
                    aria-expanded="false">
                    <i class="align-middle" data-feather="shopping-cart"></i>
                    <span class="align-middle">Đơn Hàng</span>
                </a>
                <ul id="ordersMenu" class="collapse list-unstyled">
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('orders.index2') }}">
                            <i class="align-middle" data-feather="clock"></i>
                            <span class="ms-3 align-middle">Chờ Xác Nhận</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('orders.index3') }}">
                            <i class="align-middle" data-feather="check-circle"></i>
                            <span class="ms-3 align-middle">Đã Giao Hàng</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('orders.index') }}">
                            <i class="align-middle" data-feather="clock"></i>
                            <span class="ms-3 align-middle">Danh Sách</span>
                        </a>
                    </li>
                    
                    
                </ul>
            </li>
            

            <li class="sidebar-item {{ Request::routeIs('promotions.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('promotions.index') }}">
                    <i class="align-middle" data-feather="percent"></i>
                    <span class="align-middle">Mã Khuyến Mãi</span>
                </a>
            </li>

            <li class="sidebar-item {{ Request::routeIs('comments.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{route('comments.index')}}">
                    <i class="align-middle" data-feather="message-square"></i>
                    <span class="align-middle">Bình Luận</span>
                </a>
            </li>


        </ul>


    </div>
</nav>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    let menuItem = document.querySelector('.menu-item');
    
    if (localStorage.getItem('menu-open') === 'true') {
        menuItem.classList.add('menu-open');
    }

    menuItem.addEventListener('click', function () {
        let isOpen = menuItem.classList.toggle('menu-open');
        localStorage.setItem('menu-open', isOpen);
    });
});

</script>