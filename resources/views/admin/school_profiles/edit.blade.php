@extends('layouts.app')

@section('title', 'Edit Profil Sekolah')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Profil Sekolah</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.school-profiles.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.school-profiles.update', $school->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Informasi Dasar -->
                            <div class="col-md-6">
                                <h4>Informasi Dasar</h4>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Sekolah <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $school->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required>{{ old('address', $school->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Telepon <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $school->phone) }}" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $school->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="headmaster_name" class="form-label">Kepala Sekolah <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('headmaster_name') is-invalid @enderror" id="headmaster_name" name="headmaster_name" value="{{ old('headmaster_name', $school->headmaster_name) }}" required>
                                    @error('headmaster_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="vision" class="form-label">Visi <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('vision') is-invalid @enderror" id="vision" name="vision" rows="3" required>{{ old('vision', $school->vision) }}</textarea>
                                    @error('vision')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="mission" class="form-label">Misi <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('mission') is-invalid @enderror" id="mission" name="mission" rows="4" required>{{ old('mission', $school->mission) }}</textarea>
                                    @error('mission')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="logo_path" class="form-label">Logo Sekolah</label>
                                    <input type="file" class="form-control @error('logo_path') is-invalid @enderror" id="logo_path" name="logo_path" accept="image/*">
                                    @error('logo_path')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($school->logo_path)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $school->logo_path) }}" alt="Logo" class="img-thumbnail" style="max-width: 100px;">
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="map_embed" class="form-label">Embed Peta</label>
                                    <textarea class="form-control @error('map_embed') is-invalid @enderror" id="map_embed" name="map_embed" rows="3" placeholder="Masukkan kode embed Google Maps">{{ old('map_embed', $school->map_embed) }}</textarea>
                                    @error('map_embed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Konten Halaman About -->
                            <div class="col-md-6">
                                <h4>Konten Halaman About</h4>

                                <div class="mb-3">
                                    <label for="hero_title" class="form-label">Hero Title</label>
                                    <input type="text" class="form-control @error('hero_title') is-invalid @enderror" id="hero_title" name="hero_title" value="{{ old('hero_title', $school->hero_title) }}" placeholder="Contoh: Tentang SMAN 1 Donggo">
                                    @error('hero_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="hero_description" class="form-label">Hero Description</label>
                                    <textarea class="form-control @error('hero_description') is-invalid @enderror" id="hero_description" name="hero_description" rows="2" placeholder="Deskripsi singkat untuk hero section">{{ old('hero_description', $school->hero_description) }}</textarea>
                                    @error('hero_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="school_description" class="form-label">Deskripsi Sekolah</label>
                                    <textarea class="form-control @error('school_description') is-invalid @enderror" id="school_description" name="school_description" rows="4" placeholder="Deskripsi lengkap tentang sekolah">{{ old('school_description', $school->school_description) }}</textarea>
                                    @error('school_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Features Section -->
                                <div class="mb-3">
                                    <label class="form-label">Fitur Keunggulan</label>
                                    <div id="features-container">
                                        @php
                                            $features = old('features', $school->features ?? []);
                                        @endphp
                                        @if(is_array($features) && count($features) > 0)
                                            @foreach($features as $index => $feature)
                                                <div class="feature-item border rounded p-3 mb-3">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control" name="features[{{ $index }}][icon]" value="{{ $feature['icon'] ?? '' }}" placeholder="fa-icon">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <select class="form-control" name="features[{{ $index }}][color]">
                                                                <option value="primary" {{ ($feature['color'] ?? '') == 'primary' ? 'selected' : '' }}>Primary</option>
                                                                <option value="success" {{ ($feature['color'] ?? '') == 'success' ? 'selected' : '' }}>Success</option>
                                                                <option value="info" {{ ($feature['color'] ?? '') == 'info' ? 'selected' : '' }}>Info</option>
                                                                <option value="warning" {{ ($feature['color'] ?? '') == 'warning' ? 'selected' : '' }}>Warning</option>
                                                                <option value="danger" {{ ($feature['color'] ?? '') == 'danger' ? 'selected' : '' }}>Danger</option>
                                                                <option value="secondary" {{ ($feature['color'] ?? '') == 'secondary' ? 'selected' : '' }}>Secondary</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="text" class="form-control" name="features[{{ $index }}][title]" value="{{ $feature['title'] ?? '' }}" placeholder="Judul">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-danger btn-sm remove-feature">Hapus</button>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <textarea class="form-control" name="features[{{ $index }}][desc]" rows="2" placeholder="Deskripsi">{{ $feature['desc'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-feature">Tambah Fitur</button>
                                </div>

                                <!-- Statistics Section -->
                                <div class="mb-3">
                                    <label class="form-label">Statistik Sekolah</label>
                                    <div id="statistics-container">
                                        @php
                                            $statistics = old('statistics', $school->statistics ?? []);
                                        @endphp
                                        @if(is_array($statistics) && count($statistics) > 0)
                                            @foreach($statistics as $index => $stat)
                                                <div class="statistic-item border rounded p-3 mb-3">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" name="statistics[{{ $index }}][label]" value="{{ $stat['label'] ?? '' }}" placeholder="Label">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" name="statistics[{{ $index }}][value]" value="{{ $stat['value'] ?? '' }}" placeholder="Nilai">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-danger btn-sm remove-statistic">Hapus</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-statistic">Tambah Statistik</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.school-profiles.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let featureIndex = {{ is_array($school->features) ? count($school->features) : 0 }};
    let statisticIndex = {{ is_array($school->statistics) ? count($school->statistics) : 0 }};

    // Add Feature
    document.getElementById('add-feature').addEventListener('click', function() {
        const container = document.getElementById('features-container');
        const featureHtml = `
            <div class="feature-item border rounded p-3 mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="features[${featureIndex}][icon]" placeholder="fa-icon">
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="features[${featureIndex}][color]">
                            <option value="primary">Primary</option>
                            <option value="success">Success</option>
                            <option value="info">Info</option>
                            <option value="warning">Warning</option>
                            <option value="danger">Danger</option>
                            <option value="secondary">Secondary</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="features[${featureIndex}][title]" placeholder="Judul">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-feature">Hapus</button>
                    </div>
                </div>
                <div class="mt-2">
                    <textarea class="form-control" name="features[${featureIndex}][desc]" rows="2" placeholder="Deskripsi"></textarea>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', featureHtml);
        featureIndex++;
    });

    // Add Statistic
    document.getElementById('add-statistic').addEventListener('click', function() {
        const container = document.getElementById('statistics-container');
        const statisticHtml = `
            <div class="statistic-item border rounded p-3 mb-3">
                <div class="row">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="statistics[${statisticIndex}][label]" placeholder="Label">
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="statistics[${statisticIndex}][value]" placeholder="Nilai">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-statistic">Hapus</button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', statisticHtml);
        statisticIndex++;
    });

    // Remove Feature
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-feature')) {
            e.target.closest('.feature-item').remove();
        }
    });

    // Remove Statistic
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-statistic')) {
            e.target.closest('.statistic-item').remove();
        }
    });
});
</script>
@endpush
