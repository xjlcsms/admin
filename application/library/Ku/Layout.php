<?php

namespace Ku;

/**
 * 根据 Yaf的View接口, 模拟实现的Layout的功能.
 */
class Layout implements \Yaf\View_Interface {

    /**
     * @var View
     */
    public $engine = null;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var string
     */
    protected $layoutPath;

    /**
     * Layout的名称, 最终会和layoutPath拼接在一起.
     *
     * @var string
     */
    protected $layout;

    /**
     * 渲染后的视图数据.
     *
     * @var string
     */
    protected $yield;

    /**
     * 模板中的变量, 嗯, Yaf\View 中就是这样名称.
     *
     * @var array
     */
    protected $tpl_vars = array();

    /**
     * 当前视图引擎的模板文件基目录
     *
     * @var string
     */
    protected $tpl_dir;

    /**
     * @param string $path      会触发设置LayoutPath.
     * @param array  $options   key/value
     *
     * @return void
     */
    public function __construct($path = null, $options = array()) {
        $this->setLayoutPath($path);
        $this->options = $options;
    }

    /**
     * @return View
     */
    protected function engine() {
        if (!$this->engine) {
            $this->engine = new View($this->tpl_dir, $this->options);
        }

        return $this->engine;
    }

    /**
     * 设置模板文件的基目录, 同时也会触发Layout的设置
     *
     * @param string $path 设置模板文件的基目录
     *
     * @return boolean
     * @throws \Exception    如果模板目录不可读, 将会抛出异常
     */
    public function setScriptPath($path) {
        if (is_readable($path)) {
            $this->tpl_dir = $path;
            $this->engine()->setScriptPath($path);
            return true;
        }

        throw new \Exception("Invalid path: {$path}", 1001);
    }

    /**
     * @return string
     */
    public function getScriptPath() {
        return $this->engine()->getScriptPath();
    }

    /**
     * Layout的名称.
     * <b>注意: 不需要后缀名, 程序会自动根据application.view.ext的配置自动拼接后缀名.</b>
     *
     * @param string $name Layout的名称, 不需要后缀名.
     *
     * @return void
     */
    public function setLayout($name) {
        $this->layout = $name;
    }

    /**
     * @return string
     */
    public function getLayout() {
        return $this->layout;
    }

    /**
     * 设置 Layout 和目录, 如果目录不可读取, 将会抛出异常
     *
     * @param string $path
     * @return boolean
     * @throws \Exception
     */
    public function setLayoutPath($path) {
        if (is_readable($path)) {
            $this->layoutPath = $path;
            return true;
        }

        throw new \Exception("Invalid layout path: {$path}", 1002);
    }

    /**
     * @return string
     */
    public function getLayoutPath() {
        $viewExt = \Yaf\Application::app()->getConfig()->get('application.view.ext');
        return $this->layoutPath . DIRECTORY_SEPARATOR . $this->layout . '.' . $viewExt;
    }

    /**
     * Assign a variable to the template
     *
     * @param string $name  The variable name.
     * @param mixed  $value The variable value.
     *
     * @return void
     */
    public function __set($name, $value) {
        $this->assign($name, $value);
    }

    /**
     *
     * @param string $name
     * @return mixed|null
     */
    public function __get($name = null) {
        return isset($this->tpl_vars[$name]) ? $this->tpl_vars[$name] : false;
    }

    /**
     * Allows testing with empty() and isset() to work
     *
     * @param string $name
     *
     * @return boolean
     */
    public function __isset($name) {
        return (null !== $this->engine()->$name);
    }

    /**
     * Allows unset() on object properties to work
     *
     * @param string $name
     *
     * @return void
     */
    public function __unset($name) {
        $this->engine()->clear($name);
    }

    /**
     * Assign variables to the template
     *
     * Allows setting a specific key to the specified value, OR passing
     * an array of key => value pairs to set en masse.
     *
     * @see __set()
     *
     * @param string|array $name  The assignment strategy to use (key or
     *                            array of key => value pairs)
     * @param mixed        $value (Optional) If assigning a named variable,
     *                            use this as the value.
     *
     * @return boolean
     */
    public function assign($name, $value = null) {
        $this->tpl_vars[$name] = $value;
        return $this->engine()->assign($name, $value);
    }

    /**
     * Assign variables by reference to the template
     *
     */
    public function assignRef($name, &$value) {
        $this->tpl_vars[$name] = $value;

        $this->engine()->assignRef($name, $value);
    }

    /**
     * Clear all assigned variables
     *
     * Clears all variables assigned to Yaf\View either via
     * {@link assign()} or property overloading
     * ({@link __get()}/{@link __set()}).
     *
     * @return void
     */
    public function clearVars() {
        $this->tpl_vars = array();
        $this->engine()->clear();
    }

    /**
     * Processes a view and returns the output.
     *
     * This method called once from controller to render the given view.
     * So render the view at $this->yield property and then render the
     * layout template.
     *
     * @param string $tpl      The template to process.
     * @param array  $tpl_vars Additional variables to be assigned to template.
     *
     * @return string The view or layout template output.
     */
    public function render($tpl, $tpl_vars = array()) {

        $tpl_vars = array_merge($this->tpl_vars, $tpl_vars);
        $this->engine()->setLayoutPath($this->layoutPath);

        $this->yield = $this->engine()->render($tpl, $tpl_vars);

        // if no layout is defined,
        // return the rendered view template
        if (null == $this->layout) {
            return $this->yield;
        }


        $ref  = new \ReflectionClass($this->engine());
        $prop = $ref->getProperty('_tpl_vars');
        $prop->setAccessible(true);

        $view_vars = $prop->getValue($this->engine());
        $tpl_vars  = array_merge($tpl_vars, $view_vars);

        $tpl_vars['yield'] = $this->yield;

        return $this->engine()->render($this->getLayoutPath(), $tpl_vars);
    }

    /**
     * Directly display the constens of a view / layout template.
     *
     * @param string $tpl      The template to process.
     * @param array  $tpl_vars Additional variables to be assigned to template.
     *
     * @return void
     */
    public function display($tpl, $tpl_vars = array()) {
        echo $this->render($tpl, $tpl_vars);
    }

}
