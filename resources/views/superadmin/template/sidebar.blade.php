<nav id="sidebar" class="sidebar js-sidebar">
	<div class="sidebar-content js-simplebar">
		<a class="sidebar-brand" href="{{ route('dashboard.superadmin') }}">
			<span class="align-middle">PT SAS <br> Sarana Agung Sejahtera</span>
		</a>

		<!-- Dashboard -->
		<ul class="sidebar-nav">
			<li class="sidebar-item {{ request()->routeIs('dashboard.superadmin') || request()->routeIs('history.superadmin') ? 'active' : '' }}">
				<a data-bs-target="#dashboardMenu" data-bs-toggle="collapse" class="sidebar-link">
					<i data-feather="home"></i>
					<span class="align-middle fw-bolder">Home</span>
				</a>
				<ul id="dashboardMenu" class="sidebar-dropdown list-unstyled collapse">
					<li class="sidebar-item {{ request()->routeIs('dashboard.superadmin') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('dashboard.superadmin') }}">Dashboard</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('history.superadmin') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('history.superadmin') }}">History</a>
					</li>
				</ul>
			</li>

			{{-- SPK & Report --}}
			<li class="sidebar-item {{ request()->routeIs('superadmin.spk') || request()->routeIs('admin.report') || request()->routeIs('admin.reportperbaikan') || request()->routeIs('admin.reportteknisi') ? 'active' : '' }}">
				<a data-bs-target="#spkmenu" data-bs-toggle="collapse" class="sidebar-link">
					<i data-feather="file-text"></i>
					<span class="align-middle fw-bolder">SPK & Report</span>
				</a>

				<ul id="spkmenu" class="sidebar-dropdown list-unstyled collapse">
					<li class="sidebar-item {{ request()->routeIs('superadmin.spk') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('superadmin.spk') }}">SPK</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('admin.report') || request()->routeIs('admin.reportperbaikan') || request()->routeIs('admin.reportteknisi') ? 'active' : '' }}">
						<a data-bs-target="#report" data-bs-toggle="collapse" class="sidebar-link fw-bolder">
							Report
						</a>

						<ul id="report" class="sidebar-dropdown list-unstyled collapse ps-3">
							<li class="sidebar-item {{ request()->routeIs('admin.report') ? 'active' : '' }}">
								<a class="sidebar-link" href="{{ route('admin.report') }}">Dokumentasi</a>
							</li>

							<li class="sidebar-item {{ request()->routeIs('admin.reportperbaikan') ? 'active' : '' }}">
								<a class="sidebar-link" href="{{ route('admin.reportperbaikan') }}">Perbaikan Ulang</a>
							</li>

							<li class="sidebar-item {{ request()->routeIs('admin.reportteknisi') ? 'active' : '' }}">
								<a class="sidebar-link" href="{{ route('admin.reportteknisi') }}">Teknisi</a>
							</li>
						</ul>
					</li>

					{{-- <li class="sidebar-item  {{ request()->routeIs('admin.report') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('admin.report') }}">Report</a>
					</li> --}}
				</ul>
			</li>
			
			<!-- Master Data AC -->
			<li class="sidebar-item {{ request()->routeIs('merk-ac') || request()->routeIs('jenis-ac') || request()->routeIs('detail-ac') ? 'active' : '' }}">
				<a data-bs-target="#managedataac" data-bs-toggle="collapse" class="sidebar-link">
					<i data-feather="database"></i>
					<span class="align-middle fw-bolder">Master Data</span>
				</a>

				<ul id="managedataac" class="sidebar-dropdown list-unstyled collapse">
					<li class="sidebar-item {{ request()->routeIs('merk-ac') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('merk-ac') }}">Merk AC</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('jenis-ac') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('jenis-ac') }}">Jenis AC</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('detail-ac') ? 'active' : ''}}">
						<a class="sidebar-link" href="{{ route('detail-ac') }}">Detail AC</a>
					</li>
				</ul>
			</li>
			
			<!-- Departemen & Ruangan -->
			<li class="sidebar-item {{ request()->routeIs('departement') || request()->routeIs('ruangan') ? 'active' : '' }}">
				<a data-bs-target="#ruangan" data-bs-toggle="collapse" class="sidebar-link">
					<i data-feather="map"></i>
					<span class="align-middle fw-bolder">Lokasi</span>
				</a>

				<ul id="ruangan" class="sidebar-dropdown list-unstyled collapse">
					<li class="sidebar-item {{ request()->routeIs('departement') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('departement') }}">Departement</a>
					</li>

					<li class="sidebar-item {{ request()->routeIs('ruangan') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('ruangan') }}">Ruangan</a>
					</li>
				</ul>
			</li>

			<!-- Management User -->
			<li class="sidebar-item">
				<a data-bs-target="#user" data-bs-toggle="collapse" class="sidebar-link">
					<i data-feather="user"></i>
					<span class="align-middle fw-bolder">Management User</span>
				</a>

				<ul id="user" class="sidebar-dropdown list-unstyled collapse">
					<li class="sidebar-item {{ request()->routeIs('pengguna') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('pengguna') }}">Data User</a>
					</li>

					{{-- <li class="sidebar-item">
						<a class="sidebar-link" href="#">Absensi Karyawan</a>
					</li> --}}
				</ul>
			</li>
		</ul>
	</div>
</nav>