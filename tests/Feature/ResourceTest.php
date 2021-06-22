<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create(), ['*']);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index()
    {

        $response = $this->get('/api/resources');
        $response->assertOk();
    }


    public function test_store()
    {
        $response = $this->post('/api/resources');
        $response->assertStatus(403);
    }


    public function test_show()
    {
        $response = $this->get('/api/resources/1');
        $response->assertStatus(200);
    }


    public function test_update()
    {
        $response = $this->put('/api/resources/1');
        $response->assertStatus(403);
    }


    public function test_delete()
    {
        $response = $this->delete('/api/resources/1');
        $response->assertStatus(403);
    }
}
