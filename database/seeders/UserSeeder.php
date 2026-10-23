<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            /*
            [
                'name' => 'Administrador',
                'email' => 'administrador',
                'grupo' => '1',
                'password' => Hash::make('envaseadmin'),
            ],
            [
                'name' => 'Arquitecto',
                'email' => 'arquitecto',
                'grupo' => '2',
                'password' => Hash::make('envasearqui'),
            ],
            [
                'name' => 'ETIF',
                'email' => 'etif',
                'grupo' => '3',
                'password' => Hash::make('envaseetif'),
            ],
            */
            [
                'name' => 'Vendedor 1',
                'email' => 'vendedor1',
                'grupo' => '4',
                'password' => Hash::make('sarten347'),
            ],
            [
                'name' => 'Vendedor 2',
                'email' => 'vendedor2',
                'grupo' => '4',
                'password' => Hash::make('taza523'),
            ],
            [
                'name' => 'Prensa',
                'email' => 'prensa',
                'grupo' => '5',
                'password' => Hash::make('melocoton835'),
            ],
        ]);
    }
}
