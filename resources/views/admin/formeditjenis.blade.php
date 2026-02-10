@extends('admin.template.main')

@section('title', 'Edit Jenis AC - SAS')

@section('content')
<main class="content">
	<div class="page-content mt-n4">
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-center mb-4" id="top-content">
							<h4 class="card-title mb-3">Edit Jenis AC</h4>
						</div>

                        <form action="{{ route('jenis-ac.update', $jenis->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')
            
                            <div class="mb-3">
                                <label for="nama_jenis" class="form-label">Jenis AC <span class="text-danger">*</span> </label>
                                <input
                                    type="text"
                                    id="nama_jenis"
                                    name="nama_jenis"
                                    value="{{ old('nama_jenis', $jenis->nama_jenis) }}"
                                    required
                                    class="form-control form-control-lg @error('nama_jenis') is-invalid @enderror"
                                    placeholder="Masukkan Jenis AC">
                                @error('nama_jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('jenis-ac') }}" class="btn btn-outline-secondary">Batal</a>
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