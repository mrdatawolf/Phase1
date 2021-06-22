<?php

namespace Tests\Feature;

use App\Models\Gather;
use App\Models\TotalResources;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GatherTest extends TestCase
{
    public $user;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user, ['*']);
        TotalResources::factory()->create();
        Artisan::call('db:seed', ['--class' => 'ResourceEnabledSeeder', '--database' => 'sqlitem']);
    }

    public function test_instantiate() {
        $gather = new Gather(1);
        $this->assertEquals(1, $gather->getOwner());
        $this->assertEquals(1, $gather->getResourceId());
        $this->assertEquals(0, $gather->getStatus());
        $this->assertEquals(0, $gather->getAmount());
        $this->assertEquals(2, $gather->getMultiplier());
    }

    public function test_add() {
        $gather = new Gather(1);
        $gather->setStatus(1);
        $gather->add();
        $this->assertEquals(100, $gather->getAmount());
    }
}
