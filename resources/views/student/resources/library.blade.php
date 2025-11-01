@extends('layouts.app')

@section('title', 'Perpustakaan - SMAN 1 DONGGO')

@section('content')
<div class="container-fluid fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card border-0 shadow-lg rounded-4 animate-card">
                <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-book fa-lg me-2"></i> Perpustakaan Sekolah
                    </h5>
                </div>
                <div class="card-body p-4 bg-light-subtle">
                    <!-- Borrowed Books Section -->
                    @if($borrowedBooks->count() > 0)
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-bookmark me-2"></i> Buku yang Sedang Dipinjam
                            </h6>
                            <div class="row g-3">
                                @foreach($borrowedBooks as $borrow)
                                    <div class="col-lg-6 col-xl-4">
                                        <div class="card h-100 border-0 shadow-sm rounded-3" style="border-left: 4px solid #ffc107;">
                                            <div class="card-body p-3">
                                                <h6 class="card-title fw-bold text-primary mb-2">{{ $borrow->book->title }}</h6>
                                                <small class="text-muted d-block mb-1">Penulis: {{ $borrow->book->author ?? '-' }}</small>
                                                <small class="text-muted d-block mb-1">Penerbit: {{ $borrow->book->publisher ?? '-' }}</small>
                                                <small class="text-muted d-block mb-2">
                                                    Dipinjam: {{ $borrow->borrowed_at->format('d/m/Y') }}
                                                </small>
                                                <small class="text-muted d-block mb-3">
                                                    Jatuh Tempo: {{ $borrow->due_at->format('d/m/Y') }}
                                                </small>

                                                @if($borrow->due_at < now())
                                                    <div class="alert alert-danger py-2 px-3 mb-3" style="font-size: 0.85rem;">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Buku sudah melewati batas waktu pengembalian!
                                                    </div>
                                                @else
                                                    <div class="alert alert-warning py-2 px-3 mb-3" style="font-size: 0.85rem;">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Sisa waktu: {{ $borrow->due_at->diffInDays(now()) }} hari lagi
                                                    </div>
                                                @endif

                                                <form method="POST" action="{{ route('student.library.return', $borrow->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm w-100"
                                                            onclick="return confirm('Apakah Anda yakin ingin mengembalikan buku ini?')">
                                                        <i class="fas fa-undo me-1"></i> Kembalikan Buku
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <hr class="my-4">
                    @endif

                    <!-- Available Books Section -->
                    <div class="mb-3">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-books me-2"></i> Koleksi Buku Tersedia
                        </h6>
                    </div>

                    @if($books->count() > 0)
                        <div class="row g-4">
                            @foreach($books as $book)
                                <div class="col-lg-6 col-xl-4">
                                    <div class="card h-100 border-0 shadow-sm rounded-3 hover-lift">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="avatar me-3" style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-book fa-lg text-white"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title fw-bold text-primary mb-1">{{ $book->title }}</h6>
                                                    <small class="text-muted">Penulis: {{ $book->author ?? '-' }}</small>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <small class="text-muted d-block">Penerbit: {{ $book->publisher ?? '-' }}</small>
                                                <small class="text-muted d-block">Tahun: {{ $book->year ?? '-' }}</small>
                                                <small class="text-muted d-block">ISBN: {{ $book->isbn ?? '-' }}</small>
                                            </div>

                                            <div class="mb-3">
                                                @if($book->stock > 0)
                                                    <span class="badge bg-success fs-6 px-3 py-2">
                                                        <i class="fas fa-check-circle me-1"></i> Tersedia ({{ $book->stock }})
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger fs-6 px-3 py-2">
                                                        <i class="fas fa-times-circle me-1"></i> Tidak Tersedia
                                                    </span>
                                                @endif
                                            </div>

                                            @if($book->description)
                                                <p class="card-text text-muted mb-3" style="font-size: 0.9rem;">
                                                    {{ Str::limit($book->description, 100) }}
                                                </p>
                                            @endif

                                            @if($book->stock > 0)
                                                <form method="POST" action="{{ route('student.library.borrow') }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                                        <i class="fas fa-hand-holding me-1"></i> Pinjam Buku
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-secondary btn-sm w-100" disabled>
                                                    <i class="fas fa-ban me-1"></i> Stok Habis
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $books->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-books fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada koleksi buku</h5>
                            <p class="text-muted">Koleksi buku perpustakaan akan muncul di sini setelah admin menambahkan buku.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

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
