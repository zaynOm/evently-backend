<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function index(): JsonResponse
    {
        $stats = [
            'users_count' => User::count(),
            'events_count' => Event::count(),
            'categories_count' => Category::count(),
        ];

        return response()->json(['success' => true, 'data' => $stats]);
    }
}
