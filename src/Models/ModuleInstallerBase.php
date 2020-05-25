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

use Sssm\Install\Models;


class ModuleInstallerBase extends SystemInit{
    
    /**
     * @var string モジュールバージョン
     */
    protected $version;
    /**
     * @var string モジュールクラス名
     */
    protected $moduleClassName = '';
    /**
     * @var string モジュール名
     */
    protected $moduleName;
    /**
     * @var array モジュールのメニュー設定
     */
    protected $menu = [];
    /**
     * @var bool モジュールはDBのスキーマを持っているか？
     */
    protected $moduleHasDB = false;
    /**
     * @var bool システム上からアンインストール可能なモジュールか？
     */
    public $moduleIsDeletable = true;
    /**
     * @var array 導入したテーブル群
     */
    public $installedTables = [];
    /**
     * @var array モジュールの設定
     */
    public $moduleConfig = [];
    /**
     * @var bool モジュールはcacheディレクトリ内に独自のファイルアップを伴うか
     * trueの場合はインストール時に modules/files/モジュール名 のディレクトリが作られる
     */
    public $module_has_file_upload = false;
    
    public function __construct(){
        parent::__construct();
    }
    
    public function get_info(){
        $ret['name']          = $this->moduleName;
        $ret['version']       = $this->version;
        $ret['class']         = $this->moduleClassName;
        $ret['isDeletable']   = $this->moduleIsDeletable;
        $ret['hasDB']         = $this->moduleHasDB;
        $ret['installed']     = file_exists( $this->module_info_path . $this->installed_path . $this->module_class );
        
        return $ret;
    }

    public function install_exec(){
    
    }
}