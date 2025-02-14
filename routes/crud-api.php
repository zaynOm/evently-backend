<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventParticipantController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(
    function () {
        Route::prefix('categories')->name('categories.')->group(
            function () {
                Route::controller(CategoryController::class)->group(
                    function () {
                        Route::post('/', 'createOne');
                        Route::get('/{id}', 'readOne');
                        Route::get('/', 'readAll');
                        Route::put('/{id}', 'updateOne');
                        Route::patch('/{id}', 'patchOne');
                        Route::delete('/{id}', 'deleteOne');
                    }
                );
            }
        );

        Route::prefix('events')->name('events.')->group(
            function () {
                Route::controller(EventController::class)->group(
                    function () {
                        Route::post('/', 'createOne');
                        Route::get('/{id}', 'readOne');
                        Route::get('/', 'readAll');
                        Route::put('/{id}', 'updateOne');
                        Route::patch('/{id}', 'patchOne');
                        Route::delete('/{id}', 'deleteOne');
                    }
                );
                Route::controller(EventParticipantController::class)->group(
                    function () {
                        Route::post('/{id}/join', 'join');
                        Route::post('/{id}/leave', 'leave');
                        Route::get('/{id}/participants', 'participants');
                    }
                );
            }
        );
    }
);
