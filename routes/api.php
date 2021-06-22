<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutomateResourcesController;
use App\Http\Controllers\EligibleToAutomateController;
use App\Http\Controllers\EligibleToEnableController;
use App\Http\Controllers\EligibleToImproveController;
use App\Http\Controllers\EnableResourcesController;
use App\Http\Controllers\ImproveMultiplierController;
use App\Http\Controllers\ImproveResourcesController;
use App\Http\Controllers\ResourceAutomatedController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ResourceEnabledController;
use App\Http\Controllers\ResourceIncrementAmountsController;
use App\Http\Controllers\TotalForemanController;
use App\Http\Controllers\TotalToolsController;
use App\Http\Controllers\TotalWorkersController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\AuthController;
use App\Models\ExchangeRate;
use App\Http\Controllers\InitialGather;
use App\Http\Controllers\AddController;
use App\Http\Controllers\GatherController;
use App\Http\Controllers\AutomateController;
use App\Http\Controllers\EnableController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/resources/ids', [ResourceController::class, 'ids']);
    Route::put('add/{type}/{resourceId}', [AddController::class, 'addImprovement']);
    Route::apiResources([
        'automate_resources'         => AutomateResourcesController::class,
        'eligible_to_automate'       => EligibleToAutomateController::class,
        'eligible_to_enable'         => EligibleToEnableController::class,
        'eligible_to_improve'        => EligibleToImproveController::class,
        'enable_resources'           => EnableResourcesController::class,
        'improve_multiplier'         => ImproveMultiplierController::class,
        'improve_resources'          => ImproveResourcesController::class,
        'resource_automated'         => ResourceAutomatedController::class,
        'resources'                  => ResourceController::class,
        'resource_enabled'           => ResourceEnabledController::class,
        'resource_increment_amounts' => ResourceIncrementAmountsController::class,
        'total_foreman'              => TotalForemanController::class,
        'total_tools'                => TotalToolsController::class,
        'total_workers'              => TotalWorkersController::class,
        'bank'                       => BankController::class,
        'exchange_rate'              => ExchangeRate::class,
        'current_numbers'            => InitialGather::class,
        'add'                        => AddController::class,
        'gather'                     => GatherController::class,
        'enable'                     => EnableController::class,
        'automate'                   => AutomateController::class
    ]);
});

/*
Route::middleware('auth:sanctum')->get('/members/fullid/{id}', [MemberController::class, 'fullid']);
Route::get('/members/heardfrom/{id}', [MemberController::class, 'heardFrom']);
Route::middleware('auth:sanctum')->get('/members/search/{name}', [MemberController::class, 'search']);

Route::put('/members/fullid/{id}', [MemberController::class, 'updateFullid']);
*/
