@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Daftar Guru</h1>
        <a href="{{ route('admin.teachers.create') }}" class="bg-blue-600 text-white px-3 py-2 rounded">Tambah Guru</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form method="get" class="mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau NIP" class="border p-2 rounded w-full" />
    </form>

    <div class="bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($teachers as $teacher)
                <tr>
                    <td class="px-6 py-4">{{ $teacher->nip }}</td>
                    <td class="px-6 py-4">{{ $teacher->nama }}</td>
                    <td class="px-6 py-4">{{ $teacher->jabatan }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="text-blue-600 mr-2">Edit</a>
                        <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="post" class="inline-block" onsubmit="return confirm('Hapus guru ini?')">
                            @csrf @method('delete')
                            <button class="text-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $teachers->links() }}</div>
</div>
@endsection
