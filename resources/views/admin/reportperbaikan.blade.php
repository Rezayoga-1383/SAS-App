@extends('admin.template.main')

@section('title', 'Report Perbaikan Ulang - SAS')

@section('content')
    <main class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">

                    <div class="card shadow-sm">
                        <div class="card-body">

                            <h5 class="card-title mb-4">
                                <i data-feather="tool"></i> Report Perbaikan Ulang
                            </h5>

                            {{-- FILTER SECTION --}}
                                <div class="row g-3 align-items-end">

                                    <div class="col-md-4">
                                        <label class="form-label small">Tanggal Awal</label>
                                        <input type="date" id="start_date" class="form-control">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label small">Tanggal Akhir</label>
                                        <input type="date" id="end_date" class="form-control">
                                    </div>

                                    <div class="col-md-4 d-flex gap-2">
                                        <button type="button" id="filter" class="btn btn-success w-100">
                                            <i data-feather="filter"></i> Filter
                                        </button>

                                        <button type="button" id="reset" class="btn btn-secondary w-100">
                                            Reset
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="#" id="exportpdf" class="btn btn-danger btn-sm">
                                        <i data-feather="file-text"></i> Export PDF
                                    </a>
                                </div>

                            <hr class="my-4">
                            
                            <div class="mb-3 d-flex gap-4">
                                <h6 class="mb-0">
                                    Total AC Perbaikan Ulang :
                                    <span id="totalAC" class="badge bg-danger">0</span> Unit AC
                                </h6>
                                <h6 class="mb-0">
                                    Total Aktivitas Perbaikan Ulang :
                                    <span id="totalPerbaikan" class="badge bg-danger">0</span> Kali
                                </h6>
                            </div>
                            {{-- Output Filter Perbaikan Ulang--}}
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>No AC</th>
                                            <th>Ruangan</th>
                                            <th>Departement</th>
                                            <th>Keluhan</th>
                                            <th>Jenis Pekerjaan</th>
                                            <th>Teknisi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reportBody">
                                    </tbody>
                                </table>
                            </div>

                            {{-- NO DATA --}}
                            <div id="noResultMessage" class="alert alert-warning text-center d-none mt-4">
                                Data Perbaikan Ulang Tidak Ditemukan
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
        $(document).ready(function(){

        function showErrorAlert() {
            Swal.fire({
                icon: 'error',
                title: 'Tidak Bisa Menampilkan Data',
                text: 'Silahkan isi Tanggal Awal dan Tanggal Akhir terlebih dahulu!',
                confirmButtonColor: '#d33'
            });
        }

        function isFilterValid() {
            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();

            if (!start_date || !end_date) {
                return false;
            }

            return true;
        }

        function fetchData() {

            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();

            $.ajax({
                url: "{{ route('admin.data.perbaikan') }}",
                type: "GET",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                },
                success: function(response) {

                    let tbody = $('#reportBody');
                    tbody.empty();

                    let totalac = response.totalac;
                    $('#totalAC').text(totalac); 

                    let totalperbaikan = response.totalperbaikan;
                    $('#totalPerbaikan').text(totalperbaikan);

                    if(response.data.length === 0) {
                        $('#noResultMessage').removeClass('d-none');
                        return;
                    }

                    $('#noResultMessage').addClass('d-none');

                    let no = 1;

                    response.data.forEach(function(item){

                        let tanggal = item.tanggal;
                        let no_ac = item.no_ac;
                        let ruangan = item.ruangan;
                        let departement = item.departement;
                        let keluhan = item.keluhan;
                        let jenispekerjaan = item.jenis_pekerjaan;
                        let teknisi = item.teknisi;

                        let row = `
                            <tr>
                                <td>${no++}</td>
                                <td>${tanggal}</td>
                                <td>${no_ac}</td>
                                <td>${ruangan}</td>
                                <td>${departement}</td>
                                <td>${keluhan}</td>
                                <td>${jenispekerjaan}</td>
                                <td>${teknisi}</td>
                            </tr>
                        `;

                        tbody.append(row);

                    });

                }
            });

        }

        $('#filter').click(function(){

            if(!isFilterValid()){
                showErrorAlert();
                return;
            }

            fetchData();

        });

        $('#reset').click(function(){

            $('#start_date').val('');
            $('#end_date').val('');

            $('#reportBody').empty();
            $('#totalAC').text('0');
            $('#totalPerbaikan').text('0');

            $('#noResultMessage').addClass('d-none');
        });

        // EXPORT PDF
        $('#exportpdf').click(function(e){

            e.preventDefault();

            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();

            if(!start_date || !end_date){
                Swal.fire({
                    icon: 'error',
                    title: 'Tanggal belum diisi',
                    text: 'Silahkan isi tanggal terlebih dahulu'
                });
                return;
            }

            let url = "{{ route('admin.reportpdf') }}?start_date=" + start_date + "&end_date=" + end_date;

            window.location.href = url;
        });
    });
    </script>
@endpush