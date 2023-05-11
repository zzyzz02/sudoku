<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SudokuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sudokus')->insert([
            'name' => "Level 1",
            'file_name' => "level_1.txt",
        ]);
        DB::table('sudokus')->insert([
            'name' => "Level 2",
            'file_name' => "level_2.txt",
        ]);
    }
}
