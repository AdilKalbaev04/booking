<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserController;

// Маршруты аутентификации
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Маршруты залов
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/halls', [HallController::class, 'index']); // Получение списка залов
    Route::post('/halls', [HallController::class, 'store']); // Добавление зала (админ)
    Route::put('/halls/{id}', [HallController::class, 'update']); // Обновление зала (админ)
    Route::delete('/halls/{id}', [HallController::class, 'destroy']); // Удаление зала (админ)


    // Маршруты бронирования
    Route::get('/bookings', [BookingController::class, 'index']); // Получение списка бронирований
    Route::post('/bookings', [BookingController::class, 'store']); // Создание бронирования
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']); // Удаление бронирования

    // Маршруты пользователей (только для администратора)
    Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index']); // Получение списка пользователей
        Route::post('/users', [UserController::class, 'store']); // Создание пользователя
        Route::put('/users/{id}', [UserController::class, 'update']); // Редактирование пользователя
        Route::delete('/users/{id}', [UserController::class, 'destroy']); // Удаление пользователя
    });
});
