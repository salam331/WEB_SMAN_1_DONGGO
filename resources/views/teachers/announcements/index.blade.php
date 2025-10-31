@extends('layouts.app')

@section('title', 'Pengumuman - SMAN 1 DONGGO')

@section('content')
    <div class="container-fluid animate__animated animate__fadeIn">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center"
                        style="background: linear-gradient(135deg, #4e73df, #1cc88a); color: white;">
                        <h5 class="card-title mb-0 d-flex align-items-center">
                            <i class="fas fa-bullhorn me-2"></i>
                            Pengumuman
                        </h5>
                        <a href="{{ route('teachers.announcements.create') }}"
                            class="btn btn-light btn-sm shadow-sm fw-semibold">
                            <i class="fas fa-plus me-1"></i> Buat Pengumuman
                        </a>
                    </div>

                    <div class="card-body bg-light">
                        @if($announcements->count() > 0)
                            <div class="row g-4">
                                @foreach($announcements as $announcement)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card border-0 shadow-sm h-100 announcement-card position-relative overflow-hidden"
                                            style="transition: all 0.3s ease;">
                                            <div class="card-header bg-white border-0 pb-0">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="fw-bold text-dark mb-1">{{ $announcement->title }}</h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            {{ $announcement->created_at->format('d F Y H:i') }}
                                                        </small>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light border-0" type="button"
                                                            data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('teachers.announcements.edit', $announcement->id) }}">
                                                                    <i class="fas fa-edit me-2 text-primary"></i>Edit
                                                                </a>
                                                            </li>
                                                            @if(!$announcement->is_published)
                                                                <li>
                                                                    <a class="dropdown-item" href="#"
                                                                        onclick="publishAnnouncement({{ $announcement->id }})">
                                                                        <i class="fas fa-paper-plane me-2 text-success"></i>Publish
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-danger" href="#"
                                                                    onclick="confirmDelete({{ $announcement->id }})">
                                                                    <i class="fas fa-trash me-2"></i>Hapus
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <p class="text-muted small mb-3">
                                                    {!! Str::limit(strip_tags($announcement->content), 150) !!}
                                                </p>

                                                <div class="d-flex flex-wrap align-items-center gap-2">
                                                    @if($announcement->is_published)
                                                        <span class="badge bg-success"><i
                                                                class="fas fa-check-circle me-1"></i>Published</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark"><i
                                                                class="fas fa-hourglass-half me-1"></i>Draft</span>
                                                    @endif

                                                    @if($announcement->target_audience)
                                                        <span class="badge bg-info text-white">
                                                            <i class="fas fa-users me-1"></i>{{ $announcement->target_audience }}
                                                        </span>
                                                    @endif
                                                </div>

                                                @if($announcement->attachment)
                                                    <div class="mt-3">
                                                        <a href="{{ asset('storage/' . $announcement->attachment) }}" target="_blank"
                                                            class="btn btn-outline-primary btn-sm w-100">
                                                            <i class="fas fa-paperclip me-1"></i> Lihat Lampiran
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="card-footer bg-secomndary text-white text-center border-0 text-end">
                                                <a href="{{ route('teachers.announcements.edit', $announcement->id) }}"
                                                    class="text-decoration-none small text-muted">
                                                    <i class="fas fa-edit me-1"></i>Edit Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $announcements->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-bullhorn fa-4x text-muted mb-4"></i>
                                <h5 class="text-muted">Belum Ada Pengumuman</h5>
                                <p class="text-muted">Anda belum membuat pengumuman apapun.</p>
                                <a href="{{ route('teachers.announcements.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Buat Pengumuman Pertama
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus pengumuman ini?</p>
                    <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .announcement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .announcement-card::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 4px;
            top: 0;
            left: 0;
            background: linear-gradient(90deg, #4e73df, #36b9cc, #1cc88a);
        }
    </style>

    <script>
        function confirmDelete(id) {
            document.getElementById('deleteForm').action = `/teachers/announcements/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        function publishAnnouncement(id) {
            if (confirm('Apakah Anda yakin ingin mempublish pengumuman ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/teachers/announcements/${id}/publish`;
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection