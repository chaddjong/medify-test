<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\MasterItemsExport;
use Maatwebsite\Excel\Facades\Excel;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::with('categories'); // eager load categories

        if (!empty($kode)) {
            $data_search->where('kode', $kode);
        }

        if (!empty($nama)) {
            $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        }

        if (!empty($hargamin)) {
            $data_search->where('harga_beli', '>=', $hargamin);
        }

        if (!empty($hargamax)) {
            $data_search->where('harga_beli', '<=', $hargamax);
        }

        $data_search = $data_search
            ->select('id', 'kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier', 'gambar')
            ->orderBy('id')
            ->get();

        // Tambahkan kolom kategori sebagai string
        $data_search->transform(function($item) {
            $item->kategori_list = $item->categories->pluck('nama')->implode(', ');
            return $item;
        });

        return response()->json([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        $item = ($method == 'edit') 
            ? MasterItem::with('categories')->findOrFail($id) // eager load categories
            : null;

        // ambil semua kategori dari database (untuk multi-select dropdown)
        $categories = Category::all();

        // kirim ke view
        return view('master_items.form.index', [
            'item' => $item,
            'method' => $method,
            'categories' => $categories,
        ]);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id') + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;

        // upload gambar
        if ($request->hasFile('gambar')) {
            // hapus gambar lama saat edit
            if ($method == 'edit' && $data_item->gambar) {
                Storage::disk('public')->delete($data_item->gambar);
            }

            $path = $request->file('gambar')->store('master-items', 'public');
            $data_item->gambar = $path;
        }

        $data_item->save();

        $selectedCategories = $request->categories ?? [];
        $data_item->categories()->sync($selectedCategories);

        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach($data as $item)
        {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100,1000000);
            $item->laba = rand(10,99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    public function exportExcel()
    {
        return Excel::download(new MasterItemsExport, 'master_items.xlsx');
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi','Bukulapuk','TokoBagas','E Commurz','Blublu'];
        $random = rand(0,4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat','Alkes','Matkes','Umum','ATK'];
        $random = rand(0,4);
        return $array[$random];
    }
}
