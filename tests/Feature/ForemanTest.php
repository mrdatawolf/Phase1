<?php

namespace Tests\Feature;

use App\Models\EligibleToAddForeman;
use App\Models\TotalForeman;
use App\Models\TotalResources;
use App\Models\TotalTools;
use App\Models\User;
use App\Models\Foreman;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ForemanTest extends TestCase
{
    public $user;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user, ['*']);
        TotalResources::factory()->create();
        TotalForeman::factory()->create();
        TotalTools::factory()->create();
        EligibleToAddForeman::factory()->create();
        Artisan::call('db:seed', ['--class' => 'ResourceEnabledSeeder', '--database' => 'sqlitem']);
        Artisan::call('db:seed', ['--class' => 'EligibleToEnableSeeder', '--database' => 'sqlitem']);
    }

    public function test_instantiate() {
        $tool = new Foreman(1);
        $this->assertEquals(1, $tool->getOwner());
        $this->assertEquals(1, $tool->getResourceId());
        $this->assertEquals(100, $tool->getAmount());
        $this->assertEquals(500, $tool->getCost());
        $this->assertEquals(1, $tool->getValue());
        $this->assertEquals(0, $tool->getEligibleToAdd());
    }

    public function test_add() {
        $tool = new Foreman(1);
        $tool->add();
        $this->assertEquals(100, $tool->getAmount());
    }
}
