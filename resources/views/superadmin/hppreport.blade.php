@extends('superadmin.template.main')

@section('title', 'Report HPP - SAS')

@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i data-feather="file"></i> Filter Report HPP
                            </h5>

                            <div class="row g-3 align-items-end">

                                <div class="col-md-3">
                                    <label class="form-label small">Tanggal Awal</label>
                                    <input type="date" id="start_date" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label small">Tanggal Akhir</label>
                                    <input type="date" id="end_date" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label small">Jenis Service</label>
                                    <select id="jenis_service" class="form-select">
                                        <option value="">Semua</option>
                                        <option value="Cuci AC"> Cuci AC</option>
                                        <option value="Perbaikan">Perbaikan</option>
                                        <option value="Cek AC">Cek AC</option>
                                        <option value="Ganti Unit">Ganti Unit</option>
                                    </select>
                                </div>

                                <div class="col-md-3 d-flex gap-2">
                                    <button type="button" id="filter" class="btn btn-success w-100">
                                        <i data-feather="filter"></i> Filter
                                    </button>

                                    <button type="button" id="reset" class="btn btn-secondary w-100">
                                        Reset
                                    </button>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="#" id="exportPdf" class="btn btn-danger btn-sm">
                                    <i data-feather="file-text"></i> Export PDF
                                </a>
                            </div>

                        <hr class="my-4">

                        {{-- Table --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>No SPK</th>
                                        <th>Tanggal</th>
                                        <th>Teknisi</th>
                                        <th>Departement</th>
                                        <th>Ruangan</th>
                                        <th>Pekerjaan</th>
                                        <th>Total HPP</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                    <tr>
                                        <td colspan="8" class="text-center">Silahkan filter data</td>
                                    </tr>
                                </tbody>
                            </table>
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
document.addEventListener('DOMContentLoaded', function (){

    function loadData() {
        let start = document.getElementById('start_date').value;
        let end = document.getElementById('end_date').value;
        let jenis = document.getElementById('jenis_service').value;

        let tbody = document.getElementById('table-body');

        tbody.innerHTML = `<tr><td colspan="8" class="text-center">Loading...</td></tr>`;

        fetch(`{{ route('superadmin.hpp.data') }}?start_date=${start}&end_date=${end}&jenis_service=${jenis}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="8" class="text-center">Tidak ada data</td></tr>`;
                    return;
                }

                let html = '';

                data.forEach(row => {
                    html += `
                        <tr>
                            <td>${row.no}</td>
                            <td>${row.no_spk}</td>
                            <td>${row.tanggal}</td>
                            <td>${row.teknisi}</td>
                            <td>${row.departement}</td>
                            <td>${row.ruangan}</td>
                            <td>${row.pekerjaan}</td>
                            <td>${row.total_hpp}</td>
                        </tr>
                    `;
                });

                tbody.innerHTML = html;
            })
            .catch(() => {
                tbody.innerHTML = `<tr>
                    <td colspan="8" class="text-center text-danger">
                        Gagal mengambil data
                    </td>
                </tr>`;
            });
    }

    document.getElementById('filter').addEventListener('click', function() {
        
        let start = document.getElementById('start_date').value;
        let end = document.getElementById('end_date').value;

        if (!start || !end) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Tanggal Awal dan Tanggal Akhir wajib diisi!',
            });
            return;
        }

        loadData();
    });

    document.getElementById('reset').addEventListener('click', function() {
        document.getElementById('start_date').value = '';
        document.getElementById('end_date').value = '';
        document.getElementById('jenis_service').value = '';
        loadData();
    });

    document.getElementById('exportPdf').addEventListener('click', function (e) {
        e.preventDefault();

        let start = document.getElementById('start_date').value;
        let end   = document.getElementById('end_date').value;
        let jenis = document.getElementById('jenis_service').value;

        if (!start || !end) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Tanggal Awal dan Tanggal Akhir wajib diisi',
            });
            return;
        }

        let url   = new URL("{{ route('superadmin.hpp.export') }}");

        url.searchParams.append('start_date', start);
        url.searchParams.append('end_date', end);

        if (jenis) {
            url.searchParams.append('jenis_service', jenis);
        }
        
        window.open(url.toString(), '_blank');
    });

    loadData();
});
</script>
@endpush