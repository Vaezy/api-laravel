<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
            'title' => 'Titre 1',
            'author' => 'Autheur 1',
            'summary' => 'Sommaire numéro 1',
            'isbn' => '9781234567890',
        ]);

        Book::create([
            'title' => 'Titre 2',
            'author' => 'Autheur 2',
            'summary' => 'Sommaire numéro 2',
            'isbn' => '9781234567891',
        ]);
    }
}
