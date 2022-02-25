<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\models\Genre;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            'name' => 'フード'
        ];

        $genre = new Genre;

        $genre->fill($params)->save();

        $params = [
            'name' => 'ドリンク'
        ];

        $genre = new Genre;

        $genre->fill($params)->save();

        $params = [
            'name' => '日用品'
        ];

        $genre = new Genre;

        $genre->fill($params)->save();

        $params = [
            'name' => '家電'
        ];

        $genre = new Genre;

        $genre->fill($params)->save();
    }
}
