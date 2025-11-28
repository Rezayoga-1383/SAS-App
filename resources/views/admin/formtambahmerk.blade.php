@extends('admin.template.main')

@section('title', 'Tambah Merk AC - SAS')

@section('content')
<main class="content">
	<div class="page-content mt-n4">
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-center mb-4" id="top-content">
							<h4 class="card-title mb-3">Tambah Merk AC</h4>
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

                        <form action="{{ route('merk-ac.store') }}" method="POST" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label for="nama_merk" class="form-label">Merk AC</label>
                                <input
                                    type="text"
                                    id="nama_merk"
                                    name="nama_merk"
                                    required
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    placeholder="Masukkan Merk AC">
                                @error('nama_merk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('merk-ac') }}" class="btn btn-outline-secondary">Batal</a>
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