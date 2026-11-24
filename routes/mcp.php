<?php

use App\Http\Controllers\McpController;
use Illuminate\Support\Facades\Route;

Route::match(['GET', 'POST', 'DELETE'], '/mcp', McpController::class)->name('mcp');
