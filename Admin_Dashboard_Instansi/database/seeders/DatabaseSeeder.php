<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan menggunakan tanda :: bukan tanda titik .
        User::create([
            'name' => 'Administrator BKPSDM',
            'username' => 'adminbkpsdm', 
            'nip' => '198701012024001',   
            'password' => Hash::make('rahasia123'), 
        ]);
    }
}