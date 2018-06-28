<?php

namespace Ku\Thumb\Gif\Std;

class Frame  {

    public $offestX = null;
    public $offestY = null;
    public $imageW  = null;
    public $imageH  = null;
    public $localCt = null;
    public $tbiData = null;

    protected $_data = null;

    /**
     *  pow(2, n), 0 < n < 12
     * @var array
     */
    protected $powRoot = array(1, 2, 4, 8, 16, 32, 64, 128, 256, 512, 1024, 2048, 4096);

    public function getSrcImage($globalCtTable){
        $data = $this->decode(implode($this->tbiData->imageData));

        $im = imagecreatetruecolor($this->imageW, $this->imageH);
        $co = ($this->localCt->flag) ? $this->localCt->table : $globalCtTable;
        $i  = 0;

        for ($y = 0; $y < $this->imageH; $y++) {
            for ($x = 0; $x < $this->imageW; $x++) {
                $colors = unpack("C", $data[$i]);
                $color  = $co[$colors[1]];
                imagesetpixel($im, $x, $y, imagecolorallocate($im, $color['r'], $color['g'], $color['b']));
                $i++;
                exit();
            }
        }

        header("Content-type: image/gif");
        imagegif($im);
        exit();
    }

    /**
     * 交织图像光栅数据转连续方式
     *
     * @return string
     */
    protected function interlaceData(){

    }

    /**
     *  编码数据块
     *  基于lzw算法
     */
    public function encode($data){

    }

    /**
     *  解码数据块
     *  基于lzw算法
     */
    public function decode($data){
        $LZW_Minimum = (int)$this->tbiData->LZW_Minimum + 1;
        $initSizeMax = $this->powRoot[$LZW_Minimum];
        $lzw_clear   = $initSizeMax + 1;
        $lzw_end     = $lzw_clear + 1;
        $position    = $lzw_end + 1;

        $stringTable = array();
        $strStream   = array();

        for($i = 0; $i < $initSizeMax; $i++){
            $stringTable[$i] = pack("C", $i);
        }
        $stringTable[$lzw_clear] = $lzw_clear;
        $stringTable[$lzw_end]   = $lzw_end;

        $gifResource = new \Ku\Thumb\Gif\Buffer\Bits($data, 1);

        $readBit   = $LZW_Minimum;
        $readTable = $stringTable;
        $prefixStr = '';
        $readPoint = $position;

        while ($code = $gifResource->read($readBit)){
            if($code === $lzw_end)
                continue;

            if($code === $lzw_clear){
                $readBit   = $LZW_Minimum;
                $readTable = $stringTable;
                $readPoint = $position;
                continue;
            }

            if($code < $readPoint){
                $newStr = $readTable[$code];
                $strStream[] = $prefixStr . $newStr;
            }else{
                $newStr = $prefixStr . $code;
                $strStream[] = $newStr;
            }

            $readTable[$readPoint] = $newStr;
            $prefixStr = $code;
            $readPoint++;

            if($code === $this->powRoot[$readBit]){
                $readBit++;
            }
        }

        return unpack("C*", implode($strStream));
    }
}