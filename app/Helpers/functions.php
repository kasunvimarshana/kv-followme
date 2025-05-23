<?php
/*
css helper function
*/
if(!function_exists('set_active')){
    function set_active($path, $active = 'active'){
        return call_user_func_array('Request::is', (array) $path) ? $active : '';
    }
}

if(!function_exists('arrayToObject')){
    function arrayToObject($array){
        //$myArray = json_decode(json_encode($object), true);
        //(object) $array;
        if (!is_array($array)) {
            return $array;
        }

        $object = new stdClass();
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name=>$value) {
                $name = strtolower(trim($name));
                if (!empty($name)) {
                    $object->$name = arrayToObject($value);
                }
            }
            return $object;
        }
        else {
            return false;
        }
    }
}

/*
function remove_element($array,$value) {
  return array_diff($array, (is_array($value) ? $value : array($value)));
}
*/

?>