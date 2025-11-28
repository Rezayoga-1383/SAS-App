<nav class="navbar navbar-expand navbar-light navbar-bg">
    <!-- Sidebar Toggle -->
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>

    <!-- Menu Kanan -->
    <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
            <!-- Nama User -->
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
               role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="text-dark">{{ auth()->user()->nama }}</span>
                <i class="align-middle ms-1"></i>
            </a>

            <!-- Dropdown Menu -->
            <ul class="dropdown-menu dropdown-menu-end text-end shadow-sm" aria-labelledby="userDropdown">
                <!-- Optional Profile -->
                <!-- <li><a class="dropdown-item" href="#"><i data-feather="user" class="me-1"></i> Profile</a></li> -->
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item btn-logout text-danger">
                            <i data-feather="log-out" class="me-1"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
