<?php
namespace Tests\Feature;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class SecurityFoundationTest extends TestCase {
    use RefreshDatabase;
    public function test_guest_cannot_open_dashboard(): void { $this->get('/dashboard')->assertRedirect('/login'); }
    public function test_admin_can_open_security_pages(): void {
        $admin=User::factory()->create(['role'=>'admin']);
        $this->actingAs($admin)->get('/admin/audit')->assertOk();
    }
    public function test_non_admin_is_denied_from_admin_pages(): void {
        $user=User::factory()->create(['role'=>'staff']);
        $this->actingAs($user)->get('/admin/audit')->assertForbidden();
    }
}
