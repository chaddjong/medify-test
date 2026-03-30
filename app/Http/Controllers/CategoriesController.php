<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF; // Dompdf alias via Facade
use App\Models\MasterItem; // jika tabel item namanya MasterItem

class CategoriesController extends Controller
{
    // Menampilkan semua kategori
    public function index()
    {
        $categories = Category::all();
        return view('category.single.index', compact('categories'));
    }

    // Menampilkan form tambah kategori
    public function create()
    {
        return view('category.single.create');
    }

    // Menyimpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:categories,kode',
            'nama' => 'required',
        ]);

        Category::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Menampilkan form edit kategori
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('category.single.edit', compact('category'));
    }

    // Update kategori
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|unique:categories,kode,' . $id,
            'nama' => 'required',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    // Hapus kategori
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }

    public function exportPdf($id)
    {
        // Eager load relasi items
        $category = Category::with('items')->findOrFail($id);

        // $items pasti Collection, walaupun kosong
        $items = $category->items;

        $pdf = PDF::loadView('category.single.pdf', [
            'category' => $category,
            'items' => $items
        ]);

        return $pdf->download('kategori_'.$category->kode.'.pdf');
    }
}