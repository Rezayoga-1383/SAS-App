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
                                <label for="no_spk" class="form-label">Nomor SPK <span class="text-danger">*</span></label>
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
                                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
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
                                <label for="waktu_mulai" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
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
                                <label for="waktu_selesai">Waktu Selesai <span class="text-danger">*</span></label>
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
                                <label for="jumlah_orang">Jumlah Teknisi <span class="text-danger">*</span></label>
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
                                <label class="form-label">Teknisi <span class="text-danger">*</span></label>
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
                                <label for="jumlah_ac_input" class="form-label">Jumlah AC yang ingin diperbaiki <span class="text-danger">*</span></label>
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
                                <h5 class="mb-3">Detail AC yang Diperbaiki <span class="text-danger">*</span></h5>
                                <div id="ac_container">
                                
                                </div>
                                @error('acdetail_ids')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="kepada" class="form-label">Kepada <span class="text-danger">*</span></label>
                                <select name="kepada" id="kepada" required class="form-select form-control-md select2 @error('kepada') is-invalid @enderror">
                                    <option value="">-- Pilih Kepada -- </option>
                                    @foreach ($departement as $dept)
                                        <option value="{{ $dept->nama_departement }}" {{ old('kepada') == $dept->nama_departement ? 'selected' : '' }}>
                                            {{ $dept->nama_departement }}
                                        </option>  
                                    @endforeach
                                </select>
                                @error('kepada')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mengetahui" class="form-label">Mengetahui <span class="text-danger">*</span></label>
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
                                <label for="hormat_kami" class="form-label">Hormat Kami <span class="text-danger">*</span></label>
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
                                <label for="pelaksana_ttd" class="form-label">Pelaksana <span class="text-danger">*</span></label>
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
                                <label for="file_spk">Upload File SPK <span class="text-danger">*</span></label>
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
                            
                            {{-- Button --}}
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

    // all Laravel errors as an object keyed by field name (e.g. 'history_image.0')
    const laravelErrors = @json($errors->getMessages());

  function generateAcForms() {
    const jumlah = parseInt(jumlahAcInput.value) || 0;
    acContainer.innerHTML = '';

    for (let i = 0; i < jumlah; i++) {

        const acCard = document.createElement('div');
        acCard.className = 'card mb-4 border';

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

    const errHistory = (laravelErrors && laravelErrors[`history_image.${i}`]
        ? laravelErrors[`history_image.${i}`][0]
        : (laravelErrors && laravelErrors['history_image'] ? laravelErrors['history_image'][0] : ''));

    const errBeforeIndoor = laravelErrors && laravelErrors[`before_indoor.${i}`]
        ? laravelErrors[`before_indoor.${i}`][0]
        : '';

    const errBeforeOutdoor = laravelErrors && laravelErrors[`before_outdoor.${i}`]
        ? laravelErrors[`before_outdoor.${i}`][0]
        : '';

    const errAfterIndoor = laravelErrors && laravelErrors[`after_indoor.${i}`]
        ? laravelErrors[`after_indoor.${i}`][0]
        : '';
        
    const errAfterOutdoor = laravelErrors && laravelErrors[`after_outdoor.${i}`]
        ? laravelErrors[`after_outdoor.${i}`][0]
        : '';



      acCard.innerHTML = `
        <div class="card-body">
          <h6 class="card-title mb-3">AC #${i + 1}</h6>

          <!-- Nomor AC -->
          <div class="mb-3">
            <label class="form-label">Nomor AC <span class="text-danger">*</span></label>
            <select name="acdetail_ids[]" class="form-select select-no ${errAc ? 'is-invalid' : ''}">
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

          <hr>

            <!-- KARTU HISTORY -->
            <div class="mb-3">
                <label class="form-label">Kartu History AC <span class="text-danger">*</span></label>
                <input type="file"
                        name="history_image[${i}]"
                        class="form-control ${errHistory ? 'is-invalid' : ''}"
                        accept=".jpg,.jpeg,.png">
                        ${errHistory ? `<div class="invalid-feedback d-block">${errHistory}</div>` : ''}
            </div>

            <div class="row g-3">

        <!-- BEFORE -->
        <div class="col-md-6">
            <div class="border rounded p-3 h-100">
                <h6 class="fw-bold text-center mb-3">BEFORE</h6>

                <div class="mb-3">
                    <label class="form-label">Indoor & Outdoor</label>
                    <input type="file"
                    name="images[${i}][before]"
                    class="form-control ${laravelErrors[`images.${i}.before`] ? 'is-invalid' : ''}"
                    accept=".jpg,.jpeg,.png">

                    ${laravelErrors[`images.${i}.before`]
                    ? `<div class="invalid-feedback d-block">${laravelErrors[`images.${i}.before`][0]}</div>`
                    : ''}
                </div>
            </div>
        </div>

        <!-- AFTER -->
        <div class="col-md-6">
            <div class="border rounded p-3 h-100">
                <h6 class="fw-bold text-center mb-3">AFTER</h6>

                <div class="mb-3">
                    <label class="form-label">Indoor & Outdoor</label>
                    <input type="file"
                    name="images[${i}][after]"
                    class="form-control ${laravelErrors[`images.${i}.after`] ? 'is-invalid' : ''}"
                    accept=".jpg,.jpeg,.png">

                    ${laravelErrors[`images.${i}.after`]
                    ? `<div class="invalid-feedback d-block">${laravelErrors[`images.${i}.after`][0]}</div>`
                    : ''}
                </div>
            </div>
        </div>
      `;

      acContainer.appendChild(acCard);
    }
    // initialize select2 for new selects
    initSelect2();    
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

function initSelect2() {
  $('.select-no').select2({
    placeholder: '-- Pilih No AC --',
    allowClear: true,
    width: '100%'
  });
}

// Inisialisasi Select2 untuk Searching Kepada
    $('.select2').select2({
        placeholder: "-- Pilih Kepada --",
        allowClear: true,
        width: '100%' // pastikan full width
    });
</script>
@endpush