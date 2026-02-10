@extends('admin.template.main')

@section('title', 'Edit Pengguna - SAS')

@section('content')
<main class="content">
	<div class="page-content mt-n4">
		<div class="row">
			<div class="col-md-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-center mb-4" id="top-content">
							<h4 class="card-title mb-3">Edit Pengguna</h4>
						</div>

                        <form action="{{ route('pengguna.update', $pengguna->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')
            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span> </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $pengguna->email) }}"
                                    required
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    placeholder="Masukkan Email Pengguna">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama <span class="text-danger">*</span> </label>
                                <input
                                    type="text"
                                    id="nama"
                                    name="nama"
                                    value="{{ old('nama', $pengguna->nama) }}"
                                    required
                                    class="form-control form-control-lg @error('nama') is-invalid @enderror"
                                    placeholder="Masukkan Nama Pengguna">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        required
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        placeholder="Password minimal 8 karakter"
                                        value="{{ old('password', $pengguna->password) }}">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Tampilkan password">
                                        <i data-feather="eye"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span> </label>
                                <div class="input-group">
                                    <select
                                        id="role"
                                        name="role"
                                        required
                                        class="form-control form-control-lg @error('role') is-invalid @enderror">

                                        <option value="">-- Pilih Role --</option>

                                        @foreach ($role as $itemRole)
                                            <option value="{{ $itemRole }}" 
                                                {{ (old('role', $pengguna->role) == $itemRole) ? 'selected' : '' }}>
                                                {{ ucfirst($itemRole) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('role')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('pengguna') }}" class="btn btn-outline-secondary">Batal</a>
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
    // feather icons
    if (window.feather) { feather.replace(); }

    // Toggle password visibility (sederhana, ganti ikon sesuai state)
    const toggleBtn = document.getElementById('togglePassword');
    const pwdInput = document.getElementById('password');
    if (toggleBtn && pwdInput) {
        toggleBtn.addEventListener('click', function () {
            const isPwd = pwdInput.getAttribute('type') === 'password';
            pwdInput.setAttribute('type', isPwd ? 'text' : 'password');

            // ubah ikon
            const icon = this.querySelector('i');
            if (icon) {
                icon.setAttribute('data-feather', isPwd ? 'eye-off' : 'eye');
                if (window.feather) feather.replace();
            }
        });
    }
});
</script>
@endpush