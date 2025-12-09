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
                                <label for="jumlah_orang">Jumlah Teknisi</label>
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

                            <div class="col-sm-6">
                                <label for="jumlah_ac_input" class="form-label">Jumlah AC yang ingin diperbaiki</label>
                                <input
                                    type="number"
                                    id="jumlah_ac_input"
                                    name="jumlah_ac_input"
                                    required
                                    class="form-control form-control-md @error('jumlah_ac_input') is-invalid @enderror"
                                    placeholder="Masukkan jumlah AC yang ingin diperbaiki"
                                    value="{{ old('jumlah_ac_input') }}">
                                @error('jumlah_ac_input')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <hr>
                                <h5 class="mb-3">Detail AC yang Diperbaiki</h5>
                                <div id="ac_container">
                                
                                </div>
                                @error('acdetail_ids')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
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
  const jumlahAcInput = document.getElementById('jumlah_ac_input');
  const acContainer = document.getElementById('ac_container');
  const jumlahOrangInput = document.getElementById('jumlah_orang');
  const checkboxes = document.querySelectorAll('.teknisi-checkbox');
  const pelaksanaSelect = document.getElementById('pelaksana_ttd');

  // Ambil data dari server (Blade)
  const acdetailData = @json($acdetail->pluck('no_ac', 'id'));
  const oldKeluhanData = @json(old('keluhan', []));
  const oldJenisPekerjaanData = @json(old('jenis_pekerjaan', []));
  const oldAcdetailIds = @json(old('acdetail_ids', []));

  // Error messages
  const errorAcdetail = @json($errors->get('acdetail_ids.*'));
  const errorKeluhan = @json($errors->get('keluhan.*'));
  const errorJenisPekerjaan = @json($errors->get('jenis_pekerjaan.*'));

  function generateAcForms() {
    const jumlah = parseInt(jumlahAcInput.value) || 0;
    acContainer.innerHTML = '';

    for (let i = 0; i < jumlah; i++) {

      const acCard = document.createElement('div');
      acCard.className = 'card mb-3 border';

      const oldAcId = oldAcdetailIds[i] || '';
      const oldKeluhan = oldKeluhanData[i] || '';
      const oldJenisPekerjaan = oldJenisPekerjaanData[i] || '';

      // ambil error dengan key yang benar (Laravel format)
      const errAc = errorAcdetail && errorAcdetail[`acdetail_ids.${i}`]
          ? errorAcdetail[`acdetail_ids.${i}`][0]
          : '';

      const errKeluhan = errorKeluhan && errorKeluhan[`keluhan.${i}`]
          ? errorKeluhan[`keluhan.${i}`][0]
          : '';

      const errJenis = errorJenisPekerjaan && errorJenisPekerjaan[`jenis_pekerjaan.${i}`]
          ? errorJenisPekerjaan[`jenis_pekerjaan.${i}`][0]
          : '';


      acCard.innerHTML = `
        <div class="card-body">
          <h6 class="card-title mb-3">AC #${i + 1}</h6>

          <!-- Nomor AC -->
          <div class="mb-3">
            <label class="form-label">Nomor AC <span class="text-danger">*</span></label>
            <select name="acdetail_ids[]" class="form-select ${errAc ? 'is-invalid' : ''}">
              <option value="">-- Pilih No AC --</option>
              ${Object.entries(acdetailData)
                .map(([id, noAc]) => `<option value="${id}" ${oldAcId == id ? 'selected' : ''}>${noAc}</option>`)
                .join('')}
            </select>
            ${errAc ? `<div class="invalid-feedback d-block">${errAc}</div>` : ''}
          </div>

          <!-- Keluhan -->
          <div class="mb-3">
            <label class="form-label">Keluhan <span class="text-danger">*</span></label>
            <textarea name="keluhan[]" class="form-control ${errKeluhan ? 'is-invalid' : ''}"
              rows="3" placeholder="Masukkan keluhan">${oldKeluhan}</textarea>
            ${errKeluhan ? `<div class="invalid-feedback d-block">${errKeluhan}</div>` : ''}
          </div>

          <!-- Jenis Pekerjaan -->
          <div class="mb-3">
            <label class="form-label">Jenis Pekerjaan <span class="text-danger">*</span></label>
            <textarea name="jenis_pekerjaan[]" class="form-control ${errJenis ? 'is-invalid' : ''}"
              rows="3" placeholder="Masukkan jenis pekerjaan">${oldJenisPekerjaan}</textarea>
            ${errJenis ? `<div class="invalid-feedback d-block">${errJenis}</div>` : ''}
          </div>
        </div>
      `;

      acContainer.appendChild(acCard);
    }
  }

  // Limit teknisi sesuai "jumlah orang"
  function updateCheckboxLimit() {
    const maxTeknisi = parseInt(jumlahOrangInput.value) || 0;
    const checkedCount = document.querySelectorAll('.teknisi-checkbox:checked').length;

    checkboxes.forEach(cb => {
      cb.disabled = !cb.checked && checkedCount >= maxTeknisi;
    });

    updatePelaksanaOptions();
  }

  // Pelaksana hanya dari teknisi yang dipilih
  function updatePelaksanaOptions() {
    const selectedIds = Array.from(checkboxes)
      .filter(cb => cb.checked)
      .map(cb => cb.value);

    Array.from(pelaksanaSelect.options).forEach(option => {
      if (option.value === "") return;
      option.style.display = selectedIds.includes(option.value) ? 'block' : 'none';
    });

    if (!selectedIds.includes(pelaksanaSelect.value)) {
      pelaksanaSelect.value = "";
    }
  }

  // Event listeners
  jumlahAcInput.addEventListener('input', generateAcForms);
  checkboxes.forEach(cb => cb.addEventListener('change', updateCheckboxLimit));
  jumlahOrangInput.addEventListener('input', updateCheckboxLimit);

  // Initialize saat halaman pertama kali dibuka
  document.addEventListener('DOMContentLoaded', () => {
    generateAcForms();
    updateCheckboxLimit();
  });
</script>
@endpush