<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-eye me-2"></i> Detail Log Aktivitas
            </h5>
        </div>
        <div class="card-body bg-light bg-gradient p-5">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">🕒 Waktu</label>
                    <p class="form-control border-0 shadow-sm rounded-3">{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">👤 User</label>
                    <p class="form-control border-0 shadow-sm rounded-3">{{ $log->user->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">🛡️ Role</label>
                    <p class="form-control border-0 shadow-sm rounded-3">{{ ucfirst($log->role ?? 'N/A') }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">⚡ Aksi</label>
                    <p class="form-control border-0 shadow-sm rounded-3">{{ ucfirst($log->action) }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">📦 Model</label>
                    <p class="form-control border-0 shadow-sm rounded-3">{{ $log->model }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">🆔 Model ID</label>
                    <p class="form-control border-0 shadow-sm rounded-3">{{ $log->model_id ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">🌐 IP Address</label>
                    <p class="form-control border-0 shadow-sm rounded-3">{{ $log->ip_address ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-secondary">📝 Deskripsi</label>
                    <p class="form-control border-0 shadow-sm rounded-3">{{ $log->description ?? 'N/A' }}</p>
                </div>
            </div>

            @if($log->old_values)
                <div class="mt-4">
                    <label class="form-label fw-semibold text-danger mb-2">📜 Nilai Lama</label>
                    <div class="form-control border-0 shadow-sm rounded-3 bg-red-50 overflow-auto">
                        <pre
                            class="text-danger mb-0">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            @endif

            @if($log->new_values)
                <div class="mt-4">
                    <label class="form-label fw-semibold text-success mb-2">✅ Nilai Baru</label>
                    <div class="form-control border-0 shadow-sm rounded-3 bg-green-50 overflow-auto">
                        <pre
                            class="text-success mb-0">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            @endif

            @if($log->meta)
                <div class="mt-4">
                    <label class="form-label fw-semibold text-primary mb-2">ℹ️ Metadata Tambahan</label>
                    <div class="form-control border-0 shadow-sm rounded-3 bg-blue-50 overflow-auto">
                        <pre
                            class="text-primary mb-0">{{ json_encode($log->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15) !important;
    }

    .card {
        transition: box-shadow 0.3s ease, transform 0.2s ease;
    }

    .card:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    pre {
        margin-bottom: 0;
    }

    /* Responsivitas untuk mobile - hapus padding horizontal pada sisi kiri dan kanan */
    @media (max-width: 992px) {
        main {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .card-header {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .card-body {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
    }
</style>
