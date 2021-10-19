<?php

$routes->group('api', function ($routes) {

    $routes->add('', '\Muravian\CiGen\Controllers\Api::defaultAnswer');

    $routes->get("(:segment)", "\Muravian\CiGen\Controllers\Api::index/$1/$2"); // Index
    $routes->get('(:segment)/(:num)', '\Muravian\CiGen\Controllers\Api::show/$1/$2'); // Show single
    $routes->get("(:segment)/fields", "\Muravian\CiGen\Controllers\Api::fields/$1"); // Fields

    $routes->put('(:segment)', '\Muravian\CiGen\Controllers\Api::create/$1'); // Create

    $routes->patch('(:segment)/(:num)', '\Muravian\CiGen\Controllers\Api::update/$1/$2'); // Update Single

    $routes->delete('(:segment)/(:num)', '\Muravian\CiGen\Controllers\Api::delete/$1/$2'); // Delete single
    $routes->delete('(:segment)/(:num)/(:segment)', "\Muravian\CiGen\Controllers\Api::file_delete/$1/$2/$3"); // Upload

    $routes->post('(:segment)', '\Muravian\CiGen\Controllers\Api::deleteBulk/$1'); // Delete Bulk
    $routes->post('(:segment)/(:num)/(:segment)', "\Muravian\CiGen\Controllers\Api::file_upload/$1/$2/$3"); // Upload

});