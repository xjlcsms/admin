<?php

namespace Ku\Captcha;

class Colored extends \Ku\Captcha\CaptchaAbstract {

    public function exec() {
        $this->randStr()->write();

        return $this;
    }

    /**
     * 随机字符串
     *
     * @return \Ku\Captcha\CaptchaString
     */
    protected function randStr(){
        $len = (int)($this->getCaptcha()->getRandLength());

        if($len >= 4 && $len < 16){
            $code = array();
            $char = \Ku\Captcha\Resource::getChar();

            for($i = 0; $i < $len; $i++){
                $code[] = $char[array_rand($char)];
            }

            $this->_code   = $code;
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
        $count     = count($code);
        $pWvalue   = round($captcha->getWidth()/$count) - $count;
        $imageRepo = $captcha->getImageRepo();
        $randFont  = $captcha->calFontSize();
        $size      = mt_rand($randFont['min'], $randFont['max']);
        $randItem  = array();
        $colors    = \Ku\Captcha\Resource::getColors();

        for($i = 0; $i < $count; $i++){
            $x = mt_rand($pWvalue*$i + 1, $pWvalue*($i+1) - 2);
            $y = mt_rand($size, $size + ((int)($size/2) - 1));

            $randIndex = array_rand($colors);
            $randItem[] = $randIndex;
            $rColor = $colors[$randIndex];
            $fColor = imagecolorallocate($imageRepo, $rColor[0], $rColor[1], $rColor[2]);

            imagettftext($imageRepo, $size, 0, $x, $y, $fColor, $captcha->randFontType(), $code[$i]);
        }

        if(!empty($randItem)){
            $this->_append = $randItem[array_rand($randItem)];

            foreach ($randItem as $key => $val){
                $this->_code = array();

                if($val == $this->_append){
                    $this->_code[] = $code[$key];
                }
            }
        }

        $captcha->setImageRepo($imageRepo);

        unset($captcha);
    }

}