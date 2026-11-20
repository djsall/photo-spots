<?php

use App\Livewire\Spots\View;
use Illuminate\Support\Facades\Route;

Route::get('/spots/{spot}', View::class)->name('spots.view')->middleware('signed');
