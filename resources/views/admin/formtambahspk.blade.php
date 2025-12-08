@extends('admin.template.main')

@section('title', 'Tambah SPK - SAS')

@section('content')
<main class="content">
	<div class="page-content mt-n4">
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-center mb-4" id="top-content">
							<h4 class="card-title mb-3">Tambah SPK</h4>
						</div>

                        <form action="{{ route('spkadmin.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label for="id_acdetail" class="form-label">Nomor AC</label>
                                <select name="id_acdetail" id="id_acdetail" class="form-select form-select-md @error('id_acdetail') is-invalid @enderror" required>
                                    <option value="">-- Pilih No AC --</option>
                                    @foreach ($acdetail as $item)
                                        <option value="{{ $item->id }}"  {{ old('id_acdetail') == $item->id ? 'selected' : '' }}>{{ $item->no_ac }}</option>
                                    @endforeach
                                </select>
                                @error('id_acdetail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="no_spk" class="form-label">Nomor SPK</label>
                                <input 
                                type="text"
                                id="no_spk"
                                name="no_spk"
                                required
                                class="form-control form-control-md @error('no_spk') is-invalid @enderror"
                                placeholder="Masukkan Nomor SPK" value="{{ old('no_spk') }}">
                                @error('no_spk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input 
                                    type="date"
                                    id="tanggal"
                                    name="tanggal"
                                    required
                                    class="form-control form-control-md @error('tanggal') is-invalid @enderror"
                                    placeholder="Masukkan tanggal yang sesuai" value="{{ old('tanggal') }}">
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                                <input 
                                    type="time"
                                    id="waktu_mulai"
                                    name="waktu_mulai"
                                    required
                                    class="form-control form-control-md @error('waktu_mulai') is-invalid @enderror"
                                    placeholder="Waktu mulai pengerjaan"
                                    value="{{ old('waktu_mulai') }}">
                                @error('waktu_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="waktu_selesai">Waktu Selesai</label>
                                <input 
                                    type="time"
                                    id="waktu_selesai"
                                    name="waktu_selesai"
                                    required
                                    class="form-control form-control-md @error('waktu_selesai') is-invalid @enderror"
                                    placeholder="Waktu selesai pengerjaan"
                                    value="{{ old('waktu_selesai') }}">
                                @error('waktu_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="jumlah_orang">Jumlah Orang</label>
                                <input 
                                    type="number"
                                    id="jumlah_orang"
                                    name="jumlah_orang"
                                    required
                                    class="form-control form-control-md @error('jumlah_orang') is-invalid @enderror"
                                    placeholder="Jumlah orang yang mengerjakan"
                                    value="{{ old('jumlah_orang') }}">
                                @error('jumlah_orang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Teknisi</label>
                                <div class="border rounded p-2 @error('teknisi') is-invalid @enderror" style="max-height: 150px; overflow-y:auto">
                                    @foreach ($teknisi as $user)
                                        <div class="form-check">
                                            <input
                                                class="form-check-input teknisi-checkbox"
                                                type="checkbox"
                                                name="teknisi[]"
                                                id="teknisi{{ $user->id }}"
                                                value="{{ $user->id }}"
                                                {{ in_array($user->id, old('teknisi', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="teknisi{{ $user->id }}">
                                                {{ $user->nama }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('teknisi')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="keluhan" class="form-label">Keluhan</label>
                                <textarea
                                    id="keluhan"
                                    name="keluhan"
                                    required
                                    class="form-control form-control-md @error('keluhan') is-invalid @enderror"
                                    placeholder="Masukkan Keluhan">{{ old('keluhan') }}</textarea>
                                @error('keluhan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="jenis_pekerjaan" class="form-label">Jenis Pekerjaan</label>
                                <textarea
                                    id="jenis_pekerjaan"
                                    name="jenis_pekerjaan"
                                    required
                                    class="form-control form-control-md @error('jenis_pekerjaan') is-invalid @enderror"
                                    placeholder="Masukkan jenis pekerjaan">{{ old('jenis_pekerjaan') }}</textarea>
                                @error('jenis_pekerjaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="kepada" class="form-label">Kepada</label>
                                <input
                                    type="text"
                                    id="kepada"
                                    name="kepada"
                                    required
                                    class="form-control form-control-md @error('kepada') is-invalid @enderror"
                                    placeholder="Kepada"
                                    value="{{ old('kepada') }}">
                                @error('kepada')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mengetahui" class="form-label">Mengetahui</label>
                                <input 
                                    type="text"
                                    id="mengetahui"
                                    name="mengetahui"
                                    required
                                    class="form-control form-control-md @error('mengetahui') is-invalid @enderror"
                                    placeholder="Mengetahui"
                                    value="{{ old('mengetahui') }}">
                                @error('mengetahui')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="hormat_kami" class="form-label">Hormat Kami</label>
                                <select name="hormat_kami" id="hormat_kami" class="form-select @error('hormat_kami') is-invalid @enderror" required>
                                    <option value="">-- Pilih Admin --</option>
                                    @foreach ($admin as $user )
                                        <option value="{{ $user->id }}" {{ old('hormat_kami') == $user->id ? 'selected' : ''}}>
                                            {{ $user->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hormat_kami')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="pelaksana_ttd" class="form-label">Pelaksana</label>
                                <select name="pelaksana_ttd" id="pelaksana_ttd" class="form-select @error('pelaksana_ttd') is-invalid @enderror" required>
                                    <option value="">-- Pilih Pelaksana --</option>
                                    @foreach ($teknisi as $user )
                                        <option value="{{ $user->id }}" {{ old('pelaksana_ttd') == $user->id ? 'selected' : ''}}>
                                            {{ $user->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pelaksana_ttd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="file_spk">Upload File SPK</label>
                                <input 
                                    type="file"
                                    id="file_spk"
                                    name="file_spk"
                                    class="form-control form-control-md @error('file_spk') is-invalid @enderror"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    required>
                                @error('file_spk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('admin.spk') }}" class="btn btn-outline-secondary">Batal</a>
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
    const jumlahInput = document.getElementById('jumlah_orang');
    const checkboxes = document.querySelectorAll('.teknisi-checkbox');
    const pelaksanaSelect = document.getElementById('pelaksana_ttd');

    function updateCheckboxLimit() {
        const maxTeknisi = parseInt(jumlahInput.value) || 0;
        const checkedCount = document.querySelectorAll('.teknisi-checkbox:checked').length;

        checkboxes.forEach(cb => {
            if (!cb.checked && checkedCount >= maxTeknisi) {
                cb.disabled = true;
            } else {
                cb.disabled = false;
            }
        });

        updatePelaksanaOptions();
    }

    function updatePelaksanaOptions() {
        const selectedIds = Array.from(checkboxes)
                                .filter(cb => cb.checked)
                                .map(cb => cb.value);

        Array.from(pelaksanaSelect.options).forEach(option => {
            if(option.value === "") return; // placeholder
            option.style.display = selectedIds.includes(option.value) ? 'block' : 'none';
        });

        // Reset value dropdown jika tidak ada lagi di list
        if(!selectedIds.includes(pelaksanaSelect.value)) {
            pelaksanaSelect.value = "";
        }
    }

    // Event listeners
    checkboxes.forEach(cb => cb.addEventListener('change', updateCheckboxLimit));
    jumlahInput.addEventListener('input', () => {
        const maxTeknisi = parseInt(jumlahInput.value) || 0;
        const checked = document.querySelectorAll('.teknisi-checkbox:checked');

        // Jika checked lebih dari limit, hapus yang kelebihan
        if (checked.length > maxTeknisi) {
            checked.forEach((cb, index) => {
                if (index >= maxTeknisi) cb.checked = false;
            });
        }

        updateCheckboxLimit();
    });

    // Jalankan saat load untuk inisialisasi
    updateCheckboxLimit();
</script>
@endpush