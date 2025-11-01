{{-- @extends('layouts.app')

@section('title', 'Notifikasi - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-12">
            <div class="card border-0 shadow-lg rounded-4 animate-card">
                <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-bell me-2"></i> Notifikasi
                    </h5>
                    @if($unreadCount > 0)
                        <span class="badge bg-danger">{{ $unreadCount }} belum dibaca</span>
                    @endif
                </div>
                <div class="card-body p-4 bg-light-subtle">
                    @if($notifications->count() > 0)
                        <div class="row g-3">
                            @foreach($notifications as $notification)
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm rounded-3 hover-lift {{ $notification->is_read ? '' : 'border-start border-primary border-4' }}">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <h6 class="card-title fw-bold text-primary mb-0 me-2">
                                                            {{ $notification->title }}
                                                        </h6>
                                                        @if(!$notification->is_read)
                                                            <span class="badge bg-primary">Baru</span>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i> {{ $notification->created_at->format('d/m/Y H:i') }}
                                                        @if($notification->type)
                                                            <span class="badge bg-secondary ms-2">{{ ucfirst($notification->type) }}</span>
                                                        @endif
                                                    </small>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if(!$notification->is_read)
                                                            <li><a class="dropdown-item" href="#" onclick="markAsRead({{ $notification->id }})">
                                                                <i class="fas fa-check me-2"></i> Tandai Sudah Dibaca
                                                            </a></li>
                                                        @endif
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteNotification({{ $notification->id }})">
                                                            <i class="fas fa-trash me-2"></i> Hapus
                                                        </a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <p class="card-text text-muted mb-0" style="font-size: 0.9rem;">
                                                {{ $notification->message }}
                                            </p>

                                            @if($notification->action_url)
                                                <div class="mt-2">
                                                    <a href="{{ $notification->action_url }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-arrow-right me-1"></i> Lihat Detail
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->links() }}
                        </div>

                        <!-- Bulk Actions -->
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button class="btn btn-outline-primary btn-sm" onclick="markAllAsRead()">
                                        <i class="fas fa-check-double me-1"></i> Tandai Semua Sudah Dibaca
                                    </button>
                                </div>
                                <div>
                                    <small class="text-muted">Menampilkan {{ $notifications->count() }} dari {{ $notifications->total() }} notifikasi</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada notifikasi</h5>
                            <p class="text-muted">Notifikasi akan muncul di sini ketika ada informasi penting dari sekolah.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/student/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error marking notification as read');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error marking notification as read');
    });
}

function markAllAsRead() {
    if (confirm('Apakah Anda yakin ingin menandai semua notifikasi sebagai sudah dibaca?')) {
        fetch('/student/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error marking all notifications as read');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error marking all notifications as read');
        });
    }
}

function deleteNotification(notificationId) {
    if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
        fetch(`/student/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting notification');
            }
        })
        .catch(error => {
            console.error('Error deleting notification:', error);
            alert('Error deleting notification');
        });
    }
}
</script>

<style>
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-card {
    animation: slideInUp 0.6s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection --}}
