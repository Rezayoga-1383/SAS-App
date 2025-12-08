@extends('admin.template.main')

@section('title', 'Tambah Detail AC - SAS')

@section('content')
<main class="content">
	<div class="page-content mt-n4">
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-center mb-4" id="top-content">
							<h4 class="card-title mb-3">Tambah Detail AC</h4>
						</div>

                        <form action="{{ route('detail-ac.store') }}" method="POST" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label for="id_merkac" class="form-label">Merk AC</label>
                                <select
                                    id="id_merkac"
                                    name="id_merkac"
                                    class="form-select form-select-lg @error('id_merkac') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Merk AC --</option>
                                    @foreach ($merkac as $item)
                                        <option value="{{ $item->id }}" {{ old('id_merkac') == $item->id ? 'selected' : '' }}> {{ $item->nama_merk }}</option>
                                    @endforeach
                                </select>
                                @error('id_merkac')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="id_jenisac" class="form-label">Jenis AC</label>
                                <select
                                    id="id_jenisac"
                                    name="id_jenisac"
                                    class="form-select form-select-lg @error('id_jenisac') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Jenis AC --</option>
                                    @foreach ($jenisac as $item)
                                        <option value="{{ $item->id }}" {{ old('id_jenisac') == $item->id ? 'selected' : '' }}> {{ $item->nama_jenis }}</option>
                                    @endforeach
                                </select>
                                @error('id_jenisac')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="id_ruangan" class="form-label">Ruangan & Departemen</label>
                                <select id="id_ruangan" name="id_ruangan"
                                    class="form-select form-select-lg select2 @error('id_ruangan') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Ruangan & Departemen --</option>
                                    @foreach ($ruangan as $item)
                                        <option value="{{ $item->id }}" {{ old('id_ruangan') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_ruangan }} - {{ $item->departement->nama_departement }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_ruangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="no_ac" class="form-label">Nomor AC</label>
                                <div class="input-group">
                                    <span class="input-group-text">I-</span>
                                    <input
                                        type="text"
                                        id="no_ac"
                                        name="no_ac"
                                        required
                                        class="form-control form-control-lg @error('no_ac') is-invalid @enderror"
                                        placeholder="Masukkan Nomor AC (contoh: 0001)"
                                        value="{{ old('no_ac') }}">
                                    @error('no_ac')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="no_seri_indoor" class="form-label">No Seri Indoor</label>
                                <input
                                    type="text"
                                    id="no_seri_indoor"
                                    name="no_seri_indoor"
                                    required
                                    class="form-control form-control-lg @error('no_seri_indoor') is-invalid @enderror"
                                    placeholder="Masukkan Nomor Seri AC Indoor" value="{{ old('no_seri_indoor') }}">
                                @error('no_seri_indoor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="no_seri_outdoor" class="form-label">No Seri Outdoor</label>
                                <input
                                    type="text"
                                    id="no_seri_outdoor"
                                    name="no_seri_outdoor"
                                    required
                                    class="form-control form-control-lg @error('no_seri_outdoor') is-invalid @enderror"
                                    placeholder="Masukkan Nomor Seri AC Outdoor" value="{{ old('no_seri_outdoor') }}">
                                @error('no_seri_outdoor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="pk_ac" class="form-label">PK AC</label>
                                <input
                                    type="number"
                                    id="pk_ac"
                                    name="pk_ac"
                                    required
                                    class="form-control form-control-lg @error('pk_ac') is-invalid @enderror"
                                    placeholder="Masukkan PK AC" value="{{ old('pk_ac') }}">
                                @error('pk_ac')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="jumlah_ac" class="form-label">Jumlah AC</label>
                                <input
                                    type="number"
                                    id="jumlah_ac"
                                    name="jumlah_ac"
                                    required
                                    class="form-control form-control-lg @error('jumlah_ac') is-invalid @enderror"
                                    placeholder="Masukkan Jumlah AC" value="{{ old('jumlah_ac') }}">
                                @error('jumlah_ac')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="tahun_ac" class="form-label">Tahun AC</label>
                                <input
                                    type="number"
                                    id="tahun_ac"
                                    name="tahun_ac"
                                    required
                                    class="form-control form-control-lg @error('tahun_ac') is-invalid @enderror"
                                    placeholder="Masukkan Tahun AC" value="{{ old('tahun_ac') }}">
                                @error('tahun_ac')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="tanggal_pemasangan" class="form-label">Tanggal Pemasangan</label>
                                <input
                                    type="date"
                                    id="tanggal_pemasangan"
                                    name="tanggal_pemasangan"
                                    required
                                    class="form-control form-control-lg @error('tanggal_pemasangan') is-invalid @enderror"
                                    placeholder="Masukkan Tanggal Pemasangan AC" value="{{ old('tanggal_pemasangan') }}">
                                @error('tanggal_pemasangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="tanggal_habis_garansi" class="form-label">Tanggal Habis Garansi</label>
                                <input
                                    type="date"
                                    id="tanggal_habis_garansi"
                                    name="tanggal_habis_garansi"
                                    required
                                    class="form-control form-control-lg @error('tanggal_habis_garansi') is-invalid @enderror"
                                    placeholder="Masukkan Tanggal Habis Garansi AC" value="{{ old('tanggal_habis_garansi') }}">
                                @error('tanggal_habis_garansi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('detail-ac') }}" class="btn btn-outline-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save" class="me-1"></i> Simpan Data
                                </button>
                            </div>
                        </form>
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
    
    // Inisialisasi Select2 untuk semua select dengan class .select2
    $('.select2').select2({
        placeholder: "-- Pilih Ruangan & Departemen --",
        allowClear: true,
        width: '100%' // pastikan full width
    });

    const form = document.querySelector("form");
    const noAcInput = document.getElementById("no_ac");

    form.addEventListener("submit", function(e) {
        let val = noAcInput.value.trim();

        if (val !== '') {
            // Ambil hanya angka, hapus karakter selain digit
            val = val.replace(/\D/g, '');

            // Tambahkan leading zero (maksimal 4 digit)
            val = val.substring(0, 4).padStart(4, '0');

            // Tambahkan prefix I-
            noAcInput.value = "I-" + val;
        }
    });

    // Optional: bantu user input — otomatis tambahkan nol di depan saat mengetik
    noAcInput.addEventListener("input", function() {
        let val = this.value.replace(/\D/g, '');
        val = val.substring(0, 4);
        this.value = val;
    });
});
</script>
@endpush