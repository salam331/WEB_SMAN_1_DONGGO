{{-- @extends('layouts.app')

@section('title', 'Edit Pesan - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit text-primary me-2"></i>
                        Edit Pesan
                    </h5>
                    <a href="{{ route('teachers.messages') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('teachers.messages.update', $message) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Kepada</label>
                            <input type="text" class="form-control" value="{{ $message->receiver->name }}" readonly>
                            <small class="text-muted">Penerima tidak dapat diubah</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subjek</label>
                            <input type="text" class="form-control" name="subject" value="{{ old('subject', $message->subject) }}" required>
                            @error('subject')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pesan</label>
                            <textarea class="form-control" name="content" rows="8" required>{{ old('content', $message->body) }}</textarea>
                            @error('content')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('teachers.messages') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
