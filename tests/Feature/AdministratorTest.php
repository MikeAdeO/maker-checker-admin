<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdministratorTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fetch_admin()
    {
        // $response = $this->get('/');

        // $response->assertStatus(200);
        $response=  $this->getJson(route("get-admin"));
        $this->assertEquals(1, count($response->json()));
    }
}
