<?php

namespace Ku\Thumb\Gif\Std;

class Extension {

    protected $string = array();

    public function toString(){
        foreach ($this as $extensions){
            if($extensions instanceof \stdClass){
                $this->handle($extensions);
            }
        }

        return implode($this->string);
    }

    protected function handle($extensions){
        foreach($extensions as $extension){
            if($extension instanceof \stdClass){
                $this->handle($extension);
            }else{
                $this->string[] = pack("C*", $extension);
            }
        }
    }
}
