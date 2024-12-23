<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:api')->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees/add', [EmployeeController::class, 'store']);
    Route::put('/employees/{employee}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);
});