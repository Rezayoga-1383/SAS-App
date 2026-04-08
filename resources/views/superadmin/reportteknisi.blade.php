@extends('superadmin.template.main')

@section('title', 'Report Teknisi - SAS')

@section('content')
    <main class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card shadow-sm">
                        <div class="card-body">

                            <h5 class="crad-title mb-4">
                                <i data-feather="users"></i> Report Teknisi
                            </h5>

                            {{-- Filter Section --}}
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

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Teknisi</th>
                                            <th>Cuci AC</th>
                                            <th>Perbaikan</th>
                                            <th>Cek AC</th>
                                            <th>Ganti Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reportBody">
                                    </tbody>
                                </table>
                            </div>

                            {{-- Message No Data --}}
                            <div id="noResultMessage" class="alert alert-warning text-center d-none mt-4">
                                Data Tidak Ditemukan
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
                    text: 'Silahkan isi Tanggal Awal dan Tanggal Akhir terlebih dahulu',
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

            function loadData() {
                let start_date = $('#start_date').val();
                let end_date = $('#end_date').val();

                 $.ajax({
                    url: "{{ route('superadmin.teknisi.data') }}",
                    type: "GET",
                    data:{
                        start_date:start_date,
                        end_date:end_date
                    },
                    success:function(res){

                        let html='';
                        let no=1;

                        if(res.length === 0){
                            $('#reportBody').html('');
                            $('#noResultMessage').removeClass('d-none');
                            return;
                        }

                        $('#noResultMessage').addClass('d-none');

                        res.forEach(function(item){

                            html+=`
                            <tr>
                                <td>${no++}</td>
                                <td>${item.nama}</td>
                                <td>${item.cuci_ac}</td>
                                <td>${item.perbaikan}</td>
                                <td>${item.cek_ac}</td>
                                <td>${item.ganti_unit}</td>
                            </tr>
                            `;
                        });

                        $('#reportBody').html(html);
                    }
                });

            }

            $('#filter').click(function(){
                if(!isFilterValid()){
                    showErrorAlert();
                    return;
                }

                loadData();
            });

            $('#reset').click(function(){
                $('#start_date').val('');
                $('#end_date').val('');
                $('#reportBody').html('');
                $('#noResultMessage').addClass('d-none');
            });

            // export PDF
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

                let url = "{{ route('superadmin.reportteknisipdf') }}?start_date=" + start_date + "&end_date=" + end_date;

                window.location.href = url;
            });
        });
    </script>
@endpush