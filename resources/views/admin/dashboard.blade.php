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

			{{-- Filter Bulan --}}
			<div class="d-flex justify-content-end mb-3">
				<select id="filter-bulan" class="form-select w-auto">
					<option value="">Semua Bulan</option>
					@foreach ([
						1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
						7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
					] as $num => $nama)
						<option value="{{ $num }}">{{ $nama }}</option>
					@endforeach
				</select>
			</div>
			<div class="row g-3">
				{{-- Cuci AC --}}
				<div class="col-12 col-lg-4">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title mb-0">Cuci AC</h5>
						</div>
						<div class="card-body">
							<div class="chart chart-xs mb-3">
								<canvas id="chart-cuci"></canvas>
							</div>

							<table class="table mb-0">
								<tbody>
									{{-- @foreach ($datajenis as $jenis)
									<tr>
										<td>{{ $jenis->nama_jenis }}</td>
										<td class="text-end">{{ $jenis->total }}</td>
									</tr>
									@endforeach --}}
								</tbody>
							</table>
						</div>
					</div>
				</div>

				{{-- Perbaikan --}}
				<div class="col-12 col-lg-4">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title mb-0">Perbaikan</h5>
						</div>
						<div class="card-body">
							<div class="chart chart-xs mb-3">
								<canvas id="chart-perbaikan"></canvas>
							</div>

							<table class="table mb-0">
								<tbody>
									{{-- @foreach ($datamerk as $merk)
									<tr>
										<td>{{ $merk->nama_merk }}</td>
										<td class="text-end">{{ $merk->total }}</td>
									</tr>
									@endforeach --}}
								</tbody>
							</table>
						</div>
					</div>
				</div>

				{{-- Ganti Unit --}}
				<div class="col-12 col-lg-4">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title mb-0">Ganti Unit</h5>
						</div>
						<div class="card-body">
							<div class="chart chart-xs mb-3">
								<canvas id="chart-ganti"></canvas>
							</div>

							<table class="table mb-0">
								<tbody>
									{{-- @foreach ($dataganti as $item)
									<tr>
										<td>{{ $item->nama }}</td>
										<td class="text-end">{{ $item->total }}</td>
									</tr>
									@endforeach --}}
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			{{-- Line Chart --}}
			{{-- <div class="row g-3 mt-2">
				<div class="col-12">
					<div class="card">
						<div class="card-header d-flex justify-content-between align-items-center flex-wrap">
							<h5 class="card-title mb-0">GRAFIK DATA AC</h5> <br>
							<select id="jenis_service" class="form-select form-select-sm" style="width:180px;">
								<option value="cuci">Cuci AC</option>
								<option value="perbaikan">Perbaikan</option>
								<option value="ganti">Ganti Unit</option>
							</select>
						</div>
						<div class="card-body">
							<div class="chart">
								<canvas id="chartjs-dashboard-line"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div> --}}
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
// let serviceChart;

document.addEventListener("DOMContentLoaded", function() {


	let chartCuci, chartPerbaikan, chartGanti;

    function createChart(canvasId) {
        return new Chart(document.getElementById(canvasId), {
            type:'doughnut',
            data:{
                labels:['Semua'],
                datasets:[{ 
					data:[0],
					backgroundColor: ['#4e73df']
				}]
            },
            options:{ responsive:true }
        });
    }

    chartCuci = createChart('chart-cuci');
    chartPerbaikan = createChart('chart-perbaikan');
    chartGanti = createChart('chart-ganti');


    function updateChart(chart, semua, bulan, labelBulan){

        if(labelBulan){
            // 🔥 mode compare
            chart.data.labels = ['Semua', labelBulan];
            chart.data.datasets[0].data = [semua, bulan];

			chart.data.datasets[0].backgroundColor = [
            '#4e73df', // semua (biru)
            '#1cc88a'  // bulan (hijau)
        ];

        }else{
            // 🔥 mode default
            chart.data.labels = ['Semua'];
            chart.data.datasets[0].data = [semua];

			chart.data.datasets[0].backgroundColor = [
            	'#4e73df'
        	];
        }

        chart.update();
    }


    function loadChartData(bulan=''){
        fetch("{{ route('dashboard.chart') }}?bulan="+bulan)
        .then(r=>r.json())
        .then(data=>{

            updateChart(chartCuci,
                data.semua.cuci,
                data.bulan.cuci,
                data.bulan_label
            );

            updateChart(chartPerbaikan,
                data.semua.perbaikan,
                data.bulan.perbaikan,
                data.bulan_label
            );

            updateChart(chartGanti,
                data.semua.ganti,
                data.bulan.ganti,
                data.bulan_label
            );
        });
    }

    loadChartData('');

    document.getElementById('filter-bulan')
        .addEventListener('change',function(){
            loadChartData(this.value);
        });

    // ===============================
    // PIE CHART (TETAP SEPERTI BIASA)
    // ===============================
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

    // ===============================
    // LINE CHART (INITIAL LOAD)
    // ===============================

//     const ctx = document.getElementById("chartjs-dashboard-line");

//     serviceChart = new Chart(ctx, {
//         type: "line",
//         data: {
//             labels: [],
//             datasets: [{
//                 label: "Total Service {{ now()->year }}",
//                 data: [],
//                 borderColor: "#3b82f6",
//                 backgroundColor: "rgba(59,130,246,0.2)",
//                 tension: 0.4,
//                 fill: true
//             }]
//         },
//         options: {
//             responsive: true,
//             scales: {
//                 y: {
//                     beginAtZero: true,
//                     ticks: {
//                         stepSize: 1,
//                         callback: function(value) {
//                             return Number.isInteger(value) ? value : null;
//                         }
//                     }
//                 }
//             }
//         }
//     });

//     // Load pertama kali
//     loadChartData('cuci');

//     // Event dropdown
//     document.getElementById('jenis_service')
//         .addEventListener('change', function () {
//             loadChartData(this.value);
//         });
// });

// function loadChartData(jenis) {

//     fetch("{{ route('dashboard.chart') }}?jenis_service=" + jenis)
//         .then(response => response.json())
//         .then(data => {

//             serviceChart.data.labels = data.labels;
//             serviceChart.data.datasets[0].data = data.totals;

//             serviceChart.update();
//         })
//         .catch(error => console.error(error));
// }

</script>
@endpush
