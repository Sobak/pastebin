<?php

use App\Http\Controllers\PasteController;

Route::get('/', [PasteController::class, 'create'])->name('index');
Route::post('/', [PasteController::class, 'store']);

Route::get('{paste}/download', [PasteController::class, 'download'])->name('download');

Route::get('{paste}/edit', [PasteController::class, 'edit'])->name('edit');
Route::post('{paste}/edit', [PasteController::class, 'update']);

Route::get('{paste}/raw', [PasteController::class, 'showRaw'])->name('raw');

Route::get('{paste}/remove', [PasteController::class, 'removeShow'])->name('remove');
Route::post('{paste}/remove', [PasteController::class, 'remove']);

Route::get('{paste}', [PasteController::class, 'show'])->name('show');
