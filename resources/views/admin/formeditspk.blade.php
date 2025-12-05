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
                                <label for="id_acdetail" class="form-label">Nomor AC</label>
                                <select 
                                    name="id_acdetail" 
                                    id="id_acdetail" 
                                    class="form-select form-select-lg @error('id_acdetail') is-invalid @enderror"
                                    required>

                                    <option value="">-- Pilih Nomor AC --</option>
                                    @foreach ($acdetail as $ac)
                                        <option value="{{ $ac->id }}"
                                            {{ old('id_acdetail', $spk->id_acdetail) == $ac->id ? 'selected' : '' }}>
                                            {{ $ac->no_ac }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("id_acdetail")
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
                                        $selectedTeknisi = old('teknisi', $spk->teknisi->pluck('id')->toArray());
                                    @endphp

                                    @foreach ($pengguna as $user )
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

                            <div class="mb-3">
                                <label for="keluhan" class="form-label">Keluhan</label>
                                <textarea
                                    id="keluhan"
                                    name="keluhan"
                                    required
                                    class="form-control form-control-md @error('keluhan') is-invalid @enderror"
                                    placeholder="Masukkan Keluhan">{{ old('keluhan', $spk->keluhan) }}</textarea>
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
                                    placeholder="Masukkan jenis pekerjaan">{{ old('jenis_pekerjaan', $spk->jenis_pekerjaan) }}</textarea>
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
                                    value="{{ old('kepada', $spk->kepada) }}">
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
                                <input
                                    type="text"
                                    id="hormat_kami"
                                    name="hormat_kami"
                                    required
                                    class="form-control form-control-md @error('hormat_kami') is-invalid @enderror"
                                    placeholder="Hormat Kami"
                                    value="{{ old('hormat_kami', $spk->hormat_kami) }}">
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
@endpush