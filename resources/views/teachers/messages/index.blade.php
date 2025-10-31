{{-- @extends('layouts.app')

@section('title', 'Pesan - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        Pesan & Komunikasi
                    </h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#composeModal">
                        <i class="fas fa-plus me-1"></i>Kirim Pesan
                    </button>
                </div>
                <div class="card-body">
                    <!-- Tabs untuk Inbox dan Sent -->
                    <ul class="nav nav-tabs" id="messageTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="inbox-tab" data-bs-toggle="tab" data-bs-target="#inbox" type="button" role="tab">
                                <i class="fas fa-inbox me-1"></i>Inbox
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" type="button" role="tab">
                                <i class="fas fa-paper-plane me-1"></i>Terkirim
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3" id="messageTabsContent">
                        <!-- Inbox Tab -->
                        <div class="tab-pane fade show active" id="inbox" role="tabpanel">
                            @if($receivedMessages->count() > 0)
                                <div class="list-group">
                                    @foreach($receivedMessages as $message)
                                    <div class="list-group-item list-group-item-action {{ !$message->is_read ? 'bg-light' : '' }}">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <strong class="me-2">{{ $message->sender->name }}</strong>
                                                    @if(!$message->is_read)
                                                        <span class="badge bg-primary">Baru</span>
                                                    @endif
                                                </div>
                                                <p class="mb-1 text-truncate">{{ $message->subject }}</p>
                                                <small class="text-muted">{{ Str::limit($message->body, 100) }}</small>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted d-block">{{ $message->created_at->diffForHumans() }}</small>
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewMessage({{ $message->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-3">
                                    {{ $receivedMessages->appends(request()->except('inbox_page'))->links() }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada pesan masuk</p>
                                </div>
                            @endif
                        </div>

                        <!-- Sent Tab -->
                        <div class="tab-pane fade" id="sent" role="tabpanel">
                            @if($uniqueSentMessages->count() > 0)
                                <div class="list-group">
                                    @foreach($uniqueSentMessages as $message)
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <strong class="me-2">Kepada:
                                                        @if($message->recipient_type)
                                                            @switch($message->recipient_type)
                                                                @case('students')
                                                                    Semua Siswa
                                                                    @break
                                                                @case('parents')
                                                                    Semua Orang Tua
                                                                    @break
                                                                @case('teachers')
                                                                    Semua Guru
                                                                    @break
                                                                @case('admins')
                                                                    Semua Admin
                                                                    @break
                                                                @case('users')
                                                                    Semua Pengguna
                                                                    @break
                                                                @default
                                                                    {{ $message->recipient ? $message->recipient->name : 'Pengguna Tidak Ditemukan' }}
                                                            @endswitch
                                                        @else
                                                            {{ $message->recipient ? $message->recipient->name : 'Pengguna Tidak Ditemukan' }}
                                                        @endif
                                                    </strong>
                                                </div>
                                                <p class="mb-1 text-truncate">{{ $message->subject }}</p>
                                                <small class="text-muted">{{ Str::limit($message->body, 100) }}</small>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted d-block">{{ $message->created_at->diffForHumans() }}</small>
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewMessage({{ $message->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-3">
                                    {{ $uniqueSentMessages->links() }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-paper-plane fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada pesan terkirim</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Compose Message -->
<div class="modal fade" id="composeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kirim Pesan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="POST" action="{{ route('teachers.messages.store') }}">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Kepada</label>
                    <select class="form-select" name="recipient_id" required>
                        <option value="">Pilih Penerima</option>
                        <optgroup label="Semua">
                            <option value="all_students">Semua Siswa</option>
                            <option value="all_parents">Semua Orang Tua</option>
                            <option value="all_teachers">Semua Guru</option>
                            <option value="all_admins">Semua Admin</option>
                            <option value="all_users">Semua Pengguna</option>
                        </optgroup>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Subjek</label>
                    <input type="text" class="form-control" name="subject" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pesan</label>
                    <textarea class="form-control" name="content" rows="5" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Kirim Pesan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal View Message -->
<div class="modal fade" id="viewMessageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageSubject"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Dari:</strong> <span id="messageSender"></span>
                </div>
                <div class="mb-3">
                    <strong>Kepada:</strong> <span id="messageRecipient"></span>
                </div>
                <div class="mb-3">
                    <strong>Waktu:</strong> <span id="messageTime"></span>
                </div>
                <hr>
                <div id="messageContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="replyBtn" style="display: none;">Balas</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewMessage(messageId) {
    fetch(`/teacher/messages/${messageId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('messageSubject').textContent = data.subject;
            document.getElementById('messageSender').textContent = data.sender.name;
            document.getElementById('messageRecipient').textContent = data.recipient.name;
            document.getElementById('messageTime').textContent = new Date(data.created_at).toLocaleString('id-ID');
            document.getElementById('messageContent').innerHTML = data.body.replace(/\n/g, '<br>');

            // Show reply button for received messages
            const replyBtn = document.getElementById('replyBtn');
            if (data.recipient_id === {{ auth()->id() }}) {
                replyBtn.style.display = 'inline-block';
                replyBtn.onclick = () => replyMessage(data.sender_id, data.subject);
            } else {
                replyBtn.style.display = 'none';
            }

            new bootstrap.Modal(document.getElementById('viewMessageModal')).show();
        });
}

function replyMessage(recipientId, originalSubject) {
    // Close view modal
    bootstrap.Modal.getInstance(document.getElementById('viewMessageModal')).hide();

    // Open compose modal with pre-filled data
    const composeModal = new bootstrap.Modal(document.getElementById('composeModal'));
    composeModal.show();

    // Pre-fill form
    setTimeout(() => {
        document.querySelector('select[name="recipient_id"]').value = recipientId;
        document.querySelector('input[name="subject"]').value = 'Re: ' + originalSubject;
        document.querySelector('textarea[name="content"]').focus();
    }, 500);
}
</script>
@endsection --}}
