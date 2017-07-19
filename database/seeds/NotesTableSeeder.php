<?php

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Inspiring;

class NotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notes')->delete();

        for ($i=0; $i < 50; $i++) {
            \App\Note::create([
                'title'    => 'Title '.$i,
                'author'   => 'Author '.$i,
                'dateTime' => new DateTime(),
                'text'     => Inspiring::quote()
            ]);
        }
    }
}
