<?php
use Illuminate\Support\Facades\Route;
Route::get('/', fn () => response()->json(['app' => 'HabitTrack API', 'status' => 'ok', 'docs' => '/docs/openapi.yaml']));
