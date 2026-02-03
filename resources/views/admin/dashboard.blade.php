@extends('admin.template.main')

@section('title', 'Dashboard Admin - SAS')

@section('content')

<main class="content">
    <div class="container-fluid p-0">
		@php
			$hour = now()->format('H'); // Ambil jam saat ini (00-23)
			if ($hour >= 4 && $hour < 10) {
				$greeting = 'Selamat Pagi';
			} elseif ($hour >= 10 && $hour < 15) {
				$greeting = 'Selamat Siang';
			} elseif ($hour >= 15 && $hour < 18) {
				$greeting = 'Selamat Sore';
			} else {
				$greeting = 'Selamat Malam';
			}
		@endphp

		<h1 class="h1 mb-1"><strong>{{ $greeting }}, {{ auth()->user()->nama }} </strong></h1>
		<h1 class="h3 mb-3"><strong>Dashboard</strong> Admin</h1>

		    <!-- Row statistik lainnya -->
			<div class="row mt-3 g-3">
				<div class="col-12 col-sm-6 col-lg-3">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">JENIS AC</h5>
							<h1 class="mt-1 mb-3">{{ $jumlahjenis }}</h1>
							<div class="mb-0">
								<span class="text-danger">Count Jenis</span>
								<span class="text-muted d-block">RSPAL Ramelan</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-6 col-lg-3">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">MERK AC</h5>
							<h1 class="mt-1 mb-3">{{ $jumlahmerk }}</h1>
							<div class="mb-0">
								<span class="text-success">Count Merk</span>
								<span class="text-muted d-block">RSPAL Ramelan</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-6 col-lg-3">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">DEPARTEMENT</h5>
							<h1 class="mt-1 mb-3">{{ $jumlahdepartement }}</h1>
							<div class="mb-0">
								<span class="text-success">Count Departement</span>
								<span class="text-muted d-block">RSPAL Ramelan</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-6 col-lg-3">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">RUANGAN</h5>
							<h1 class="mt-1 mb-3">{{ $jumlahruangan }}</h1>
							<div class="mb-0">
								<span class="text-danger">Count Ruangan</span>
								<span class="text-muted d-block">RSPAL Ramelan</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		<div class="row g-3">
				<!-- Kolom Kiri -->
			<div class="col-12 col-lg-6">
					<div class="card">
						<div class="card-body">
							<div class="card flex-fill w-100">
								<div class="card-header">
									<h5 class="card-title mb-0">JENIS AC</h5>
								</div>
								<div class="card-body d-flex">
									<div class="align-self-center w-100">
										<div class="py-3">
											<div class="chart chart-xs">
												<canvas id="chartjs-dashboard-pie-left"></canvas>
											</div>
										</div>

										<table class="table mb-0">
											<tbody>
												@foreach ($datajenis as $jenis)
												<tr>
													<td>{{ $jenis->nama_jenis }}</td>
													<td class="text-end">{{ $jenis->total }}</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Kolom Kanan -->
			<div class="col-12 col-lg-6">
					<div class="card">
						<div class="card-body">
							<div class="card flex-fill w-100">
								<div class="card-header">
									<h5 class="card-title mb-0">MERK AC</h5>
								</div>
								<div class="card-body d-flex">
									<div class="align-self-center w-100">
										<div class="py-3">
											<div class="chart chart-xs">
												<canvas id="chartjs-dashboard-pie-right"></canvas>
											</div>
										</div>

										<table class="table mb-0">
											<tbody>
												@foreach ($datamerk as $merk)
												<tr>
													<td>{{ $merk->nama_merk }}</td>
													<td class="text-end">{{ $merk->total }}</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
</main>
@endsection

@push('script')

<script>
document.addEventListener("DOMContentLoaded", function() {
    const chartDataJenis = {
        labels: {!! json_encode($datajenis->pluck('nama_jenis')) !!},
        datasets: [{
            data: {!! json_encode($datajenis->pluck('total')) !!},
            backgroundColor: ["#3b82f6", "#10b981", "#f59e0b", "#ef4444", "#8b5cf6"]
        }]
    };
    const chartDataMerk = {
        labels: {!! json_encode($datamerk->pluck('nama_merk')) !!},
        datasets: [{
            data: {!! json_encode($datamerk->pluck('total')) !!},
            backgroundColor: ["#3b82f6", "#10b981", "#f59e0b", "#ef4444", "#8b5cf6"]
        }]
    };

    new Chart(document.getElementById("chartjs-dashboard-pie-left"), {
        type: "pie",
        data: chartDataJenis,
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById("chartjs-dashboard-pie-right"), {
        type: "pie",
        data: chartDataMerk,
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
});
</script>

@endpush
