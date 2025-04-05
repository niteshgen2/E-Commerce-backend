<aside class="main-sidebar">
    <nav>
        <ul>
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.products.index') }}">Products</a></li>
            <li><a href="{{ route('admin.products.create') }}">Add Product</a></li>
            <li class="nav-item">
    <a href="{{ route('admin.products.add') }}" class="nav-link">
        <i class="nav-icon fas fa-box"></i>
        <p>Products</p>
    </a>
</li>
            <li>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </li>
        </ul>
    </nav>
</aside>
