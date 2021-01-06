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
    Route::get('/parents/list',[Controllers\ParentController::class,'list'])->name('parents.list');
    Route::get('/parents/{parent}',[Controllers\ParentController::class,'show'])->name('parents.show');
    //Children
    Route::get('/children',[Controllers\ChildController::class, 'index'])->name('children.index');
    Route::post('/children',[Controllers\ChildController::class,'store'])->name('children.store');
    Route::patch('/children/{child}',[Controllers\ChildController::class,'update'])->name('children.udpate');
    Route::delete('/children/{child}',[Controllers\ChildController::class,'destroy'])->name('children.delete');
    Route::get('/children/all')->name('childre.paginate');

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
    Route::get('plans/list', [Controllers\PlanController::class,'list'])->name('plans.list');

    //Vaccines
    Route::get('/vaccines',[Controllers\VaccineController::class,'index'])->name('vaccines.index');
    Route::post('/vaccines',[Controllers\VaccineController::class,'store'])->name('vaccines.store');
    Route::patch('/vaccines/{vaccine}',[Controllers\VaccineController::class,'update'])->name('vaccines.update');
    Route::delete('/vaccines/{vaccine}',[Controllers\VaccineController::class,'destroy'])->name('vaccines.destroy');

    Route::get('/visits',[Controllers\VisitController::class, 'index'])->name('visits.index');
    Route::get('/visits/{id}',[Controllers\VisitController::class,'show'])->name('visits.show');
    Route::post('/visits',[Controllers\VisitController::class, 'store'])->name('visits.store');
    Route::patch('/visits/{id}',[Controllers\VisitController::class, 'update'])->name('visits.update');
    Route::delete('/visits/{id}', [Controllers\VisitController::class, 'destroy'])->name('visits.destroy');
    Route::get('/visit/newOrEdit/{id?}',[Controllers\VisitController::class, 'newOrEdit'])->name('visits.new');
});


