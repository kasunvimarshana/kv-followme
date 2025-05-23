<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChainableExe2 extends Model
{
    //
    private $_returns = [];

    // Retrieve the returned value from last
    // chained method
    public function _get_return(&$var){
        $var = count($this->_returns)?
                  array_pop($this->_returns):null;
        return $this;
    }

    // Clear the returned value cache
    public function _reset(){
        $this->_returns = [];
        return $this;
    }

    // Redefine call proxy for saving returned values
    public function __call($name,$pars){
        ($r=call_user_func_array([$this->instance,$name],$pars))?$this->_returns[]=$r:null;
        return $this;
    }
}

/*
$test = new ChainableExe2('Test');
$test
    ->foo()
    ->baz()->_get_return($the_value)
    ->bar();

echo "\n\n",'The value was : ',$the_value;
Output:
    FOOBAR
    The value was : BAZ
*/