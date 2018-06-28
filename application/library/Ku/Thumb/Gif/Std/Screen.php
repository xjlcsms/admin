<?php

namespace Ku\Thumb\Gif\Std;

class Screen {

    /**
     * 缩略宽比例 0-1
     *
     * @var float
     */
    protected $radioW = 1;

    /**
     * 缩略高比例 0-1
     *
     * @var float
     */
    protected $radioH = 1;

    /**
     * 缩略宽比例
     *
     * @param float $w
     * @return \Ku\Thumb\Gif\Std\Screen
     */
    public function setRadioW($w){
        $this->radioW = (float)$w;

        return $this;
    }

    /**
     * 缩略高比例
     *
     * @param float $h
     * @return \Ku\Thumb\Gif\Std\Screen
     */
    public function setRadioH($h){
        $this->radioH = (float)$h;

        return $this;
    }

    public function toString(){
        $screen = $this;
        $data = pack("C*", round($screen->srcW * $this->radioW));
        $data .= pack("C*", round($screen->srcH * $this->radioH));
        $data .= pack("C*", bindec(implode(array(
            $screen->globalCt->flag,
            $screen->globalCt->resolution,
            $screen->globalCt->sort,
            $screen->globalCt->pixelSize
        ))));

        $data .= pack("C*", $screen->blackground);
        $data .= pack("C*", $screen->aspect_radio);

        foreach ($screen->globalCt->table as $globalTable){
            $data .= pack("C*", implode($globalTable));
        }

        return $data;
    }
}

