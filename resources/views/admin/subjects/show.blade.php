@extends('layouts.app')

@section('page_title', 'Detail Mata Pelajaran')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-book me-2"></i> Detail Mata Pelajaran
                </h5>
                <div>
                    <a href="{{ route('admin.subjects.index') }}"
                        class="btn btn-light btn-sm text-primary fw-semibold rounded-pill shadow-sm me-2">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <a href="{{ route('admin.subjects.edit', $subject) }}"
                        class="btn btn-warning btn-sm text-dark fw-semibold rounded-pill shadow-sm me-2">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill shadow-sm fw-semibold"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')">
                            <i class="fas fa-trash-alt me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-body bg-light bg-gradient p-4">
                <!-- Informasi Mata Pelajaran -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i> Informasi Mata Pelajaran
                            </h6>
                            <div class="mb-2"><strong>Kode:</strong> {{ $subject->code }}</div>
                            <div class="mb-2"><strong>Nama:</strong> {{ $subject->name }}</div>
                            <div class="mb-2"><strong>KKM:</strong> {{ $subject->kkm }}</div>
                            <div class="mb-2"><strong>Guru Utama:</strong> {{ $subject->teacher->user->name ?? 'Belum ditentukan' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-chart-bar me-2"></i> Statistik
                            </h6>
                            <div class="mb-2"><strong>Jumlah Guru:</strong> {{ $subject->subjectTeachers->count() }}</div>
                            <div class="mb-2"><strong>Jumlah Jadwal:</strong> {{ $subject->schedules->count() }}</div>
                            <div class="mb-2"><strong>Jumlah Materi:</strong> {{ $subject->materials->count() }}</div>
                            <div><strong>Jumlah Ujian:</strong> {{ $subject->exams->count() }}</div>
                        </div>
                    </div>
                </div>

                <!-- Guru Pengajar -->
                <div class="mb-5">
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="fas fa-chalkboard-teacher me-2"></i> Guru Pengajar
                    </h6>

                    @if($subject->subjectTeachers->count() > 0)
                        <div class="table-responsive shadow-sm rounded-4 overflow-hidden border border-light-subtle">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="text-white text-center"
                                    style="background: linear-gradient(135deg, #0d6efd, #007bff);">
                                    <tr>
                                        <th class="py-3">No</th>
                                        <th class="py-3">Guru</th>
                                        <th class="py-3">Kelas</th>
                                        <th class="py-3">NIP</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white text-center">
                                    @foreach($subject->subjectTeachers as $subjectTeacher)
                                        <tr class="border-bottom">
                                            <td class="fw-semibold text-secondary">{{ $loop->iteration }}</td>
                                            <td class="fw-semibold text-dark">
                                                <i class="fas fa-user-tie me-1 text-success"></i>
                                                {{ $subjectTeacher->teacher->user->name }}
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border shadow-sm px-3 py-2 rounded-pill">
                                                    <i class="fas fa-door-open me-1 text-secondary"></i>
                                                    {{ $subjectTeacher->classRoom->name ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-muted">
                                                <i class="fas fa-id-card me-1 text-primary"></i>
                                                {{ $subjectTeacher->teacher->nip ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-light border shadow-sm d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary me-2 fs-5"></i>
                            <span class="text-muted fst-italic">Belum ada guru yang mengajar mata pelajaran ini.</span>
                        </div>
                    @endif
                </div>

                <style>
                    .table-hover tbody tr:hover {
                        background-color: #f8faff;
                        transition: background-color 0.25s ease;
                    }
                </style>


                <!-- Jadwal Pelajaran -->
                <div class="mb-5">
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="fas fa-calendar-alt me-2"></i> Jadwal Pelajaran
                    </h6>

                    @if($subject->schedules->count() > 0)
                        <div class="table-responsive shadow-sm rounded-4 overflow-hidden border border-light-subtle">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="text-white" style="background: linear-gradient(135deg, #0d6efd, #007bff);">
                                    <tr class="text-center">
                                        <th class="py-3">No</th>
                                        <th class="py-3">Hari</th>
                                        <th class="py-3">Waktu</th>
                                        <th class="py-3">Kelas</th>
                                        <th class="py-3">Guru</th>
                                        <th class="py-3">Ruangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach($subject->schedules as $schedule)
                                        <tr class="text-center border-bottom">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="badge bg-info text-dark px-3 py-2 rounded-pill shadow-sm">
                                                    <i class="fas fa-calendar-day me-1"></i>
                                                    {{ ucfirst($schedule->day) }}
                                                </span>
                                            </td>
                                            <td class="fw-semibold text-secondary">
                                                <i class="far fa-clock me-1 text-primary"></i>
                                                {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border shadow-sm">
                                                    <i class="fas fa-door-open me-1 text-secondary"></i>
                                                    {{ $schedule->classRoom?->name ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="fw-semibold text-dark">
                                                <i class="fas fa-user-tie me-1 text-success"></i>
                                                {{ $schedule->teacher?->user?->name ?? '-' }}
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                                    {{ $schedule->room ?? '-' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-light border shadow-sm d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary me-2 fs-5"></i>
                            <span class="text-muted fst-italic">Belum ada jadwal untuk mata pelajaran ini.</span>
                        </div>
                    @endif
                </div>


                <!-- Materi Pembelajaran -->
                <div class="mb-5">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-primary mb-0">
                            <i class="fas fa-book-reader me-2"></i> Materi Pembelajaran ({{ $subject->materials->count() }})
                        </h6>
                        <a href="{{ route('admin.materials.create') }}"
                            class="btn btn-sm btn-primary rounded-pill shadow-sm px-3 py-1 fw-semibold">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </a>
                    </div>

                    @if($subject->materials->count() > 0)
                        <div class="table-responsive shadow-sm rounded-4 overflow-hidden border border-light-subtle">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="text-white" style="background: linear-gradient(135deg, #0d6efd, #007bff);">
                                    <tr class="text-center">
                                        <th class="py-3">No</th>
                                        <th class="py-3 text-start">Judul</th>
                                        <th class="py-3">Kelas</th>
                                        <th class="py-3">Guru</th>
                                        <th class="py-3">Status</th>
                                        <th class="py-3">File</th>
                                        <th class="py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach($subject->materials as $material)
                                        <tr class="border-bottom">
                                            <td class="text-center fw-semibold text-secondary">{{ $loop->iteration }}</td>
                                            <td class="text-start">
                                                <i class="fas fa-file-alt me-2 text-primary"></i>
                                                <strong>{{ Str::limit($material->title, 30) }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($material->description, 50) }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark px-3 py-2 rounded-pill shadow-sm">
                                                    {{ $material->classRoom->name ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center fw-semibold text-dark">
                                                <i class="fas fa-user-tie me-1 text-success"></i>
                                                {{ $material->teacher->user->name ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                @if($material->is_published)
                                                    <span class="badge bg-success text-white px-2 py-1 rounded-pill">
                                                        <i class="fas fa-check me-1"></i> Aktif
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">
                                                        <i class="fas fa-lock me-1"></i> Draft
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($material->file_path)
                                                    <span class="badge bg-success text-white px-2 py-1 rounded-pill">
                                                        <i class="fas fa-check me-1"></i>
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary text-white px-2 py-1 rounded-pill">
                                                        <i class="fas fa-times me-1"></i>
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('admin.materials.show', $material) }}"
                                                        class="btn btn-sm btn-outline-info rounded-pill shadow-sm px-2 py-1"
                                                        title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.materials.edit', $material) }}"
                                                        class="btn btn-sm btn-outline-primary rounded-pill shadow-sm px-2 py-1"
                                                        title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill shadow-sm px-2 py-1"
                                                            title="Hapus">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-light border shadow-sm d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary me-2 fs-5"></i>
                            <span class="text-muted fst-italic">Belum ada materi untuk mata pelajaran ini.
                                <a href="{{ route('admin.materials.create') }}" class="text-primary fw-semibold">Tambah sekarang</a>
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Ujian -->
                <div>
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="fas fa-file-alt me-2"></i> Ujian
                    </h6>

                    {{-- Pastikan $subject->exams bukan null dan memiliki count > 0 --}}
                    @if(isset($subject->exams) && $subject->exams->count() > 0)
                        <div class="table-responsive shadow-sm rounded-4 overflow-hidden border border-light-subtle">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="text-white text-center"
                                    style="background: linear-gradient(135deg, #0d6efd, #007bff);">
                                    <tr>
                                        <th class="py-3">No</th>
                                        <th class="py-3">Judul</th>
                                        <th class="py-3">Tanggal</th>
                                        <th class="py-3">Durasi</th>
                                        <th class="py-3">Guru</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white text-center">
                                    @foreach($subject->exams as $exam)
                                        <tr class="border-bottom">
                                            <td class="fw-semibold text-secondary">{{ $loop->iteration }}</td>
                                            <td class="fw-semibold text-dark">
                                                <i class="fas fa-file-signature me-1 text-primary"></i>
                                                {{ $exam->title }}
                                            </td>
                                            <td class="text-muted">
                                                @if($exam->exam_date)
                                                    <i class="far fa-calendar-alt me-1 text-info"></i>
                                                    {{ \Carbon\Carbon::parse($exam->exam_date)->format('d/m/Y') }}
                                                @else
                                                    <span class="text-muted fst-italic">Belum dijadwalkan</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border shadow-sm px-3 py-2 rounded-pill">
                                                    <i class="fas fa-stopwatch me-1 text-secondary"></i>
                                                    {{ $exam->duration ? $exam->duration . ' menit' : '-' }}
                                                </span>
                                            </td>
                                            <td class="fw-semibold text-dark">
                                                <i class="fas fa-user-tie me-1 text-success"></i>
                                                {{ $exam->teacher?->user?->name ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-light border shadow-sm d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary me-2 fs-5"></i>
                            <span class="text-muted fst-italic">Belum ada ujian untuk mata pelajaran ini.</span>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <style>
        .card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .btn {
            transition: all 0.2s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .table th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection
