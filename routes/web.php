<?php

Route::get('/', 'PasteController@create')->name('index');
Route::post('/', 'PasteController@store');

Route::get('{paste}/download', 'PasteController@download')->name('download');

Route::get('{paste}/edit', 'PasteController@edit')->name('edit');
Route::post('{paste}/edit', 'PasteController@update');

Route::get('{paste}/raw', 'PasteController@showRaw')->name('raw');

Route::get('{paste}', 'PasteController@show')->name('show');
