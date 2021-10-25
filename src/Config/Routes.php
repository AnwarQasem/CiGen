<?php

$routes->group('api', function ($routes) {

    $routes->add('', '\Muravian\CiGen\Controllers\Api::defaultAnswer');

    $routes->get("(:segment)", "\Muravian\CiGen\Controllers\Api::index/$1"); // Index
    $routes->get('(:segment)/(:num)', '\Muravian\CiGen\Controllers\Api::show/$1/$2'); // Show single
    $routes->get("(:segment)/fields", "\Muravian\CiGen\Controllers\Api::fields/$1"); // Fields

    $routes->put('(:segment)', '\Muravian\CiGen\Controllers\Api::create/$1'); // Create

    $routes->patch('(:segment)/(:num)', '\Muravian\CiGen\Controllers\Api::update/$1/$2'); // Update Single

    $routes->delete('(:segment)/(:num)', '\Muravian\CiGen\Controllers\Api::delete/$1/$2'); // Delete single
    $routes->delete('(:segment)/(:num)/(:segment)', "\Muravian\CiGen\Controllers\Api::file_delete/$1/$2/$3"); // Upload

    $routes->post('(:segment)', '\Muravian\CiGen\Controllers\Api::deleteBulk/$1'); // Delete Bulk
    $routes->post('(:segment)/(:num)/(:segment)', "\Muravian\CiGen\Controllers\Api::file_upload/$1/$2/$3"); // Upload

    // Download or view Files.
    $routes->get('img/(:segment)/(:segment)', '\Muravian\CiGen\Controllers\Api::file_get/$1/$2');
    $routes->get('(:segment)/export/(:segment)', '\Muravian\CiGen\Controllers\Api::file_export/$1/$2');

    // Import MAP
    $routes->post   ('(:segment)/import/map', '\Muravian\CiGen\Controllers\Api::get_import_map/$1');
    $routes->put('(:segment)/import/map', '\Muravian\CiGen\Controllers\Api::put_import_map/$1');
    $routes->delete('(:segment)/import/map', '\Muravian\CiGen\Controllers\Api::delete_import_map/$1');

    // Import FILE
    $routes->put('(:segment)/import', '\Muravian\CiGen\Controllers\Api::put_import/$1');
});