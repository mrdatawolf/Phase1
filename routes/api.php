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
use App\Models\ExchangeRate;

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

Route::resource('automate_resources', AutomateResourcesController::class);
Route::resource('eligible_to_automate', EligibleToAutomateController::class);
Route::resource('eligible_to_enable', EligibleToEnableController::class);
Route::resource('eligible_to_improve', EligibleToImproveController::class);
Route::resource('enable_resources', EnableResourcesController::class);
Route::resource('improve_multiplier', ImproveMultiplierController::class);
Route::resource('improve_resources', ImproveResourcesController::class);
Route::resource('resource_automated', ResourceAutomatedController::class);
Route::resource('resource', ResourceController::class);
Route::resource('resource_enabled', ResourceEnabledController::class);
Route::resource('resource_increment_amounts', ResourceIncrementAmountsController::class);
Route::resource('total_foreman', TotalForemanController::class);
Route::resource('total_tools', TotalToolsController::class);
Route::resource('total_workers', TotalWorkersController::class);
Route::resource('bank', BankController::class);
Route::resource('exchange_rate', ExchangeRate::class);
/*
Route::middleware('auth:sanctum')->get('/members/fullid/{id}', [MemberController::class, 'fullid']);
Route::get('/members/heardfrom/{id}', [MemberController::class, 'heardFrom']);
Route::middleware('auth:sanctum')->get('/members/search/{name}', [MemberController::class, 'search']);

Route::put('/members/fullid/{id}', [MemberController::class, 'updateFullid']);
*/
