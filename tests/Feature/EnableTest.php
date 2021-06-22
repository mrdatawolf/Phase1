<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EnableTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create(), ['*']);
        Artisan::call('db:seed', ['--class' => 'ResourceEnabledSeeder', '--database' => 'sqlitem']);
        Artisan::call('db:seed', ['--class' => 'EligibleToEnableSeeder', '--database' => 'sqlitem']);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->get('/api/enable');
        $response->assertOk();
    }


    public function test_store()
    {
        $response = $this->post('/api/enable');
        $response->assertStatus(403);
    }


    public function test_show()
    {
        $response = $this->get('/api/enable/1');
        $response->assertStatus(200);
    }


    public function test_update()
    {
        $response = $this->put('/api/enable/1');
        $response->assertStatus(200);
    }


    public function test_delete()
    {
        $response = $this->delete('/api/enable/1');
        $response->assertStatus(200);
    }
}
