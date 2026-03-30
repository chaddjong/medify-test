@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Kategori</h1>

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="kode" class="form-label">Kode</label>
            <input type="text" name="kode" id="kode" class="form-control" value="{{ old('kode') }}">
            @error('kode')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Kategori</label>
            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama') }}">
            @error('nama')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection