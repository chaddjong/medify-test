<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PDF Kategori {{ $category->nama }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px; text-align: left; }
        footer { position: fixed; bottom: 0; text-align: right; font-size: 10px; }
    </style>
</head>
<body>
    <h2>Kategori: {{ $category->nama }}</h2>
    <p>Kode: {{ $category->kode }}</p>

    <h3>Daftar Item</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Item</th>
                <th>Kode Item</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->deskripsi }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada item untuk kategori ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <footer>
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}
    </footer>
</body>
</html>