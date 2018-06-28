<?php

namespace Ku\Captcha;

class Arithmetic extends \Ku\Captcha\CaptchaAbstract {

    protected $_type = array('+', '-');
    protected $_symbol = array(
        '-' => array('-'),
        '+' => array('+'),
        'x' => array('x', 'X'),
        '÷' => array('÷')
    );

    public function exec() {
        $this->randArithmetic()->write();

        return $this;
    }

    protected function randArithmetic(){
        $rand1 = $this->randInt();
        $rand2 = $this->randInt();
        $calcu = $this->randCalculation();

        $calResult = -1;
        $items = array();

        switch ($calcu){
            case '-':
                    $calResult = ($rand2 > $rand1) ? $rand2 - $rand1 : $rand1 - $rand2;
                    $items[] = ($rand2 > $rand1) ? $rand2 : $rand1;
                    $items[] = $this->getString('-');
                    $items[] = ($rand2 > $rand1) ? $rand1 : $rand2;
                break;
            case '+':
            default :
                    $calResult = $rand1 + $rand2;
                    $items[] = $rand1;
                    $items[] = $this->getString('+');
                    $items[] = $rand2;
                break;
        }

        $this->_result = $calResult;
        $this->_code = $items;

        return $this;
    }

    /**
     * 符号处理
     */
    protected function getString($symbol){
        if(isset($this->_symbol[$symbol])){
            $arr = $this->_symbol[$symbol];

            return $arr[array_rand($arr)];
        }

        throw new \Exception('符号组设置错误');
    }

    /**
     * 获取随机整数　
     *
     * @return int
     */
    protected function randInt(){
        return mt_rand(1, 50);
    }

    /**
     * 随机计算符
     *
     * @return string
     */
    protected function randCalculation(){
        return $this->_type[array_rand($this->_type)];
    }

    /**
     * 文本写入图像资源
     */
    protected function write() {
        $code      = $this->getCode();
        $captcha   = $this->getCaptcha();
        $randColor = $captcha->randFontColor();
        $randFont  = $captcha->calFontSize();
        $imageRepo = $captcha->getImageRepo();
        $size      = mt_rand($randFont['min'], $randFont['max']);
        $agle      = mt_rand(-2, 2);

        $w = $captcha->getWidth();
        $h = (int)($captcha->getHeight()/2);
        $x = mt_rand(0, $w - ($size*3) - 4);
        $y = mt_rand($h+1, $h + 16);

        imagettftext($imageRepo, $size, $agle, $x, $y, $randColor, $captcha->randFontType(), implode($code));

        $captcha->setImageRepo($imageRepo);

        unset($captcha);
    }

}