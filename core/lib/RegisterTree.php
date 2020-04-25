<?php
namespace core\lib;

class RegisterTree
{

    private static $objects=array();
    
    public static function get($name)
    {
        return self::$objects[$name];
    }

    public static function set($alias,$object){
        self::$objects[$alias]=$object;
    }

    public static function remove($alias){
        unset(self::$objects[$alias]);
    }
}