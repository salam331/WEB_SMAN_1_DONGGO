@extends('layouts.app')

@section('title', 'Edit Profil Sekolah')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div
                        class="card-header bg-primary text-white py-3 rounded-top-4 d-flex align-items-center justify-content-between">
                        <h3 class="mb-0 fw-semibold">
                            <i class="fas fa-edit me-2"></i> Edit Profil Sekolah
                        </h3>
                        <a href="{{ route('admin.school-profiles.index') }}"
                            class="btn btn-light btn-sm fw-semibold shadow-sm text-primary rounded-pill">
                            <i class="fas fa-arrow-left me-1 text-primary"></i> Kembali
                        </a>
                    </div>

                    <form action="{{ route('admin.school-profiles.update', $school->id) }}" method="POST"
                        enctype="multipart/form-data" class="p-4 bg-light rounded-bottom-4">
                        @csrf
                        @method('PUT')

                        {{-- Informasi Dasar --}}
                        <div class="card border-0 shadow-sm mb-4 rounded-4">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h5 class="mb-0 fw-semibold"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
                            </div>
                            <div class="card-body bg-white p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold">Nama Sekolah <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $school->name) }}"
                                            placeholder="Masukkan nama sekolah" required>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="headmaster_name" class="form-label fw-semibold">Kepala Sekolah <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('headmaster_name') is-invalid @enderror"
                                            id="headmaster_name" name="headmaster_name"
                                            value="{{ old('headmaster_name', $school->headmaster_name) }}"
                                            placeholder="Nama kepala sekolah" required>
                                        @error('headmaster_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="address" class="form-label fw-semibold">Alamat <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address"
                                            name="address" rows="3" placeholder="Masukkan alamat lengkap sekolah"
                                            required>{{ old('address', $school->address) }}</textarea>
                                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label fw-semibold">Telepon <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $school->phone) }}"
                                            placeholder="Contoh: 08123456789" required>
                                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-semibold">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $school->email) }}"
                                            placeholder="contoh@email.com" required>
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="accreditation" class="form-label fw-semibold">Akreditasi <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('accreditation') is-invalid @enderror"
                                            id="accreditation" name="accreditation"
                                            value="{{ old('accreditation', $school->accreditation) }}"
                                            placeholder="Contoh: A" required>
                                        @error('accreditation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="total_achievements" class="form-label fw-semibold">Total Prestasi <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('total_achievements') is-invalid @enderror"
                                            id="total_achievements" name="total_achievements"
                                            value="{{ old('total_achievements', $school->total_achievements) }}"
                                            placeholder="Jumlah prestasi" required>
                                        @error('total_achievements')<div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="logo_path" class="form-label fw-semibold">Logo Sekolah</label>
                                        <input type="file" class="form-control @error('logo_path') is-invalid @enderror"
                                            id="logo_path" name="logo_path" accept="image/*">
                                        @error('logo_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        @if($school->logo_path)
                                            <img src="{{ asset('storage/' . $school->logo_path) }}" alt="Logo Sekolah"
                                                class="img-thumbnail mt-2 shadow-sm" style="max-width: 100px;">
                                        @endif
                                    </div>

                                    <div class="col-md-12">
                                        <label for="map_embed" class="form-label fw-semibold">Embed Peta</label>
                                        <textarea class="form-control @error('map_embed') is-invalid @enderror"
                                            id="map_embed" name="map_embed" rows="3"
                                            placeholder="Masukkan kode embed Google Maps">{{ old('map_embed', $school->map_embed) }}</textarea>
                                        @error('map_embed')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="vision" class="form-label fw-semibold">Visi <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control @error('vision') is-invalid @enderror" id="vision"
                                            name="vision" rows="3" required>{{ old('vision', $school->vision) }}</textarea>
                                        @error('vision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="mission" class="form-label fw-semibold">Misi <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control @error('mission') is-invalid @enderror" id="mission"
                                            name="mission" rows="3"
                                            required>{{ old('mission', $school->mission) }}</textarea>
                                        @error('mission')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Konten Halaman About --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-primary text-white rounded-top-4">
                                <h5 class="mb-0 fw-semibold"><i class="fas fa-file-alt me-2"></i>Konten Halaman About</h5>
                            </div>
                            <div class="card-body bg-white p-4">
                                <div class="mb-3">
                                    <label for="hero_title" class="form-label fw-semibold">Hero Title</label>
                                    <input type="text" class="form-control @error('hero_title') is-invalid @enderror"
                                        id="hero_title" name="hero_title"
                                        value="{{ old('hero_title', $school->hero_title) }}"
                                        placeholder="Contoh: Tentang SMAN 1 Donggo">
                                    @error('hero_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="hero_description" class="form-label fw-semibold">Hero Description</label>
                                    <textarea class="form-control @error('hero_description') is-invalid @enderror"
                                        id="hero_description" name="hero_description" rows="2"
                                        placeholder="Deskripsi singkat hero section">{{ old('hero_description', $school->hero_description) }}</textarea>
                                    @error('hero_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="school_description" class="form-label fw-semibold">Deskripsi Sekolah</label>
                                    <textarea class="form-control @error('school_description') is-invalid @enderror"
                                        id="school_description" name="school_description" rows="4"
                                        placeholder="Deskripsi lengkap tentang sekolah">{{ old('school_description', $school->school_description) }}</textarea>
                                    @error('school_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                {{-- Features --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Fitur Keunggulan</label>
                                    <div id="features-container">
                                        @php $features = old('features', $school->features ?? []); @endphp
                                        @if(is_array($features) && count($features))
                                            @foreach($features as $index => $feature)
                                                <div class="feature-item border rounded p-3 mb-3 shadow-sm bg-light">
                                                    <div class="row g-2 align-items-center">
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control"
                                                                name="features[{{ $index }}][icon]"
                                                                value="{{ $feature['icon'] ?? '' }}" placeholder="fa-icon">
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
                                                            <input type="text" class="form-control"
                                                                name="features[{{ $index }}][title]"
                                                                value="{{ $feature['title'] ?? '' }}" placeholder="Judul">
                                                        </div>
                                                        <div class="col-md-2 text-end">
                                                            <button type="button" class="btn btn-danger btn-sm remove-feature"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </div>
                                                    </div>
                                                    <textarea class="form-control mt-2" name="features[{{ $index }}][desc]" rows="2"
                                                        placeholder="Deskripsi">{{ $feature['desc'] ?? '' }}</textarea>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-feature"><i
                                            class="fas fa-plus-circle me-1"></i>Tambah Fitur</button>
                                </div>

                                {{-- Statistik --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Statistik Sekolah</label>
                                    <div id="statistics-container">
                                        @php $statistics = old('statistics', $school->statistics ?? []); @endphp
                                        @if(is_array($statistics) && count($statistics))
                                            @foreach($statistics as $index => $stat)
                                                <div class="statistic-item border rounded p-3 mb-3 shadow-sm bg-light">
                                                    <div class="row g-2 align-items-center">
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"
                                                                name="statistics[{{ $index }}][label]"
                                                                value="{{ $stat['label'] ?? '' }}" placeholder="Label">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control"
                                                                name="statistics[{{ $index }}][value]"
                                                                value="{{ $stat['value'] ?? '' }}" placeholder="Nilai">
                                                        </div>
                                                        <div class="col-md-2 text-end">
                                                            <button type="button" class="btn btn-danger btn-sm remove-statistic"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-statistic"><i
                                            class="fas fa-plus-circle me-1"></i>Tambah Statistik</button>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.school-profiles.index') }}" class="btn btn-secondary px-4 shadow-sm">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 me-2 shadow-sm">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let featureIndex = {{ is_array($school->features) ? count($school->features) : 0 }};
            let statisticIndex = {{ is_array($school->statistics) ? count($school->statistics) : 0 }};

            document.getElementById('add-feature').addEventListener('click', function () {
                const container = document.getElementById('features-container');
                const html = `
                <div class="feature-item border rounded p-3 mb-3 shadow-sm bg-light">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-3"><input type="text" class="form-control" name="features[${featureIndex}][icon]" placeholder="fa-icon"></div>
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
                        <div class="col-md-4"><input type="text" class="form-control" name="features[${featureIndex}][title]" placeholder="Judul"></div>
                        <div class="col-md-2 text-end"><button type="button" class="btn btn-danger btn-sm remove-feature"><i class="fas fa-trash"></i></button></div>
                    </div>
                    <textarea class="form-control mt-2" name="features[${featureIndex}][desc]" rows="2" placeholder="Deskripsi"></textarea>
                </div>`;
                container.insertAdjacentHTML('beforeend', html);
                featureIndex++;
            });

            document.getElementById('add-statistic').addEventListener('click', function () {
                const container = document.getElementById('statistics-container');
                const html = `
                <div class="statistic-item border rounded p-3 mb-3 shadow-sm bg-light">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-5"><input type="text" class="form-control" name="statistics[${statisticIndex}][label]" placeholder="Label"></div>
                        <div class="col-md-5"><input type="text" class="form-control" name="statistics[${statisticIndex}][value]" placeholder="Nilai"></div>
                        <div class="col-md-2 text-end"><button type="button" class="btn btn-danger btn-sm remove-statistic"><i class="fas fa-trash"></i></button></div>
                    </div>
                </div>`;
                container.insertAdjacentHTML('beforeend', html);
                statisticIndex++;
            });

            document.addEventListener('click', function (e) {
                if (e.target.closest('.remove-feature')) e.target.closest('.feature-item').remove();
                if (e.target.closest('.remove-statistic')) e.target.closest('.statistic-item').remove();
            });
        });
    </script>
@endpush