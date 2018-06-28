<?php

namespace Ku\Captcha;

class Randchar extends \Ku\Captcha\CaptchaAbstract {

    public function exec() {
        $this->randStr()->write();

        return $this;
    }

    /**
     * 随机字符串
     *
     * @return \Ku\Captcha\Randchar
     */
    protected function randStr(){
        $len = (int)($this->getCaptcha()->getRandLength());

        if($len >= 4 && $len < 16){
            $code = array();
            $char = \Ku\Captcha\Resource::getChar();

            for($i = 0; $i < $len; $i++){
                $code[] = $char[array_rand($char)];
            }

            $this->_code = $code;
            $this->_result = implode($code);
        }

        return $this;
    }

    /**
     * 文本写入图像资源
     */
    protected function write() {
        $code      = $this->getCode();
        $captcha   = $this->getCaptcha();
        $randColor = $captcha->randFontColor();
        $randFont  = $captcha->calFontSize();
        $count     = count($code);
        $pWvalue   = round($captcha->getWidth()/$count);
        $pHvalue   = $captcha->getHeight();
        $imageRepo = $captcha->getImageRepo();

        for($i = 0; $i < $count; $i++){
             $size  = mt_rand($randFont['min'], $randFont['max']);
             $agle  = mt_rand(-8, 8);

             $xMin = $pWvalue*$i;
             $xMax = $pWvalue*($i+1) - (int)(($size >= $pWvalue ? $pWvalue : $size));

             $x = mt_rand($xMin, $xMax - 1);
             $y = mt_rand($size, $size + (int)($pHvalue/2) - 8);

            imagettftext($imageRepo, $size, $agle, $x, $y, $randColor, $captcha->randFontType(), $code[$i]);
        }

        $captcha->setImageRepo($imageRepo);

        unset($captcha);
    }

}