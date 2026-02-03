@extends('admin.template.main')

@section('title', 'History - SAS')

@section('content')
<main class="content">
    <div class="page-content mt-n4">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title mb-4">Cari History Service AC</h5>

                        {{-- FORM SEARCH --}}
                        <form id="searchForm" class="mb-4">
                            <div class="row g-2">
                                <div class="col-md-8">
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="noAcInput"
                                        placeholder="Masukkan No AC (contoh: I-0001)"
                                        required
                                    >
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i data-feather="search"></i> Cari
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button type="reset" class="btn btn-secondary w-100">
                                        <i data-feather="x"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- INFO HASIL --}}
                        <div id="resultInfo" class="alert alert-info d-none">
                            History untuk No AC: <strong id="searchedNoAc"></strong>
                        </div>

                        {{-- TABLE --}}
                        <div id="tableContainer" class="d-none">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>No AC</th>
                                            <th>No SPK</th>
                                            <th>Tanggal</th>
                                            <th>Keluhan</th>
                                            <th>Jenis Pekerjaan</th>
                                            <th>Pelaksana</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="historyTableBody"></tbody>
                                </table>
                            </div>
                        </div>

                        {{-- TIDAK ADA DATA --}}
                        <div id="noResultsMessage" class="alert alert-warning d-none">
                            Tidak ada history ditemukan untuk No AC ini.
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
$(document).ready(function () {

    $('#searchForm').on('submit', function (e) {
        e.preventDefault();

        let noAc = $('#noAcInput').val().trim();

        if (!noAc) {
            Swal.fire('Peringatan', 'No AC tidak boleh kosong', 'warning');
            return;
        }

        $('#searchedNoAc').text(noAc);
        $('#resultInfo').removeClass('d-none');
        $('#tableContainer').addClass('d-none');
        $('#noResultsMessage').addClass('d-none');

        $.ajax({
            url: "{{ route('history.search') }}",
            method: 'GET',
            data: { no_ac: noAc },
            success: function (res) {
                let data = res.data;

                if (data.length === 0) {
                    $('#noResultsMessage').removeClass('d-none');
                    return;
                }

                let html = '';
                data.forEach((item, index) => {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.no_ac}</td>
                            <td>${item.no_spk}</td>
                            <td>${item.tanggal}</td>
                            <td>${item.keluhan}</td>
                            <td>${item.jenis_pekerjaan}</td>
                            <td>${item.pelaksana_nama}</td>
                            <td>
                                <a href="/admin/spk/detail/${item.id}"
                                   class="btn btn-sm btn-info">
                                    <i data-feather="eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    `;
                });

                $('#historyTableBody').html(html);
                $('#tableContainer').removeClass('d-none');
                feather.replace();
            },
            error: function () {
                Swal.fire('Error', 'Gagal mengambil data history', 'error');
            }
        });
    });

    $('#searchForm').on('reset', function () {
        $('#resultInfo').addClass('d-none');
        $('#tableContainer').addClass('d-none');
        $('#noResultsMessage').addClass('d-none');
        $('#historyTableBody').html('');
    });

});
</script>
@endpush