@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-semibold mb-4">Edit Guru</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="post" action="{{ route('admin.teachers.update', $teacher) }}">
        @csrf @method('put')
        <div class="mb-3">
            <label class="block mb-1">NIP</label>
            <input type="text" name="nip" class="border p-2 w-full" value="{{ old('nip', $teacher->nip) }}" />
        </div>
        <div class="mb-3">
            <label class="block mb-1">Nama</label>
            <input type="text" name="nama" class="border p-2 w-full" value="{{ old('nama', $teacher->nama) }}" />
        </div>
        <div class="mb-3">
            <label class="block mb-1">Jabatan</label>
            <input type="text" name="jabatan" class="border p-2 w-full" value="{{ old('jabatan', $teacher->jabatan) }}" />
        </div>
        <div class="mb-3">
            <label class="block mb-1">Bio</label>
            <textarea name="bio" class="border p-2 w-full">{{ old('bio', $teacher->bio) }}</textarea>
        </div>
        <div>
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('admin.teachers') }}" class="ml-2">Batal</a>
        </div>
    </form>
</div>
@endsection
