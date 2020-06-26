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

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;
use Config\Database;
use Sssm\Base\Config\SssmBase;

abstract class ModuleInstallerBase extends Model implements ModuleInstallerInterface{
    
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
    /**
     * @var int モジュールをインストール可能なユーザ
     */
    protected $installableUser = SssmBase::systemRole_ROOT | SssmBase::systemRole_ADMINISTRATOR;
    
    private $installed = false;
    private $uninstalled = false;
    
    protected $info = [];
    
    protected $dbTables = [];
    protected $dbSchema = [];
    protected $dbPrimaryKey = [];
    protected $dbKey = [];
    protected $dbForeignKey = [];
    protected $dbUniqueKey = [];
    
    protected $table = 'modules';
    protected $allowedFields = [
        'name' ,
        'namespace' ,
        'path' ,
    ];
    
    protected $moduleFilePath = null;
    protected $moduleFilePathBase = null;
    
    public function __construct( ConnectionInterface &$db = null , ValidationInterface $validation = null ){
        parent::__construct( $db , $validation );
        
        $_SESSION[$_ENV['sssm.sysname']]['User']['Role'] = $_SESSION[$_ENV['sssm.sysname']]['User']['Role'] ?? null;
        if( !SssmBase::hasRole( $this->installableUser )  ){
            throw $e;
        }
        if( $this->moduleFilePath === null ){
            throw $e;
        }
        
        $this->moduleFilePathBase = dirname( dirname( dirname( $this->moduleFilePath ) ) ) . DIRECTORY_SEPARATOR;
        
        $this->info = parse_ini_file( $this->moduleFilePathBase . $_ENV['sssm.module_ini_file'] , true );

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

    public function install(){
        try{
            $this->createTables();
        }catch( Exception $e ){
            throw $e;
        }
        $this->installed = true;
    }
    
    public function uninstall(){
        try{
            $forge = Database::forge();
            foreach( array_keys( $this->dbTables ) as $table ){
                $forge->dropTable( $table );
            }
        }catch( Exception $e ){
            throw $e;
        }
        $this->uninstalled = true;
    }
    
    /**
     * @throws Exception
     */
    private function createTables(){
        try{
            $forge = Database::Forge();
            foreach( $this->dbTables as $table => $attr ){
                if( isset( $this->dbSchema[$table] ) ){
                    
                    $forge->addField( $this->dbSchema[$table] );
                    if( isset( $this->dbKey[$table] ) && count( $this->dbKey[$table] ) > 0 ){
                        $forge->addKey( $this->dbKey[$table] );
                    }
                    if( isset( $this->dbPrimaryKey[$table] ) && count( $this->dbPrimaryKey[$table] ) > 0  ){
                        $forge->addPrimaryKey( $this->dbPrimaryKey[$table] );
                    }
                    if( isset( $this->dbForeignKey[$table] ) && count( $this->dbForeignKey[$table] ) > 0 ){
                        $forge->addForeignKey( $this->dbForeignKey[$table] );
                    }
                    if( isset( $this->dbUniqueKey[$table] ) && count( $this->dbUniqueKey[$table] ) > 0 ){
                        $forge->addUniqueKey( $this->dbUniqueKey[$table] );
                    }
                    $forge->createTable( $table , true , $attr );
                }
            }
        }catch( Exception $e ){
            throw $e;
        }
    }
    
}