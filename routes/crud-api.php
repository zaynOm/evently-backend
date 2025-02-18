<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\StatsController;
use App\Mail\JoinedEventMail;
use App\Models\Event;
use App\Models\User;
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

        Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');
    }
);

Route::get('/test-email', function () {
    return new JoinedEventMail(
        $event = new Event([
            'title' => 'Event 1',
            'description' => 'Description 1',
            'date' => '2023-01-01',
            'time' => '12:00',
            'location' => 'Location 1',
            'capacity' => 100,
            'host_id' => 1,
            'category_id' => 1,
        ]),
        $user = new User([
            'full_name' => 'User 1',
            'email' => 'user1@example.com',
            'password' => 'password1',
        ])
    );
});
