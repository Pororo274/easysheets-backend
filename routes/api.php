<?php

use App\Http\Controllers\SheetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(SheetController::class)->prefix('sheets')->group(function () {
    Route::post('', 'create');
    Route::post('{sheetId}/cells', 'addCell');
    Route::post('{sheetId}/export', 'export');
});
