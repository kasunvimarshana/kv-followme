<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConverterModel extends Model
{
    //
    public static function toObject(array $array, $object){
        $class = get_class($object);
        $methods = get_class_methods($class);
        foreach ($methods as $method) {
            preg_match(' /^(set)(.*?)$/i', $method, $results);
            $pre = $results[1]  ?? '';
            $k = $results[2]  ?? '';
            $k = strtolower(substr($k, 0, 1)) . substr($k, 1);
            If ($pre == 'set' && !empty($array[$k])) {
                $object->$method($array[$k]);
            }
        }
        return $object;
    }
    
    public static function toArray($object){
        $array = array();
        $class = get_class($object);
        $methods = get_class_methods($class);
        foreach ($methods as $method) {
            preg_match(' /^(get)(.*?)$/i', $method, $results);
            $pre = $results[1]  ?? '';
            $k = $results[2]  ?? '';
            $k = strtolower(substr($k, 0, 1)) . substr($k, 1);
            If ($pre == 'get') {
                $array[$k] = $object->$method();
            }
        }
        return $array;
    }
    
    function object_to_array($obj) {
        if(is_object($obj)) $obj = (array) dismount($obj);
        if(is_array($obj)) {
        $new = array();
            foreach($obj as $key => $val) {
                $new[$key] = object_to_array($val);
            }
        }
        else $new = $obj;
        return $new;
    } 
    
    function dismount($object) {
        $reflectionClass = new ReflectionClass(get_class($object));
        $array = array();
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($object);
            $property->setAccessible(false);
        }
        return $array;
    }
}
