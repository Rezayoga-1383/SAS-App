<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="index.html">
                    <span class="align-middle">P.T SAS <br> Sarana Agung Sejahtera</span>
                </a>
                <!-- Dashboard -->
		        <ul class="sidebar-nav">
					<li class="sidebar-header">
						Dashboard & History
					</li>
					<li class=" sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('dashboard') }}">
                            <i class="align-middle" data-feather="home"></i> <span class="align-middle">Dashboard</span>
                        </a>
					</li>
					{{-- <li class="sidebar-item">
						<a class="sidebar-link" href="index.html">
                            <i class="align-middle" data-feather="clock"></i> <span class="align-middle">History</span>
                        </a>
					</li> --}}
                    <!-- Management Data AC -->
					<li class="sidebar-header">
						Management Data AC
					</li>
					<li class="sidebar-item {{ request()->routeIs('merk-ac') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('merk-ac') }}">
							<i class="align-middle" data-feather="database"></i> <span class="align-middle">Merk AC</span>
						</a>
					</li>
					<li class="sidebar-item {{ request()->routeIs('jenis-ac') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('jenis-ac') }}">
							<i class="align-middle" data-feather="database"></i> <span class="align-middle">Jenis AC</span>
						</a>
					</li>
					<li class="sidebar-item {{ request()->routeIs('detail-ac') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('detail-ac') }}">
							<i class="align-middle" data-feather="database"></i> <span class="align-middle">Detail AC</span>
						</a>
					</li>
                    <!-- Departemen & Ruangan -->
                    <li class="sidebar-header">
						Departemen & Ruangan 
					</li>
					<li class="sidebar-item {{ request()->routeIs('departement') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('departement') }}">
              <i class="align-middle" data-feather="briefcase"></i> <span class="align-middle">Departemen</span>
            </a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('ruangan') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('ruangan') }}">
              <i class="align-middle" data-feather="server"></i> <span class="align-middle">Ruangan</span>
            </a>
					</li>
                    <!-- Management User -->
                    <li class="sidebar-header">
						Management User 
					</li>
					<li class="sidebar-item {{ request()->routeIs('pengguna') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('pengguna') }}">
              <i class="align-middle" data-feather="user"></i> <span class="align-middle">User</span>
            </a>
					</li>
				</ul>
			</div>
		</nav>