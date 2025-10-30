@extends('layouts.app')

@section('title', 'Edit Orang Tua')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Data Orang Tua</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.parents.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.parents.update', $parent) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $parent->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $parent->user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Nomor Telepon</label>
                                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $parent->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="relation_to_student">Hubungan dengan Siswa</label>
                                    <select name="relation_to_student" id="relation_to_student" class="form-control @error('relation_to_student') is-invalid @enderror">
                                        <option value="Ayah" {{ old('relation_to_student', $parent->relation_to_student) == 'Ayah' ? 'selected' : '' }}>Ayah</option>
                                        <option value="Ibu" {{ old('relation_to_student', $parent->relation_to_student) == 'Ibu' ? 'selected' : '' }}>Ibu</option>
                                        <option value="Wali" {{ old('relation_to_student', $parent->relation_to_student) == 'Wali' ? 'selected' : '' }}>Wali</option>
                                        <option value="Ayah/Ibu" {{ old('relation_to_student', $parent->relation_to_student) == 'Ayah/Ibu' ? 'selected' : '' }}>Ayah/Ibu</option>
                                    </select>
                                    @error('relation_to_student')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active">Status Akun</label>
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                                               value="1" {{ old('is_active', $parent->user->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.parents.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
