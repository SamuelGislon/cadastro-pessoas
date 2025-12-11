<?php

use Illuminate\Support\Facades\Route;

Route::view('/{qualquer}', 'app')
    ->where('qualquer', '.*');
