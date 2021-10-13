<?php

$routes->group('api', function ($routes) {

    $routes->add('', '\Muravian\CiGen\Controllers\Api::defaultAnswer');

    // Index
    $routes->get("(:alphanum)", "\Muravian\CiGen\Controllers\Api::index/$1/$2");

    // Show single
    $routes->get('(:alphanum)/(:num)', '\Muravian\CiGen\Controllers\Api::show/$1/$2');

    // Search
    $routes->post('(:alphanum)/search', '\Muravian\CiGen\Controllers\Api::search/$1');

    // Create
    $routes->put('(:alphanum)/create', '\Muravian\CiGen\Controllers\Api::create/$1');

    // Update Single
    $routes->patch('(:alpha)/update/(:num)', '\Muravian\CiGen\Controllers\Api::update/$1/$2');

    // Delete single
    $routes->delete('(:alpha)/delete/(:num)', '\Muravian\CiGen\Controllers\Api::delete/$1/$2');

    // Delete Bulk
    $routes->post('(:alpha)/delete/', '\Muravian\CiGen\Controllers\Api::deleteBulk/$1');

});