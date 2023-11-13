<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Libraries\BookDataImportProcessor;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookDataImportProcessor = new BookDataImportProcessor();
        $data = $bookDataImportProcessor->importData();
    }
}
