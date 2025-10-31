@extends('layouts.app')

@section('title', 'Notifikasi - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell text-primary me-2"></i>
                        Notifikasi
                    </h5>
                    <div>
                        <button class="btn btn-outline-secondary btn-sm me-2" onclick="markAllAsRead()">
                            <i class="fas fa-check-double me-1"></i>Tandai Semua Dibaca
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="clearAllNotifications()">
                            <i class="fas fa-trash me-1"></i>Hapus Semua
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                            <div class="list-group-item list-group-item-action {{ !$notification->is_read ? 'bg-light' : '' }} position-relative">
                                @if(!$notification->is_read)
                                    <div class="position-absolute top-50 start-0 translate-middle-y bg-primary rounded-circle" style="width: 8px; height: 8px; margin-left: 10px;"></div>
                                @endif

                                <div class="d-flex w-100 justify-content-between align-items-start" style="margin-left: 20px;">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <h6 class="mb-0 me-2">{{ $notification->title }}</h6>
                                            @if(!$notification->is_read)
                                                <span class="badge bg-primary">Baru</span>
                                            @endif
                                        </div>
                                        <p class="mb-1 text-muted">{{ $notification->message }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                            @if($notification->data && isset($notification->data['type']))
                                                <span class="badge bg-secondary ms-2">{{ ucfirst($notification->data['type']) }}</span>
                                            @endif
                                        </small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        @if($notification->action_url)
                                            <a href="{{ $notification->action_url }}" class="btn btn-sm btn-outline-primary me-2">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteNotification({{ $notification->id }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted">Tidak Ada Notifikasi</h5>
                            <p class="text-muted">Anda belum memiliki notifikasi apapun.</p>
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus notifikasi ini?</p>
            </div>
            <div class="modal-footer">
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

<script>
function markAllAsRead() {
    if (confirm('Apakah Anda yakin ingin menandai semua notifikasi sebagai sudah dibaca?')) {
        fetch('/teacher/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Terjadi kesalahan saat menandai notifikasi.');
            }
        });
    }
}

function clearAllNotifications() {
    if (confirm('Apakah Anda yakin ingin menghapus semua notifikasi? Tindakan ini tidak dapat dibatalkan.')) {
        fetch('/teacher/notifications/clear-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Terjadi kesalahan saat menghapus notifikasi.');
            }
        });
    }
}

function deleteNotification(notificationId) {
    if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/teacher/notifications/${notificationId}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);

        document.body.appendChild(form);
        form.submit();
    }
}

// Auto-refresh notifications every 30 seconds
setInterval(function() {
    // You can add logic to check for new notifications here
    // For now, we'll just update the timestamps
    const timestamps = document.querySelectorAll('.text-muted i.fa-clock');
    timestamps.forEach(timestamp => {
        // This would need more complex logic to update relative times
    });
}, 30000);
</script>
@endsection
