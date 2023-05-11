<?php

use App\Http\Controllers\SudokuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/sudoku', [SudokuController::class, "index"]);
Route::get('/sudoku/{sudoku}', [SudokuController::class, "show"]);
Route::post('/sudoku', [SudokuController::class, "store"]);
Route::put('/sudoku/{sudoku}', [SudokuController::class, "update"]);
Route::delete('/sudoku/{sudoku}', [SudokuController::class, "destroy"]);
