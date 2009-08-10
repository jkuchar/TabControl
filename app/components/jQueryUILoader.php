<?php

class jQueryUILoader extends Control
{
    static private $modules = array();
    static $theme  = "smoothness";
    static $lang   = "cs";

    static $jQueryUIFolder = "js/jQuery/ui";
    static $jQueryUILocalization = "translations";
    static $jQueryUIThemeFolder = "css/jQueryUI";

    static function addModule($name){
        self::$modules[$name] = true;
    }
/*
    static function getModules(){
        return self::$modules;
    }
*/
    static function render(){
        echo "<!-- jQuery User Interface loader -->\n";
        foreach(self::$modules AS $key=>$val){
            echo "\t<!-- Module: ".$key." -->\n";
            self::addJS(self::$jQueryUIFolder."/".$key.".js", 1);
            if(strstr($key,"datepicker")){
                self::addJS(self::$jQueryUIFolder."/".self::$jQueryUILocalization."/ui.datepicker-".self::$lang.".js", 2);
            }
            if(preg_match('/^ui\./',$key)){
                self::addCSS(self::$jQueryUIThemeFolder."/".self::$theme."/".$key.".css", 2);
            }
        }
        self::addCSS(self::$jQueryUIThemeFolder."/".self::$theme."/ui.theme.css", 2);
    }

    static private function addCSS($link,$tabs=0){
        if(file_exists(WWW_DIR."/".$link)){
            for($i=0;$i<=$tabs;$i++) echo "\t";
            echo "<link type=\"text/css\" href=\"".Environment::getVariable('baseUri').$link."\" rel=\"stylesheet\" />";
            echo "\n";
        }
    }

    static private function addJS($link,$tabs=0){
        if(file_exists(WWW_DIR."/".$link)){
            for($i=0;$i<=$tabs;$i++) echo "\t";
            echo "<script type=\"text/javascript\" src=\"".Environment::getVariable('baseUri').$link."\"></script>";
            echo "\n";
        }
    }
}

jQueryUILoader::addModule("ui.core");
jQueryUILoader::addModule("ui.datepicker");
jQueryUILoader::addModule("ui.tabs");
jQueryUILoader::addModule("ui.sortable");