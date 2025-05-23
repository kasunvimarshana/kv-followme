<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChainableExe1 extends Model
{
    //
    private $instance = null;
    public function __construct(){
        $pars = func_get_args();
        $this->instance = is_object($obj=array_shift($pars))?$obj:new $obj($pars);
    }

    public function __call($name,$pars){
        call_user_func_array([$this->instance,$name],$pars);
        return $this;
    }
}

/*
$test = new ChainableExe1('Test');
$test->foo()->baz()->bar();

Output:
    FOOBAR
*/
