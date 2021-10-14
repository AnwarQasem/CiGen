<?php

$routes->group('api', function ($routes) {

    $routes->add('', '\Muravian\CiGen\Controllers\Api::defaultAnswer');

    // Index
    $routes->get("(:segment)", "\Muravian\CiGen\Controllers\Api::index/$1/$2");

    // Show single
    $routes->get('(:segment)/(:num)', '\Muravian\CiGen\Controllers\Api::show/$1/$2');

    // Create
    $routes->put('(:segment)/create', '\Muravian\CiGen\Controllers\Api::create/$1');

    // Update Single
    $routes->patch('(:segment)/update/(:num)', '\Muravian\CiGen\Controllers\Api::update/$1/$2');

    // Delete single
    $routes->delete('(:segment)/delete/(:num)', '\Muravian\CiGen\Controllers\Api::delete/$1/$2');

    // Delete Bulk
    $routes->post('(:segment)/delete/', '\Muravian\CiGen\Controllers\Api::deleteBulk/$1');

    // Fields
    $routes->get("(:segment)/fields", "\Muravian\CiGen\Controllers\Api::fields/$1");

});