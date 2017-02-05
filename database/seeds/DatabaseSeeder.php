<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sources')->insert(['name' => 'MangaStream', 'url' => 'http://mangastream.com/']);

        DB::table('mangas')->insert([
            ['name' => 'One Piece'],
            ['name' => 'Dragon Ball Super'],
            ['name' => 'Haikyu'],
            ['name' => 'Fairy Tail'],
        ]);

        DB::table('manga_source')->insert([
            ['manga_id' => 1, 'source_id' => 1],
            ['manga_id' => 2, 'source_id' => 1],
            ['manga_id' => 3, 'source_id' => 1],
            ['manga_id' => 4, 'source_id' => 1],
        ]);
        // $this->call(UsersTableSeeder::class);
    }
}
