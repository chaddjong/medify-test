<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MasterItemsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil semua master items beserta relasi kategori dan supplier
        return MasterItem::with('categories', 'supplier')->get()->map(function($item, $index) {
            return [
                'no' => $index + 1,
                'kategori' => $item->categories->pluck('nama')->implode(', '),
                'nama_item' => $item->nama,
                'nama_supplier' => $item->supplier?->nama ?? '-',
                'harga' => $item->harga,
                'laba' => $item->laba,
                'harga_jual' => $item->harga_jual,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori',
            'Nama Item',
            'Nama Supplier',
            'Harga',
            'Laba',
            'Harga Jual',
        ];
    }
}