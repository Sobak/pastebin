<?php

use App\Http\Controllers\PasteController;

Route::post('detect-language', [PasteController::class, 'detectLanguage']);
