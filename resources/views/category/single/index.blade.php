{{-- resources/views/category/single/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Kategori Items</h1>
        <a href="{{ url('categories/create') }}" class="btn btn-primary">Tambah Kategori</a>
    </div>

    {{-- Tabel kategori --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Kode</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $category->kode }}</td>
                <td>{{ $category->nama }}</td>
                <td>
                    <a href="{{ url('categories/' . $category->id . '/edit') }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ url('categories/' . $category->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                    <a href="{{ route('categories.pdf', $category->id) }}" class="btn btn-success">Download PDF</a>
                    <a href="{{ route('master-items.excel') }}" class="btn btn-success mb-3">Download Excel</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada data kategori.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection