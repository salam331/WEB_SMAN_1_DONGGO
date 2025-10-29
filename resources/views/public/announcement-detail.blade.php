{{-- @extends('layouts.public')

@section('title', $announcement->title)

@section('content')
    <div class="container mt-5">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h2 class="fw-bold text-primary mb-3">
                <i class="fas fa-bullhorn me-2"></i>{{ $announcement->title }}
            </h2>

            <p class="text-muted small mb-3">
                <i class="fas fa-calendar me-1"></i>{{ $announcement->created_at->format('d M Y') }}
                @if($announcement->postedBy)
                    • Diposting oleh {{ $announcement->postedBy->name }}
                @endif
            </p>

            <div class="announcement-content text-secondary" style="line-height: 1.8;">
                {!! nl2br(e($announcement->content)) !!}
            </div>

            <div class="mt-4">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
@endsection --}}