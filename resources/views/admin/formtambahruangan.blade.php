@extends('admin.template.main')

@section('title', 'Tambah Ruangan - SAS')

@section('content')
<main class="content">
	<div class="page-content mt-n4">
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-center mb-4" id="top-content">
							<h4 class="card-title mb-3">Tambah Ruangan</h4>
						</div>
						@if(session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger mb-3" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('ruangan.store') }}" method="POST" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label for="id_departement" class="form-label">Departement</label>
                                <select
                                    id="id_departement"
                                    name="id_departement"
                                    class="form-select form-select-lg @error('id_departemen') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Departement --</option>
                                    @foreach ($departement as $item)
                                        <option value="{{ $item->id }}" {{ old('id_departement') == $item->id ? 'selected' : '' }}> {{ $item->nama_departement }}</option>
                                    @endforeach
                                </select>
                                @error('id_departemen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nama_ruangan" class="form-label">Ruangan</label>
                                <input
                                    type="text"
                                    id="nama_ruangan"
                                    name="nama_ruangan"
                                    required
                                    class="form-control form-control-lg @error('nama_ruangan') is-invalid @enderror"
                                    placeholder="Masukkan Nama Ruangan" value = "{{ old('nama_ruangan') }}">
                                @error('nama_departement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('ruangan') }}" class="btn btn-outline-secondary">Batal</a>
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