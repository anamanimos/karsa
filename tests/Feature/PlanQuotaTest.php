<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Employee;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanQuotaTest extends TestCase
{
    use RefreshDatabase;

    protected User $superadmin;
    protected User $starterOwner;
    protected Business $starterBiz;
    protected Plan $starterPlan;
    protected Plan $proPlan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->superadmin = User::where('email', 'superadmin@saaserp.com')->first();
        $this->starterOwner = User::where('email', 'budi@kopinusantara.com')->first();
        $this->starterBiz = Business::where('name', 'Kopi Nusantara & Cafe')->first();

        $this->starterPlan = Plan::where('slug', 'starter')->first();
        $this->proPlan = Plan::where('slug', 'pro')->first();
    }

    public function test_starter_owner_cannot_exceed_business_limit(): void
    {
        // Starter plan allows 1 business. Budi already owns 1 business.
        $this->assertEquals(1, $this->starterOwner->maxBusinesses());
        $this->assertEquals(1, $this->starterOwner->ownedBusinesses()->count());
        $this->assertFalse($this->starterOwner->canCreateBusiness());

        // Attempt to access business create page
        $response = $this->actingAs($this->starterOwner)->get(route('businesses.create'));
        $response->assertRedirect(route('businesses.index'));
        $response->assertSessionHas('error');

        // Attempt to store 2nd business
        $storeResponse = $this->actingAs($this->starterOwner)->post(route('businesses.store'), [
            'name' => 'Kedai Kopi Cabang 2',
            'business_type' => 'fnb',
        ]);
        $storeResponse->assertRedirect(route('businesses.index'));
        $storeResponse->assertSessionHas('error');

        // Verify 2nd business was NOT created
        $this->assertDatabaseMissing('businesses', ['name' => 'Kedai Kopi Cabang 2']);
    }

    public function test_custom_max_businesses_override_allows_extra_business(): void
    {
        // Admin overrides Budi's limit to 3 stores
        $this->starterOwner->update(['custom_max_businesses' => 3]);
        $this->starterOwner->refresh();

        $this->assertEquals(3, $this->starterOwner->maxBusinesses());
        $this->assertTrue($this->starterOwner->canCreateBusiness());

        // Now Budi can access create and store 2nd business
        $this->actingAs($this->starterOwner);
        $createResponse = $this->get(route('businesses.create'));
        $createResponse->assertStatus(200);

        $storeResponse = $this->post(route('businesses.store'), [
            'name' => 'Kedai Kopi Cabang 2',
            'business_type' => 'fnb',
        ]);
        $storeResponse->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('businesses', ['name' => 'Kedai Kopi Cabang 2']);
        $this->assertEquals(2, $this->starterOwner->ownedBusinesses()->count());
    }

    public function test_starter_business_cannot_exceed_employee_limit(): void
    {
        $this->actingAs($this->starterOwner);
        session(['active_business_id' => $this->starterBiz->id]);

        // Starter plan limit is 3 employees
        $this->assertEquals(3, $this->starterOwner->maxEmployeesPerBusiness());

        // Create 3 employees (reach the limit)
        for ($i = 1; $i <= 3; $i++) {
            Employee::create([
                'business_id' => $this->starterBiz->id,
                'code' => "EMP-00{$i}",
                'name' => "Staf Barista {$i}",
                'position' => 'Barista',
                'employment_status' => 'permanent',
                'base_salary' => 2500000,
                'is_active' => true,
            ]);
        }

        $this->assertFalse($this->starterOwner->canCreateEmployee($this->starterBiz));

        // Attempting to create a 4th employee should be rejected
        $createResponse = $this->get(route('employees.create'));
        $createResponse->assertRedirect(route('employees.index'));
        $createResponse->assertSessionHas('error');

        $storeResponse = $this->post(route('employees.store'), [
            'code' => 'EMP-004',
            'name' => 'Staf Barista 4',
            'position' => 'Barista',
            'employment_status' => 'permanent',
            'base_salary' => 2500000,
            'is_active' => true,
        ]);
        $storeResponse->assertRedirect(route('employees.index'));
        $storeResponse->assertSessionHas('error');

        $this->assertDatabaseMissing('employees', ['code' => 'EMP-004']);
    }

    public function test_super_admin_has_unlimited_privileges_and_can_manage_plans(): void
    {
        $this->assertTrue($this->superadmin->isSuperAdmin());
        $this->assertTrue($this->superadmin->canCreateBusiness());
        $this->assertTrue($this->superadmin->canCreateEmployee());

        // Access plans index
        $plansResponse = $this->actingAs($this->superadmin)->get(route('admin.plans.index'));
        $plansResponse->assertStatus(200);
        $plansResponse->assertSee('Starter (Gratis)');
        $plansResponse->assertSee('Pro Bisnis (Tumbuh)');
        $plansResponse->assertSee('Enterprise (Unlimited)');

        // Update plan limits
        $updateResponse = $this->put(route('admin.plans.update', $this->starterPlan), [
            'name' => 'Starter Plus',
            'price' => 0,
            'max_businesses' => 2,
            'max_employees_per_business' => 5,
            'max_products_per_business' => 200,
            'is_active' => 1,
        ]);
        $updateResponse->assertRedirect(route('admin.plans.index'));

        $this->starterPlan->refresh();
        $this->assertEquals('Starter Plus', $this->starterPlan->name);
        $this->assertEquals(2, $this->starterPlan->max_businesses);
        $this->assertEquals(5, $this->starterPlan->max_employees_per_business);
    }
}
