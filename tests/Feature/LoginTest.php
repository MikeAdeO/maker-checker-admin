<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_login_with_email_and_password()
    {
        $this->createAdmin();
        $response = $this->json('POST', route('admin.login'), [
            'email' => 'test@gmail.com',
            'password' => 'secret1234',
        ]);
        $response->assertStatus(200);
        $this->assertArrayHasKey('token', $response->json());

        //Delete the Admin
        Admin::where('email', 'test@gmail.com')->delete();
    }
}
