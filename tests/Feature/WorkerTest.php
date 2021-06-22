<?php

namespace Tests\Feature;

use App\Models\EligibleToAddWorker;
use App\Models\TotalResources;
use App\Models\TotalWorkers;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WorkerTest extends TestCase
{
    public $user;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user, ['*']);
        TotalResources::factory()->create();
        TotalWorkers::factory()->create();
        EligibleToAddWorker::factory()->create();
        Artisan::call('db:seed', ['--class' => 'ResourceEnabledSeeder', '--database' => 'sqlitem']);
        Artisan::call('db:seed', ['--class' => 'EligibleToEnableSeeder', '--database' => 'sqlitem']);
    }

    public function test_instantiate() {
        $worker = new Worker(1);
        $this->assertEquals(1, $worker->getOwner());
        $this->assertEquals(1, $worker->getResourceId());
        $this->assertEquals(100, $worker->getAmount());
        $this->assertEquals(500, $worker->getCost());
        $this->assertEquals(1, $worker->getValue());
        $this->assertEquals(0, $worker->getEligibleToAdd());
    }

    public function test_add() {
        $worker = new Worker(1);
        $worker->add();
        $this->assertEquals(100, $worker->getAmount());
    }
}
