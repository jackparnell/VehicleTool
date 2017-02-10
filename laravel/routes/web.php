<?php

Route::any('api', 'ApiController@index');

Route::get('api/vehicle/{unitNumber}', 'ApiController@vehicle');

Route::post('api/damageReport/create', 'ApiController@damageReportCreate');
Route::post('api/defectReport/create', 'ApiController@defectReportCreate');
Route::post('api/vehicleCheck/create', 'ApiController@vehicleCheckCreate');

Route::get('/', 'UserInputController@index');


