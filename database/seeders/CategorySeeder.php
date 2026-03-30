<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultCategories = [
            ['kode' => 'PRM', 'nama' => 'Promo'],
            ['kode' => 'OL', 'nama' => 'Obat Luar'],
            ['kode' => 'OD', 'nama' => 'Obat Dalam'],
        ];

        foreach($defaultCategories as $cat) {
            Category::firstOrCreate(['nama' => $cat['nama']], $cat);
        }
    }
}
