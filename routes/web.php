<?php

Route::get('dashboard', fn() => response('Dashboard'));

require __DIR__ . '/auth.php';
