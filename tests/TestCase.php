<?php

namespace Tests;

use App\Models\Admin;
use App\Models\UserEdit;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    public function createAdmin($args = [])
    {
        return  Admin::create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'role' => "maker",
            'password' => bcrypt('secret1234')
        ]);
    }


    public function authAdmin()
    {
        $admin = $this->createAdmin();
        Sanctum::actingAs($admin, ['*']);
        return $admin;
    }
}
