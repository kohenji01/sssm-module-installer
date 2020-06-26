<?php
/**
 * =============================================================================================
 *  Project: sssm
 *  File: ModuleInstaller.php
 *  Date: 2020/05/20 11:32
 *  Author: Shoji Ogura <kohenji@sarahsytems.com>
 *  Copyright (c) 2020. SarahSystems lpc.
 *  This software is released under the MIT License, see LICENSE.txt.
 * =============================================================================================
 */

namespace Sssm\ModuleInstaller\Models;

use Sssm\Base\Config\SssmBase;
use Sssm\Install\Models\SystemInit;


interface ModuleInstallerInterface{
    
    public function get_info();

    public function install();
    
    public function uninstall();

}