<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        for ($i=0; $i < 50; $i++) {
            \App\User::create([
                'name'    => 'Name '.$i,
                'email'   => 'email '.$i.'@example.com',
                'password' => Hash::make(str_random(16)),
            ]);
        }
    }
}
