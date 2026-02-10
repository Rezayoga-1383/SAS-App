@extends('admin.template.main')

@section('title', 'Edit Merk AC - SAS')

@section('content')
<main class="content">
	<div class="page-content mt-n4">
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-center mb-4" id="top-content">
							<h4 class="card-title mb-3">Edit Merk AC</h4>
						</div>

                        <form action="{{ route('ruangan.update', $ruangan->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')
            
                            <!-- Pilih Departemen -->
                            <div class="mb-3">
                                <label for="id_departement" class="form-label">Departement <span class="text-danger">*</span> </label>
                                <select name="id_departement" class="form-select form-select-lg select2 @error('id_departement') is-invalid @enderror">
                                    @foreach($departements as $d)
                                        <option value="{{ $d->id }}" 
                                            {{ old('id_departement', $ruangan->id_departement) == $d->id ? 'selected' : '' }}>
                                            {{ $d->nama_departement }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_departement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nama Ruangan -->
                            <div class="mb-3">
                                <label for="nama_ruangan" class="form-label">Nama Ruangan <span class="text-danger">*</span> </label>
                                <input type="text" name="nama_ruangan" value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}" 
                                    class="form-control @error('nama_ruangan') is-invalid @enderror">
                                @error('nama_ruangan')
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('.select2').select2({
        placeholder: '-- Pilih Departement --',
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: 0
    });
});
</script>
@endpush