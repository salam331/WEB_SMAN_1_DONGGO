@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')
    <div class="container py-12">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <div class="card border-0 shadow-lg rounded-4">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 px-4 rounded-top-4">
                        <h4 class="mb-0 fw-bold">
                            <i class="fas fa-user-graduate me-2"></i> Detail Siswa
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.students.edit', $student) }}"
                                class="btn btn-warning btn-sm px-3 shadow-sm">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <a href="{{ route('admin.students') }}" class="btn btn-outline-light btn-sm px-3 shadow-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body px-5 py-4 bg-light">
                        <div class="row align-items-start">

                            {{-- Foto Profil Siswa (opsional, default avatar) --}}
                            <div class="col-md-4 text-center mb-4 mb-md-0">
                                <div class="position-relative">
                                    <img src="{{ $student->user->avatar_url ?? asset('images/default-avatar.png') }}"
                                        class="rounded-circle shadow-sm border border-3 border-primary" alt="Foto Siswa"
                                        width="150" height="150">
                                </div>
                                <h5 class="mt-3 fw-bold text-primary">{{ $student->user->name }}</h5>
                                <p class="text-muted mb-0">{{ $student->classRoom->name ?? 'Belum ada kelas' }}</p>
                                <span class="badge {{ $student->user->is_active ? 'bg-success' : 'bg-danger' }} mt-2">
                                    {{ $student->user->is_active ? 'Akun Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>

                            {{-- Detail Informasi --}}
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="text-secondary" width="180"><i
                                                        class="fas fa-envelope me-2 text-primary"></i>Email</th>
                                                <td>{{ $student->user->email }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-secondary"><i
                                                        class="fas fa-id-card me-2 text-primary"></i>NIS</th>
                                                <td>{{ $student->nis }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-secondary"><i
                                                        class="fas fa-barcode me-2 text-primary"></i>NISN</th>
                                                <td>{{ $student->nisn ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-secondary"><i
                                                        class="fas fa-venus-mars me-2 text-primary"></i>Jenis Kelamin</th>
                                                <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-secondary"><i
                                                        class="fas fa-map-marker-alt me-2 text-primary"></i>Tempat Lahir
                                                </th>
                                                <td>{{ $student->birth_place ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-secondary"><i
                                                        class="fas fa-calendar-day me-2 text-primary"></i>Tanggal Lahir</th>
                                                <td>{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d M Y') : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-secondary"><i
                                                        class="fas fa-home me-2 text-primary"></i>Alamat</th>
                                                <td>{{ $student->address ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-secondary"><i
                                                        class="fas fa-user-friends me-2 text-primary"></i>Orang Tua</th>
                                                <td>
                                                    @if($student->parent)
                                                        <a href="{{ route('admin.parents.show', $student->parent) }}"
                                                            class="text-decoration-none text-primary fw-semibold">
                                                            {{ $student->parent->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Belum terdaftar</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-secondary"><i
                                                        class="fas fa-clock me-2 text-primary"></i>Dibuat</th>
                                                <td>{{ $student->created_at->format('d M Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-secondary"><i
                                                        class="fas fa-sync-alt me-2 text-primary"></i>Terakhir Diperbarui
                                                </th>
                                                <td>{{ $student->updated_at->format('d M Y H:i') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div> {{-- End Row --}}
                    </div> {{-- End Card Body --}}
                </div>

            </div>
        </div>
    </div>
@endsection