<?php

namespace Ku\Thumb\Gif\Structures;

class Header extends \Ku\Thumb\Gif\Structures\StructuresAbstract {

    protected $signature = null;
    protected $version   = null;

    /**
     * GIF 署名
     *
     * @return string
     */
    public function readSignature(){
        if($this->signature === null)
            $this->signature = $this->getResource()->read(3);

        return $this->signature;
    }

    /**
     * GIF 版本号
     *
     * @return string
     */
    public function readVersion(){
        if($this->version === null)
            $this->version = $this->getResource()->read(3);

        return $this->version;
    }

    public function toString() {
        return implode(array($this->signature, $this->version));
    }
}

