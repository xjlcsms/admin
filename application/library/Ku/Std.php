<?php

namespace Ku;

final class Std extends \stdClass {

    public function __construct(array $from = array()) {
        foreach ($from as $key => $value) {
            $this->set($key, $value);
        }
    }

    private function __clone() {}
    private function __sleep() {}

    public function __set($name, $value) {
        if(preg_match('/^[a-z][a-zA-Z0-9\_]*$/', $name)) {
            $this->{$name} = $value;
        }
    }

    public function __get($name) {
        return (isset($this->{$name}) ? $this->{$name} : '');
    }

    /**
     *  提供get*方法
     *
     * @param string $name
     * @param string $arguments
     * @return \Ku\Std|null
     */
    public function __call($name, $arguments = null) {
        $ret = null;

        if (strpos($name, 'get') === 0 && empty($arguments)) {
            $name = strtolower(trim(str_replace('get', '', $name)));

            $ret = $this->get($name);
        }

        return $ret;
    }

    /**
     *
     * @param strig $name
     * @param string|array|boolean|number $value
     * @return \My\Std
     */
    public function set($name, $value){
        $this->{$name} = $value;

        return $this;
    }

    /**
     *
     * @param strig $name
     * @return string|array|boolean|number $value
     */
    public function get($name){
        return $this->{$name};
    }

    /**
     * 属性转成数组
     *
     * @return \array
     */
    public function toArray(){
        $items = array();

        foreach ($this as $key => $value){
            $items[$key] = $value;
        }

        return $items;
    }
}