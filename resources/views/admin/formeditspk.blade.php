@extends('admin.template.main')

@section('title', 'Edit SPK - SAS')

@section('content')
<main class="content">
    <div class="page-content mt-n4">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4" id="top-content">
                            <h4 class="card-title mb-3">Edit SPK</h4>
                        </div>

                        <form action="{{ route('spk.update', $spk->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="no_spk" class="form-label">Nomor SPK</label>
                                <input 
                                type="text"
                                id="no_spk"
                                name="no_spk"
                                required
                                class="form-control form-control-md @error('no_spk') is-invalid @enderror"
                                placeholder="Masukkan Nomor SPK" value="{{ old('no_spk', $spk->no_spk) }}">
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
                                    placeholder="Masukkan tanggal yang sesuai" value="{{ old('tanggal', $spk->tanggal) }}">
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
                                    value="{{ old('waktu_mulai', $spk->waktu_mulai) }}">
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
                                    value="{{ old('waktu_selesai', $spk->waktu_selesai) }}">
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
                                    value="{{ old('jumlah_orang', $spk->jumlah_orang) }}">
                                @error('jumlah_orang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Teknisi</label>
                                <div class="border rounded p-2 @error('teknisi') is-invalid @enderror" style="max-height: 150px; overflow-y:auto">

                                    @php
                                        $selectedTeknisi = old('teknisi', $spk->teknisi ? $spk->teknisi->pluck('id')->toArray() : []);
                                    @endphp

                                    @foreach ($teknisi as $user )
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input teknisi-checkbox" name="teknisi[]" id="teknisi{{ $user->id }}" value="{{ $user->id }}" {{ in_array($user->id, $selectedTeknisi) ? 'checked' : ''}}>
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

                             <!-- Dynamic AC Input Section -->
                            <div class="mb-3">
                                <label for="jumlah_ac_input" class="form-label">Jumlah AC</label>
                                <input type="number" id="jumlah_ac_input" name="jumlah_ac_input" 
                                  class="form-control form-control-md" 
                                  placeholder="Masukkan jumlah AC"
                                  value="{{ old('jumlah_ac_input', $spk->acdetail && $spk->acdetail->count() > 0 ? $spk->acdetail->count() : 1) }}" min="1">
                                <small class="text-muted">Akan menampilkan form input untuk setiap AC</small>
                            </div>

                            <div class="mb-3">
                                <h5 class="mb-3">Detail AC yang Diperbaiki</h5>
                                <div id="ac_container">
                                  <!-- AC items akan di-generate dengan JavaScript -->
                                </div>
                                @error('acdetail_ids')
                                  <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="kepada" class="form-label">Kepada</label>
                                <select name="kepada" id="kepada" required class="form-select form-control-md @error('kepada') is-invalid @enderror">
                                    <option value="">-- Pilih Kepada --</option>
                                    @foreach ($departement as $dept)
                                        <option value="{{ $dept->nama_departement }}" {{ old('kepada', $spk->kepada) == $dept->nama_departement ? 'selected' : '' }}>
                                            {{ $dept->nama_departement }}
                                        </option>
                                    @endforeach
                                </select>
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
                                    value="{{ old('mengetahui', $spk->mengetahui) }}">
                                @error('mengetahui')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="hormat_kami" class="form-label">Hormat Kami</label>
                                <select name="hormat_kami" id="hormat_kami" class="form-select @error('hormat_kami') is-invalid @enderror" required>
                                    <option value="">-- Pilih Admin --</option>
                                    @foreach ($admin as $user )
                                        <option value="{{ $user->id }}" {{ old('hormat_kami', $spk->hormat_kami) == $user->id ? 'selected' : ''}}>
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
                                    @foreach ($pengguna as $user )
                                        <option value="{{ $user->id }}" {{ old('pelaksana_ttd', $spk->pelaksana_ttd) == $user->id ? 'selected' : ''}}>
                                            {{ $user->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pelaksana_ttd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="file_spk">Upload File SPK (Optional)</label>

                                {{-- Tampilkan file lama jika ada --}}
                                @if ($spk->file_spk)
                                    <p class="mb-2">
                                        File saat ini:
                                        <a href="{{ asset('storage/' . $spk->file_spk) }}" target="_blank" class="text-primary">
                                            Lihat File
                                        </a>
                                    </p>
                                @endif

                                <input 
                                    type="file"
                                    id="file_spk"
                                    name="file_spk"
                                    class="form-control form-control-md @error('file_spk') is-invalid @enderror"
                                    accept=".pdf,.jpg,.jpeg,.png">

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
const jumlahAcInput = document.getElementById('jumlah_ac_input');
const acContainer = document.getElementById('ac_container');
const checkboxes = document.querySelectorAll('.teknisi-checkbox');
const pelaksanaSelect = document.getElementById('pelaksana_ttd');

// Data AC master
const acdetailData = {!! json_encode($acdetail ? $acdetail->pluck('no_ac', 'id')->toArray() : []) !!};

// Data existing AC dari SPK (pivot)
const existingAcData = {!! json_encode(
    $spk->acdetail && $spk->acdetail->count() > 0
        ? $spk->acdetail->map(fn($ac) => [
            'id' => $ac->id,
            'no_ac' => $ac->no_ac,
            'keluhan' => $ac->pivot->keluhan ?? '',
            'jenis_pekerjaan' => $ac->pivot->jenis_pekerjaan ?? ''
        ])->values()
        : []
) !!};

// Errors dari validasi
const errorsData = {!! json_encode($errors->messages()) !!};

function getErrorMessage(fieldName, index) {
    const key = `${fieldName}.${index}`;
    return errorsData[key] ? errorsData[key][0] : '';
}

function hasError(fieldName, index) {
    const key = `${fieldName}.${index}`;
    return key in errorsData;
}

function generateAcForms() {
    const jumlah = parseInt(jumlahAcInput.value) || 1;
    acContainer.innerHTML = '';

    const oldIds = {!! json_encode(old('acdetail_ids', [])) !!};
    const oldKeluhan = {!! json_encode(old('keluhan', [])) !!};
    const oldJenis = {!! json_encode(old('jenis_pekerjaan', [])) !!};

    for (let i = 0; i < jumlah; i++) {
        const acCard = document.createElement('div');
        acCard.className = 'card mb-3 border';

        // Ambil old input dulu, jika tidak ada pakai existing SPK
        const acId = oldIds[i] || (existingAcData[i] ? existingAcData[i].id : '');
        const keluhan = oldKeluhan[i] || (existingAcData[i] ? existingAcData[i].keluhan : '');
        const jenisPekerjaan = oldJenis[i] || (existingAcData[i] ? existingAcData[i].jenis_pekerjaan : '');

        const acError = hasError('acdetail_ids', i);
        const keluhanError = hasError('keluhan', i);
        const jenisError = hasError('jenis_pekerjaan', i);

        acCard.innerHTML = `
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0">AC #${i + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-ac-btn">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>
                <div class="mb-3">
                    <label for="acdetail_${i}" class="form-label">Nomor AC <span class="text-danger">*</span></label>
                    <select name="acdetail_ids[]" id="acdetail_${i}" class="form-select ${acError ? 'is-invalid' : ''}" required>
                        <option value="">-- Pilih No AC --</option>
                        ${Object.entries(acdetailData).map(([id, noAc]) => `
                            <option value="${id}" ${acId == id ? 'selected' : ''}>${noAc}</option>
                        `).join('')}
                    </select>
                    ${acError ? `<div class="invalid-feedback d-block">${getErrorMessage('acdetail_ids', i)}</div>` : ''}
                </div>
                <div class="mb-3">
                    <label for="keluhan_${i}" class="form-label">Keluhan <span class="text-danger">*</span></label>
                    <textarea name="keluhan[]" id="keluhan_${i}" class="form-control ${keluhanError ? 'is-invalid' : ''}" rows="3" required>${keluhan}</textarea>
                    ${keluhanError ? `<div class="invalid-feedback d-block">${getErrorMessage('keluhan', i)}</div>` : ''}
                </div>
                <div class="mb-3">
                    <label for="jenis_pekerjaan_${i}" class="form-label">Jenis Pekerjaan <span class="text-danger">*</span></label>
                    <textarea name="jenis_pekerjaan[]" id="jenis_pekerjaan_${i}" class="form-control ${jenisError ? 'is-invalid' : ''}" rows="3" required>${jenisPekerjaan}</textarea>
                    ${jenisError ? `<div class="invalid-feedback d-block">${getErrorMessage('jenis_pekerjaan', i)}</div>` : ''}
                </div>
            </div>
        `;

        acContainer.appendChild(acCard);

        // tombol hapus
        acCard.querySelector('.remove-ac-btn').addEventListener('click', e => {
            e.preventDefault();
            acCard.remove();
        });
    }
}

// Teknisi limit & pelaksana filter
function updateCheckboxLimit() {
    const maxTeknisi = parseInt(jumlahInput.value) || 0;
    const checkedCount = document.querySelectorAll('.teknisi-checkbox:checked').length;

    checkboxes.forEach(cb => cb.disabled = !cb.checked && checkedCount >= maxTeknisi);

    updatePelaksanaOptions();
}

function updatePelaksanaOptions() {
    const selectedIds = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);

    Array.from(pelaksanaSelect.options).forEach(opt => {
        if(opt.value === "") return;
        opt.style.display = selectedIds.includes(opt.value) ? 'block' : 'none';
    });

    if(!selectedIds.includes(pelaksanaSelect.value)) pelaksanaSelect.value = "";
}

// Event listeners
jumlahAcInput.addEventListener('input', generateAcForms);
jumlahInput.addEventListener('input', () => {
    const maxTeknisi = parseInt(jumlahInput.value) || 0;
    const checked = document.querySelectorAll('.teknisi-checkbox:checked');
    if(checked.length > maxTeknisi) checked.forEach((cb, i) => { if(i >= maxTeknisi) cb.checked = false; });
    updateCheckboxLimit();
});
checkboxes.forEach(cb => cb.addEventListener('change', updateCheckboxLimit));

// Init
document.addEventListener('DOMContentLoaded', () => {
    generateAcForms();
    updateCheckboxLimit();
});
</script>
@endpush
