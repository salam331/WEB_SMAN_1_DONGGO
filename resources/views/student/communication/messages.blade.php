@extends('layouts.app')

@section('title', 'Pesan - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-12">
            <div class="card border-0 shadow-lg rounded-4 animate-card">
                <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-envelope me-2"></i> Kotak Pesan
                    </h5>
                </div>
                <div class="card-body p-4 bg-light-subtle">
                    @if($messages->count() > 0)
                        <div class="row g-3">
                            @foreach($messages as $message)
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm rounded-3 hover-lift {{ $message->is_read ? '' : 'border-start border-primary border-4' }}">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title fw-bold text-primary mb-1">
                                                        {{ $message->subject }}
                                                        @if(!$message->is_read)
                                                            <span class="badge bg-primary ms-2">Baru</span>
                                                        @endif
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-user me-1"></i>
                                                        @if($message->sender_type == 'student')
                                                            Siswa: {{ $message->sender->name ?? 'Unknown' }}
                                                        @elseif($message->sender_type == 'teacher')
                                                            Guru: {{ $message->sender->name ?? 'Unknown' }}
                                                        @elseif($message->sender_type == 'parent')
                                                            Orang Tua: {{ $message->sender->name ?? 'Unknown' }}
                                                        @else
                                                            Admin
                                                        @endif
                                                        <i class="fas fa-clock ms-3 me-1"></i> {{ $message->created_at->format('d/m/Y H:i') }}
                                                    </small>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="event.preventDefault(); viewMessage({{ $message->id }})">
                                                            <i class="fas fa-eye me-2"></i> Lihat Pesan
                                                        </a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="javascript:void(0);" onclick="event.preventDefault(); deleteMessage({{ $message->id }})">
                                                            <i class="fas fa-trash me-2"></i> Hapus
                                                        </a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <p class="card-text text-muted mb-0" style="font-size: 0.9rem;">
                                                {{ Str::limit($message->body, 150) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $messages->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-envelope-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada pesan</h5>
                            <p class="text-muted">Kotak pesan Anda masih kosong. Mulai kirim pesan ke guru atau admin.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



<!-- View Message Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Detail Pesan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="messageContent">
                <!-- Message content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function viewMessage(messageId) {
    fetch(`/student/messages/${messageId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('messageContent').innerHTML = `
                <div class="mb-3">
                    <strong>Subjek:</strong> ${data.subject}
                </div>
                <div class="mb-3">
                    <strong>Dari:</strong> ${data.sender_name}
                </div>
                <div class="mb-3">
                    <strong>Waktu:</strong> ${data.created_at}
                </div>
                <div class="mb-3">
                    <strong>Pesan:</strong>
                    <div class="mt-2 p-3 bg-light rounded">${data.body}</div>
                </div>
            `;

            // Mark as read if not already
            if (!data.is_read) {
                fetch(`/student/messages/${messageId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
            }

            new bootstrap.Modal(document.getElementById('viewModal')).show();
        })
        .catch(error => {
            console.error('Error loading message:', error);
            alert('Error loading message');
        });
}

function deleteMessage(messageId) {
    if (confirm('Apakah Anda yakin ingin menghapus pesan ini?')) {
        fetch(`/student/messages/${messageId}`, {
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
                alert('Error deleting message');
            }
        })
        .catch(error => {
            console.error('Error deleting message:', error);
            alert('Error deleting message');
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
@endsection
