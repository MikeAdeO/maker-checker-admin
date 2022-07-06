<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Admin::truncate();
        $this->createAdmin();

    }

    protected function createAdmin(){
       $admins = [
                [
                    'name' => 'Maker John',
                    'email' => "maker@gloove.co",
                    'role' => 'maker',
                    'password' => bcrypt('1234567890')
                ],

                [
                    'name' => "Checker Doe",
                    'email' => 'checker@gloove.co',
                    'role' => 'checker',
                    'password' => bcrypt('1234567890'),
                ]
        ];
        Admin::insert($admins);
    }
}
