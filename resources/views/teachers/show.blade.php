@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Detail Guru</h1>

    <div class="bg-white p-4 rounded shadow">
        <p><strong>NIP:</strong> {{ $teacher->nip }}</p>
        <p><strong>Nama:</strong> {{ $teacher->nama }}</p>
        <p><strong>Jabatan:</strong> {{ $teacher->jabatan }}</p>
        <p class="mt-2">{{ $teacher->bio }}</p>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.teachers') }}">Kembali</a>
    </div>
</div>
@endsection
