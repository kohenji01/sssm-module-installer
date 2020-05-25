<?php
/**
 * =============================================================================================
 *  Project: sssm-core
 *  File: Routes.php
 *  Date: 2020/05/21 19:19
 *  Author: Shoji Ogura <kohenji@sarahsytems.com>
 *  Copyright (c) 2020. Shoji Ogura
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

$routes->group('', ['namespace' => 'Sssm\ModuleInstaller\Controllers'], function($routes) {
    $routes->get('ModuleInstall', 'ModuleInstall::index');
    $routes->get('ModuleInstall/(:any)', 'ModuleInstall::$1');
});
