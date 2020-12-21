<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Patient;
use App\Http\Controllers;
use App\Http\Controllers\Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->group(function (){
    //Parents
    Route::get('/parents',[Controllers\ParentController::class,'index'])->name('parents.index');
    Route::post('/parents',[Controllers\ParentController::class,'store'])->name('parents.store');
    Route::patch('/parents/{parent}',[Controllers\ParentController::class,'update'])->name('parents.update');
    Route::delete('/parents/{parent}',[Controllers\ParentController::class,'destroy']);
    Route::get('/parents/all',[Controllers\ParentController::class, 'all'])->name('parents.paginate');
    
    //Children
    Route::get('/children',[Controllers\ChildController::class, 'index'])->name('children.index');

    //Users
    Route::get('/users',[Controllers\UserController::class, 'index'])->name('users.index');

    Route::get('/users',[Controllers\UserController::class, 'index'])->name('users.index');

    //Dashboard
    Route::get('/dashboard',[Controllers\DashboardController::class, 'index'])->name('dashboard');

    //Insurance
    Route::get('/insurances',[Controllers\InsuranceController::class, 'index'])->name('insurances.index');
    Route::post('/insurances',[Controllers\InsuranceController::class,'store'])->name('insurances.store');
    Route::patch('/insurances/{insurance}',[Controllers\InsuranceController::class,'update'])->name('insurance.update');
    Route::delete('/insurances/{insurance}',[Controllers\InsuranceController::class,'destroy'])->name('insurance.destroy');
    
    //Plans
    Route::get('/plans/insunrance/{insurance}',[Controllers\PlanController::class,'index'])->name('plans');
    Route::post('/plans',[Controllers\PlanController::class,'store']);
    Route::delete('/plans/{plan}',[Controllers\PlanController::class,'destroy']);
    Route::patch('/plans/{plan}',[Controllers\PlanController::class,'update']);
});


