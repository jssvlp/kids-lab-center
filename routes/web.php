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
    Route::get('/children/all',[Controllers\ChildController::class,'all'])->name('childre.paginate');
    Route::get('/children/list',[Controllers\ChildController::class,'list'])->name('children.list');
    Route::get('/children/{id}/history',[Controllers\ChildController::class, 'history'])->name('children.history');
    Route::post('/children/verifyplannumber', [Controllers\ChildController::class,'checkPlanInsuranceNumber'])->name('children.verifyplan');
    //Users
    Route::get('/users',[Controllers\UserController::class, 'index'])->name('users.index');

    Route::get('/users',[Controllers\UserController::class, 'index'])->name('users.index');

    //Dashboard
    Route::get('/dashboard',[Controllers\DashboardController::class, 'index'])->name('dashboard');

    //Insurance
    Route::get('/insurances',[Controllers\InsuranceController::class, 'index'])->name('insurances.index');
    Route::get('/insurances/all',[Controllers\InsuranceController::class, 'all'])->name('insurances.all');
    Route::post('/insurances',[Controllers\InsuranceController::class,'store'])->name('insurances.store');
    Route::patch('/insurances/{insurance}',[Controllers\InsuranceController::class,'update'])->name('insurance.update');
    Route::delete('/insurances/{insurance}',[Controllers\InsuranceController::class,'destroy'])->name('insurance.destroy');
    
    //Plans
    Route::get('/plans/insunrance/{insurance}',[Controllers\PlanController::class,'index'])->name('plans');
    Route::post('/plans',[Controllers\PlanController::class,'store']);
    Route::delete('/plans/{plan}',[Controllers\PlanController::class,'destroy']);
    Route::patch('/plans/{plan}',[Controllers\PlanController::class,'update']);
    Route::get('/plans/list', [Controllers\PlanController::class,'list'])->name('plans.list');

    //Vaccines
    Route::get('/vaccines',[Controllers\VaccineController::class,'index'])->name('vaccines.index');
    Route::get('/vaccines/all/paginated',[Controllers\VaccineController::class,'all'])->name('vaccines.paginated');
    Route::post('/vaccines',[Controllers\VaccineController::class,'store'])->name('vaccines.store');
    Route::patch('/vaccines/{vaccine}',[Controllers\VaccineController::class,'update'])->name('vaccines.update');
    Route::delete('/vaccines/{vaccine}',[Controllers\VaccineController::class,'destroy'])->name('vaccines.destroy');
    Route::get('/vaccines/list',[Controllers\VaccineController::class,'list'])->name('vaccines.list');
    //Visits
    Route::get('/visits',[Controllers\VisitController::class, 'index'])->name('visits.index');
    Route::get('/visits/{id}',[Controllers\VisitController::class,'show'])->name('visits.show');
    Route::get('/visits/all/paginated',[Controllers\VisitController::class,'all'])->name('visits.paginate');
    Route::post('/visits',[Controllers\VisitController::class, 'store'])->name('visits.store');
    Route::patch('/visits/{id}',[Controllers\VisitController::class, 'update'])->name('visits.update');
    Route::delete('/visits/{id}', [Controllers\VisitController::class, 'destroy'])->name('visits.destroy');
    Route::get('/visits/newOrEdit/{id?}',[Controllers\VisitController::class, 'newOrEdit'])->name('visits.newOrEdit');
    Route::post('/visits/{visit}/vaccine/{vaccine}',[Controllers\VisitController::class, 'addVaccine'])->name('visits.add.vaccine');
    Route::delete('/visits/{visit}/vaccine/{vaccine}',[Controllers\VisitController::class, 'removeVaccine'])->name('visits.remove.vaccine');
    Route::get('/visits/{visit}/vaccines',[Controllers\VisitController::class,'vaccines']);
    Route::get('/visits/{visit}/get',[Controllers\VisitController::class, 'getVisit'])->name('visit.get');
    //Invoices
    Route::post('/invoices',[Controllers\InvoiceController::class,'store'])->name('invoices.store');
    Route::get('/invoices',[Controllers\InvoiceController::class,'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}',[Controllers\InvoiceController::class,'detail'])->name('invoices.detail');
    Route::post('/invoices/pay/{invoice}',[Controllers\InvoiceController::class,'pay'])->name('invoices.pay');
    Route::get('/invoices/{invoice}/print',[Controllers\InvoiceController::class,'print'])->name('invoices.print');
    Route::get('/invoices/all/paginated',[Controllers\InvoiceController::class,'all'])->name('invoices.paginated');

    //Reports
    Route::get('/reports',[Controllers\ReportController::class,'index'])->name('reports.index');
    Route::post('/reports/filter',[Controllers\ReportController::class,'filter'])->name('reports.filter');
});


