<?php


namespace Sssm\ModuleInstaller\Models;


class ModulesInfo{
    
    protected $searchPaths = [];
    protected $modulePaths = [];

    protected $moduleInfoList = [];
    
    protected $moduleIniFile = '';
    
    public function __construct(){
        $this->moduleIniFile = $_ENV['sssm.module_ini_file'];
        $this->searchPaths = [
            VENDORPATH ,
            WRITEPATH . $_ENV['sssm.sysname'] . DIRECTORY_SEPARATOR . 'Modules' . DIRECTORY_SEPARATOR ,
        ];
        $this->initInfo();
    }
    
    public function getInfo(){
        return $this->moduleInfoList;
    }
    
    protected function initInfo(){
        $this->serachModules();
        $this->getModuleInfo();
    }
    
    protected function getModuleInfo(){
        foreach( $this->modulePaths as $module_path ){
            $this->moduleInfoList[$module_path] = parse_ini_file( $module_path . $this->moduleIniFile , true );
        }
    }
    
    protected function serachModules(){
        foreach( $this->searchPaths as $dir ){
            if( is_dir( $dir ) ){
                $handle = opendir( $dir );
                while( false !== ( $vendor_name = readdir( $handle ) ) ){
                    if( $vendor_name != '.' && $vendor_name != '..' && !is_file( $dir . $vendor_name ) ){
                        $this->serachModuleEachVendor( $dir . $vendor_name . DIRECTORY_SEPARATOR ) ;
                    }
                }
                closedir( $handle );
            }else{
                return false;
            }
        }
    }
    
    protected function serachModuleEachVendor( $vendor_path ){
        if( is_dir( $vendor_path ) ){
            $handle = opendir( $vendor_path );
            while( false !== ( $module_name = readdir( $handle ) ) ){
                if( $module_name != '.' && $module_name != '..' && !is_file( $vendor_path . $module_name ) ){
                    if( $this->isModuleDir( $vendor_path . $module_name . DIRECTORY_SEPARATOR ) ){
                        $this->modulePaths[] = $vendor_path . $module_name . DIRECTORY_SEPARATOR ;
                    }
                }
            }
            closedir( $handle );
        }else{
            return false;
        }
    }
    
    protected function isModuleDir( $path ){
        return file_exists( $path . $this->moduleIniFile );
    }
    
}