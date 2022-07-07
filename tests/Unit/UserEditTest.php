<?php

namespace Tests\Unit;

use App\Models\Admin;
use App\Models\User;
use App\Models\UserDraft;
use App\Models\UserEdit;
use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;

class UserEditTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->authAdmin();

        $this->user = User::factory()->create();
        $this->admin  = Admin::where('role', 'maker')->get()->random()->id;
        $this->userDraft = UserDraft::factory()->create();
        $this->userEdit = UserEdit::factory()->create();
    }


    /** @test */
    public function admin_can_save_a_user_draft()
    {
        $userEdit = UserEdit::factory()->create([
            "editable_id" => $this->userDraft->id,
            "editable_type" => UserDraft::class,
            'maker_id' => $this->admin
        ]);

        $this->assertInstanceOf(UserDraft::class, $userEdit->editable);
    }

    /** @test */
    public function admin_can_view_pending_request()
    {
        $response = $this->getJson(route('admin.user.pending'));

        $this->assertEquals(1, count($response->json()));
    }

    /** @test */
    public function admin_can_reject_pending_request()
    {
        $this->deleteJson(route('admin.user.decline',  $this->userEdit->id));
        $this->assertDatabaseMissing('user_edits', ['id' => $this->userEdit->id]);
    }

    /** @test */
    public function admin_can_approve_pending_request()
    {
        $response = $this->getJson(route('admin.user.approve', $this->userEdit->id));
        $this->assertEquals(1, count($response->json()));
    }
}
