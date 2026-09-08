<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenantTest extends TestCase
{
    use RefreshDatabase;

    protected User $superadmin;
    protected User $owner1;
    protected User $owner2;
    protected Business $biz1;
    protected Business $biz2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->superadmin = User::where('email', 'superadmin@saaserp.com')->first();
        $this->owner1 = User::where('email', 'admin@postani.com')->first();
        $this->owner2 = User::where('email', 'budi@kopinusantara.com')->first();
        $this->biz1 = Business::where('name', 'Toko Tani & Agribisnis Jaya')->first();
        $this->biz2 = Business::where('name', 'Kopi Nusantara & Cafe')->first();
    }

    public function test_super_admin_can_access_admin_dashboard_and_management(): void
    {
        $response = $this->actingAs($this->superadmin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Platform Super Administrator');
        $response->assertSee('Total Unit Usaha (Tenants)');

        $usersResponse = $this->actingAs($this->superadmin)->get(route('admin.users.index'));
        $usersResponse->assertStatus(200);
        $usersResponse->assertSee($this->owner1->email);
        $usersResponse->assertSee($this->owner2->email);

        $bizResponse = $this->actingAs($this->superadmin)->get(route('admin.businesses.index'));
        $bizResponse->assertStatus(200);
        $bizResponse->assertSee($this->biz1->name);
        $bizResponse->assertSee($this->biz2->name);
    }

    public function test_regular_user_cannot_access_super_admin_routes(): void
    {
        $response = $this->actingAs($this->owner1)->get(route('admin.dashboard'));
        $response->assertStatus(403);

        $usersResponse = $this->actingAs($this->owner1)->get(route('admin.users.index'));
        $usersResponse->assertStatus(403);

        $bizResponse = $this->actingAs($this->owner1)->get(route('admin.businesses.index'));
        $bizResponse->assertStatus(403);
    }

    public function test_super_admin_can_toggle_user_status(): void
    {
        $this->assertTrue($this->owner2->is_active);

        $response = $this->actingAs($this->superadmin)->patch(route('admin.users.toggle-status', $this->owner2));
        $response->assertSessionHas('success');

        $this->owner2->refresh();
        $this->assertFalse($this->owner2->is_active);
    }

    public function test_super_admin_can_toggle_business_status(): void
    {
        $this->assertTrue($this->biz2->is_active);

        $response = $this->actingAs($this->superadmin)->patch(route('admin.businesses.toggle-status', $this->biz2));
        $response->assertSessionHas('success');

        $this->biz2->refresh();
        $this->assertFalse($this->biz2->is_active);
    }

    public function test_tenant_data_isolation_between_businesses(): void
    {
        // 1. Create Product in Business 1
        $this->actingAs($this->owner1);
        session(['active_business_id' => $this->biz1->id]);
        
        $cat1 = Category::firstOrCreate(['name' => 'Kategori Tani', 'business_id' => $this->biz1->id]);
        $unit1 = Unit::firstOrCreate(['name' => 'Sak Tani', 'symbol' => 'sak', 'business_id' => $this->biz1->id]);

        $prod1 = Product::create([
            'business_id' => $this->biz1->id,
            'sku' => 'TANI-SPECIAL-001',
            'name' => 'Pupuk Organik Khusus Tani',
            'category_id' => $cat1->id,
            'buy_unit_id' => $unit1->id,
            'sell_unit_id' => $unit1->id,
            'conversion_factor' => 1,
            'selling_price' => 50000,
            'avg_purchase_price' => 40000,
            'stock' => 10,
        ]);

        // 2. Create Product in Business 2
        $this->actingAs($this->owner2);
        session(['active_business_id' => $this->biz2->id]);

        $cat2 = Category::firstOrCreate(['name' => 'Kategori Kopi', 'business_id' => $this->biz2->id]);
        $unit2 = Unit::firstOrCreate(['name' => 'Cup Kopi', 'symbol' => 'cup', 'business_id' => $this->biz2->id]);

        $prod2 = Product::create([
            'business_id' => $this->biz2->id,
            'sku' => 'KOPI-SPECIAL-001',
            'name' => 'Espresso Blend Single Origin',
            'category_id' => $cat2->id,
            'buy_unit_id' => $unit2->id,
            'sell_unit_id' => $unit2->id,
            'conversion_factor' => 1,
            'selling_price' => 25000,
            'avg_purchase_price' => 15000,
            'stock' => 50,
        ]);

        // 3. Verify Owner 1 only sees Business 1 products
        $this->actingAs($this->owner1);
        session(['active_business_id' => $this->biz1->id]);
        $response1 = $this->get(route('products.index'));
        $response1->assertStatus(200);
        $response1->assertSee('Pupuk Organik Khusus Tani');
        $response1->assertDontSee('Espresso Blend Single Origin');

        // 4. Verify Owner 2 only sees Business 2 products
        $this->actingAs($this->owner2);
        session(['active_business_id' => $this->biz2->id]);
        $response2 = $this->get(route('products.index'));
        $response2->assertStatus(200);
        $response2->assertSee('Espresso Blend Single Origin');
        $response2->assertDontSee('Pupuk Organik Khusus Tani');
    }

    public function test_user_can_create_new_business_and_switch_active_business(): void
    {
        $this->actingAs($this->owner1);

        // 1. Create a 2nd business under Owner 1
        $createResponse = $this->post(route('businesses.store'), [
            'name' => 'Cabang Baru Grosir Tani',
            'business_type' => 'wholesale',
            'phone' => '081299887766',
            'city' => 'Surabaya',
        ]);

        $createResponse->assertRedirect(route('dashboard'));

        $newBiz = Business::where('name', 'Cabang Baru Grosir Tani')->first();
        $this->assertNotNull($newBiz);
        $this->assertEquals($this->owner1->id, $newBiz->owner_id);

        // 2. Switch back to first business
        $switchResponse = $this->post(route('businesses.switch', $this->biz1));
        $switchResponse->assertRedirect();
        
        $this->owner1->refresh();
        $this->assertEquals($this->biz1->id, $this->owner1->active_business_id);
    }

    public function test_admin_root_redirects_and_renders_responsive_layout(): void
    {
        // 1. /admin should redirect to /admin/dashboard
        $redirectResponse = $this->actingAs($this->superadmin)->get('/admin');
        $redirectResponse->assertRedirect(route('admin.dashboard'));

        // 2. /admin/dashboard uses responsive admin layout with desktop sidebar and mobile drawer
        $dashboardResponse = $this->actingAs($this->superadmin)->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('mobileSidebarOpen');
        $dashboardResponse->assertSee('Super Admin');
        $dashboardResponse->assertSee('Kelola Seluruh Pengguna');
        $dashboardResponse->assertSee('Kelola Seluruh Tenant/Usaha');

        // 3. Admin users edit page renders properly with responsive layout
        $editResponse = $this->actingAs($this->superadmin)->get(route('admin.users.edit', $this->owner1));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Edit Pengguna:');
    }
}

