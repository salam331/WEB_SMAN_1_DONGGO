@extends('layouts.app')

@section('title', 'Notifikasi - SMAN 1 DONGGO')

@section('content')
    <style>
        /* Animasi lembut */
        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeSlide {
            animation: fadeSlide 0.6s ease forwards;
        }

        .card-modern {
            background: linear-gradient(145deg, #ffffff, #f8f9fc);
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .card-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: linear-gradient(90deg, #4e73df, #224abe);
            color: #fff !important;
            border-radius: 20px 20px 0 0 !important;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        .btn-outline-secondary,
        .btn-outline-danger {
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #e2e6ea;
            color: #4e73df;
        }

        .btn-outline-danger:hover {
            background-color: #f8d7da;
            color: #c82333;
        }

        .list-group-item {
            border: none;
            margin-bottom: 0.5rem;
            border-radius: 15px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
        }

        .list-group-item:hover {
            background-color: #f1f4ff !important;
            transform: scale(1.01);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.6rem;
        }

        .empty-state i {
            color: #b0b9d6;
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .btn-sm i {
            pointer-events: none;
        }

        .new-dot {
            width: 10px;
            height: 10px;
            background-color: #4e73df;
            border-radius: 50%;
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            box-shadow: 0 0 6px rgba(78, 115, 223, 0.7);
        }

        .notification-item {
            position: relative;
            animation: fadeSlide 0.5s ease;
        }

        /* Tooltip efek */
        [data-bs-toggle="tooltip"] {
            position: relative;
        }
    </style>

    <div class="container-fluid animate-fadeSlide">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-12">
                <div class="card border-0 shadow-lg card-modern">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-bell me-2"></i> Notifikasi
                        </h5>
                        <div>
                            <button class="btn btn-outline-success text-semi-bold btn-sm" onclick="markAllAsRead()"
                                data-bs-toggle="tooltip">
                                <i class="fas fa-check-double me-1"></i> Tandai Semua
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="clearAllNotifications()"
                                data-bs-toggle="tooltip">
                                <i class="fas fa-trash me-1"></i> Hapus Semua
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        @if($notifications->count() > 0)
                            <div class="list-group">
                                @foreach($notifications as $notification)
                                    <div
                                        class="list-group-item list-group-item-action notification-item {{ !$notification->is_read ? 'bg-light' : 'bg-white' }}">
                                        @if(!$notification->is_read)
                                            <div class="new-dot"></div>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-start ps-4">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <h6 class="mb-0 fw-bold text-dark me-2">{{ $notification->title }}</h6>
                                                    @if(!$notification->is_read)
                                                        <span class="badge bg-primary">Baru</span>
                                                    @endif
                                                </div>
                                                <p class="mb-1 text-muted">{{ $notification->message }}</p>
                                                <small class="text-muted">
                                                    <i
                                                        class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                                    @if($notification->data && isset($notification->data['type']))
                                                        <span
                                                            class="badge bg-secondary ms-2">{{ ucfirst($notification->data['type']) }}</span>
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="d-flex align-items-center ms-2">
                                                @if($notification->action_url)
                                                    <a href="{{ $notification->action_url }}"
                                                        class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="tooltip"
                                                        title="Lihat detail">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                @endif
                                                <button class="btn btn-sm btn-outline-danger"
                                                    onclick="deleteNotification({{ $notification->id }})" data-bs-toggle="tooltip"
                                                    title="Hapus notifikasi">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $notifications->links() }}
                            </div>
                        @else
                            <div class="text-center py-5 empty-state">
                                <i class="fas fa-bell-slash fa-5x mb-3"></i>
                                <h5 class="text-muted">Tidak Ada Notifikasi</h5>
                                <p class="text-secondary">Anda belum memiliki notifikasi apapun saat ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus notifikasi ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function markAllAsRead() {
            if (confirm('Tandai semua notifikasi sebagai sudah dibaca?')) {
                fetch('/teacher/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                }).then(res => res.json())
                    .then(data => data.success ? location.reload() : alert('Gagal menandai notifikasi.'));
            }
        }

        function clearAllNotifications() {
            if (confirm('Hapus semua notifikasi? Tindakan ini tidak dapat dibatalkan.')) {
                fetch('/teacher/notifications/clear-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                }).then(res => res.json())
                    .then(data => data.success ? location.reload() : alert('Gagal menghapus notifikasi.'));
            }
        }

        function deleteNotification(notificationId) {
            if (confirm('Hapus notifikasi ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/teacher/notifications/${notificationId}`;
                form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
        });
    </script>
@endsection