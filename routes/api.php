<?php

Route::module('discover');
Route::module('bulbs');
Route::put('bulbs', 'BulbsController@updateAll');
Route::module('groups');
Route::module('grouped-bulbs');