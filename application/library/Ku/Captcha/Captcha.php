<?php

namespace Ku\Captcha;


class Captcha {

    protected static $_instance = null;
    protected $_captcha  = null;
    protected $_appendMsg = null;
    protected $captchaType = 'randchar';
    protected $captchaTypeArr = array(
        'randchar',   // 随机字符
        'arithmetic', // 四则运算
        'colored'     // 着色部份
     );

    protected $imageRepo  = null;
    protected $interfere  = null;
    protected $randLength = 4;

    protected $width = 85;
    protected $height = 27;

    public function __construct() {}

    /**
     * @return \Ku\Captcha\Captcha
     */
    public static function getInstance(){
        if(!self::$_instance instanceof self)
            self::$_instance = new self();

        return self::$_instance;
    }

    /**
     * 设置图像宽度
     *
     * @param int $w
     */
    public function setWidth($w){
        $this->width = (int)$w;

        return $this;
    }

    /**
     * 图像宽度
     *
     * @return int
     */
    public function getWidth(){
        return $this->width;
    }

    /**
     * 设置图像高度
     *
     * @param int $h
     */
    public function setHeight($h){
        $this->height = (int)$h;

        return $this;
    }

    /**
     * 图像高度
     *
     * @return int
     */
    public function getHeight(){
        return $this->height;
    }

    /**
     * 确认展示类型
     *
     * @param string $type <br />
     *  randchar     随机字符 <br />
     *  arithmetic   四则运算 <br />
     *  colored'     着色部份 <br />
     *  default: string <br />
     * @return \Captcha
     */
    public function setCaptchaType($type){
        if(in_array($type, $this->captchaTypeArr)){
            $this->captchaType = (string)(trim($type));
        }

        return $this;
    }

    /**
     * 随机字符串时长度
     *
     * @param int $len
     * @return \Captcha
     */
    public function setRandLength($len){
        $this->randLength = (int)$len;

        return $this;
    }

    /**
     * 随机字符串时长度
     *
     * @return int $len
     */
    public function getRandLength(){
        return $this->randLength;
    }

    /**
     * 设置干扰素类型
     *
     * @param string|array $interfere [line, pixel, arc]
     */
    public function setInterfere($interfere){
        $this->interfere = $interfere;

        return $this;
    }

    /**
     * 图像资源
     *
     * @return GD INT
     */
    public function getImageRepo(){
        return $this->imageRepo;
    }

    /**
     * 设置图像资源
     *
     * @param type $imageRepo
     * @return \Captcha
     */
    public function setImageRepo($imageRepo){
        $this->imageRepo = $imageRepo;

        return $this;
    }

    /**
     * 创建验证码图
     */
    public function create(){
        if (!$this->imageRepo)
            $this->CreateImageResources ();

        $this->interfere();

        $captchaType = '\\Ku\\Captcha\\' . ucfirst($this->captchaType);
        $captchInstance = (new $captchaType())->exec();
        $this->_captcha = $captchInstance->getResult();
        $this->_appendMsg = $captchInstance->getAppendMsg();

        return $this;
    }

    /**
     * 获取已创建验证码图
     */
    public function show(){
        header("Content-type: image/png");
        imagepng($this->imageRepo);
	imagedestroy($this->imageRepo);
    }

    /**
     * 获取已创建验证码结果
     */
    public function getCaptcha(){
        return (string)$this->_captcha;
    }

    /**
     * 计算当前适用的字体大小
     */
    public function calFontSize(){
        $min = (int)($this->height*0.4114); // 13/default height 27
        $max = (int)($this->height*0.6167); // 18/default height 27

        return array('min' => $min, 'max' => $max);
    }

    /**
     * 随机字体顔色
     *
     * @return \GD  int
     */
    public function randFontColor(){
        static $randfg = null;

        if($randfg === null){
            $fontColors = \Ku\Captcha\Resource::getFontColors();
            $randIndex  = mt_rand(0, count($fontColors) - 1);
            $randColor  = isset($fontColors[$randIndex]) ? $fontColors[$randIndex] : array(0x00, 0x00, 0x00);
            $randfg     = imagecolorallocate($this->imageRepo, $randColor[0], $randColor[1], $randColor[2]);
        }

        return $randfg;
    }

    /**
     * 随机字体
     *
     * @return string
     */
    public function randFontType(){
        static $randFt = array();

        $fontTypeArr = \Ku\Captcha\Resource::getFontType();
        $randIndex   = mt_rand(0, count($fontTypeArr) - 1);

        if(!isset($randFt[$randIndex])){
            $fontType = $fontTypeArr[$randIndex];
            $randFt[$randIndex] = APPLICATION_PATH . '/data/fonts/' . $fontType . '.ttf';
        }

        return $randFt[$randIndex];
    }

    /**
     * 设置干扰素
     */
    protected function interfere(){
        $interfere = $this->interfere;
        $randColor = $this->randFontColor();

        if(empty($interfere))
            return false;

        if(is_string($interfere))
            $interfere = array($interfere);

        foreach ($interfere as $set){
            if(in_array($set, array('line', 'pixel', 'arc'))){
                if(method_exists($this, $set)){
                    $this->{$set}($randColor);
                }
            }
        }
    }

    /**
     * 创建图片资源
     *
     * @return \Captcha
     */
    protected function CreateImageResources() {
        $this->imageRepo = imagecreatetruecolor($this->width, $this->height);

        $bgColor  = imagecolorallocate($this->imageRepo, 0xFF, 0xFF, 0xFF);

        imagefilledrectangle($this->imageRepo, 0, 0, $this->width, $this->height, $bgColor);

        imagefill($this->imageRepo, 0, 0, $bgColor);
    }

    /**
     * 点状干扰素
     *
     * @param int $setColor
     * @param int $size
     */
    protected function pixel($setColor, $size = 100){
        $hVal = $this->height;
        $wVal = $this->width;
        $size = ($size > 0) ? (int)$size : 100;

        for($i = 0; $i < $size; $i++){
            imagesetpixel($this->imageRepo, rand(0, $wVal) , rand(0, $hVal) , (int)$setColor);
        }
    }

    /**
     * 弧线状干扰素
     *
     * @param int $setColor
     * @param int $size
     */
    protected function arc($setColor, $size = 1){
       // todo
    }

    /**
     * 线条状干扰素s
     *
     * @param int $setColor
     * @param int $size
     */
    protected function line($setColor, $size = 1){
        $size = ($size > 0 && $size < 5) ? (int)$size : 1;
        $hVal = $this->height;
        $wVal = $this->width;

        for($i = 0; $i < $size; $i++){
            imageline($this->imageRepo, 0, mt_rand(0, $hVal), $wVal, mt_rand(0, $hVal), (int)$setColor);
        }
    }

}