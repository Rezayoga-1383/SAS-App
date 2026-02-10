@extends('admin.template.main')

@section('title', 'Edit Detail AC - SAS')

@section('content')
<main class="content">
	<div class="page-content mt-n4">
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-center mb-4" id="top-content">
							<h4 class="card-title mb-3">Edit Detail AC</h4>
						</div>

                        <form action="{{ route('detail-ac.update', $acdetail->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')
            
                            <div class="mb-3">
                                <label for="id_merkac" class="form-label">Merk AC <span class="text-danger">*</span> </label>
                                <select name="id_merkac" id="id_merkac" class="form-control @error('id_merkac') is-invalid @enderror" required>
                                    <option value="">-- Pilih Merk --</option>
                                    @foreach($merkac as $m)
                                        <option value="{{ $m->id }}" {{ $acdetail->id_merkac == $m->id ? 'selected' : '' }}>
                                            {{ $m->nama_merk }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_merkac')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="id_jenisac" class="form-label">Jenis AC <span class="text-danger">*</span> </label>
                                <select name="id_jenisac" id="id_jenisac" class="form-control @error('id_jenisac') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($jenisac as $j)
                                        <option value="{{ $j->id }}" {{ $acdetail->id_jenisac == $j->id ? 'selected' : '' }}>
                                            {{ $j->nama_jenis }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_jenisac')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="id_ruangan" class="form-label">Ruangan <span class="text-danger">*</span> </label>
                                <select name="id_ruangan" id="id_ruangan" class="form-select select2 @error('id_ruangan') is-invalid @enderror" required>
                                    <option value="">-- Pilih Ruangan --</option>
                                    @foreach($ruangan as $r)
                                        <option value="{{ $r->id }}" {{ $acdetail->id_ruangan == $r->id ? 'selected' : '' }}>
                                            {{ $r->nama_ruangan }} ({{ $r->departement->nama_departement ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_ruangan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="no_ac" class="form-label">Nomor AC <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <span class="input-group-text">I-</span>
                                    <input
                                        type="text"
                                        id="no_ac"
                                        name="no_ac"
                                        required
                                        class="form-control form-control-lg @error('no_ac') is-invalid @enderror"
                                        placeholder="Masukkan Nomor AC (contoh: 001)"
                                        value="{{ old('no_ac', $acdetail->no_ac ?? 'I-') }}">
                                    @error('no_ac')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="no_seri_indoor" class="form-label">No Seri Indoor <span class="text-danger">*</span> </label>
                                    <input type="text" id="no_seri_indoor" name="no_seri_indoor" value="{{ $acdetail->no_seri_indoor }}" class="form-control @error('no_seri_indoor') is-invalid @enderror" required>
                                    @error('no_seri_indoor')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="no_seri_outdoor" class="form-label">No Seri Outdoor <span class="text-danger">*</span> </label>
                                    <input type="text" id="no_seri_outdoor" name="no_seri_outdoor" value="{{ $acdetail->no_seri_outdoor }}" class="form-control @error('no_seri_outdoor') is-invalid @enderror" required>
                                    @error('no_seri_outdoor')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pk_ac" class="form-label">PK AC <span class="text-danger">*</span> </label>
                                    <input type="text" id="pk_ac" name="pk_ac" value="{{ $acdetail->pk_ac }}" class="form-control @error('pk_ac') is-invalid @enderror" required>
                                    @error('pk_ac')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="jumlah_ac" class="form-label">Jumlah AC <span class="text-danger">*</span> </label>
                                    <input type="number" id="jumlah_ac" name="jumlah_ac" value="{{ $acdetail->jumlah_ac }}" class="form-control @error('jumlah_ac') is-invalid @enderror" required>
                                    @error('jumlah_ac')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tahun_ac" class="form-label">Tahun AC <span class="text-danger">*</span> </label>
                                    <input type="text" id="tahun_ac" name="tahun_ac" value="{{ $acdetail->tahun_ac }}" class="form-control @error('tahun_ac') is-invalid @enderror" required>
                                    @error('tahun_ac')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_pemasangan" class="form-label">Tanggal Pemasangan <span class="text-danger">*</span> </label>
                                    <input type="date" id="tanggal_pemasangan" name="tanggal_pemasangan" value="{{ $acdetail->tanggal_pemasangan }}" class="form-control @error('tanggal_pemasangan') is-invalid @enderror" required>
                                    @error('tanggal_pemasangan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_habis_garansi" class="form-label">Tanggal Habis Garansi <span class="text-danger">*</span> </label>
                                <input type="date" id="tanggal_habis_garansi" name="tanggal_habis_garansi" value="{{ $acdetail->tanggal_habis_garansi }}" class="form-control @error('tanggal_habis_garansi') is-invalid @enderror" required>
                                @error('tanggal_habis_garansi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
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
    // Inisialisasi select2 untuk ruangan
    $('#id_ruangan').select2({
        placeholder: "-- Pilih Ruangan --",
        allowClear: true,
        width: '100%',
    });

    const form = document.querySelector("form");
    const noAcInput = document.getElementById("no_ac");

    // Format no_ac saat submit
    form.addEventListener("submit", function(e) {
        let val = noAcInput.value.trim();

        if (val !== '') {
            // Hapus semua karakter selain angka
            val = val.replace(/\D/g, '');
            // Ambil maksimal 3 digit & tambahkan leading zero
            val = val.substring(0, 4).padStart(4, '0');
            // Tambahkan prefix I-
            noAcInput.value = "I-" + val;
        }
    });

    // Format no_ac saat user mengetik
    noAcInput.addEventListener("input", function() {
        let val = this.value.replace(/\D/g, '');
        val = val.substring(0, 4); // maksimal 3 digit
        this.value = val;
    });

    // Jika value awal sudah ada (misal edit I-005), hapus prefix untuk ditampilkan di input
    if (noAcInput.value.startsWith("I-")) {
        noAcInput.value = noAcInput.value.replace("I-", '');
    }
});
</script>
@endpush
