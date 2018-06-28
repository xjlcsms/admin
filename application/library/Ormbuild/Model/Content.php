<?php

namespace Ormbuild\Model;

final class Content {

    protected static $_instance = null;
    private $_columns           = null;
    private $_tableName         = null;
    private $_tab               = null;
    
    /**
     * @var \Model\Tablestruct
     */
    private $_tableInfo = null;

    /**
     * 未在配置内的将默认为 string
     *
     * @var array
     */
    private $_typeArr = array(
        'int'       => 'int',
        'tinyint'   => 'int',
        'smallint'  => 'int',
        'mediumint' => 'int',
        'bigint'    => 'int',
        'double'    => 'float',
        'float'     => 'float',
        'decimal'   => 'float'
    );

    public function __construct() {
        $this->_tab = \Ormbuild\Lib\Options::getInstance()->getTab();
    }

    /**
     * 单例
     *
     * @return \Lib\Modelcontents
     */
    public static function getInstance() {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * @param string $tableName
     * @return \Lib\Modelfile
     */
    public function setTableName($tableName) {
        $this->_tableName = (string)(strtolower($tableName));

        return $this;
    }
    
    /**
     * @param \Ormbuild\Model\Tablestruct|array $tableInfo
     * @return \Ormbuild\Lib\Modelfile
     */
    public function setTableInfo($tableInfo) {
        if(!$tableInfo instanceof \Ormbuild\Model\Tablestruct) {
            $tableInfo = new \Ormbuild\Model\Tablestruct($tableInfo);
        }
        
        $this->_tableInfo = $tableInfo;

        return $this;
    }

    /**
     * @param string $columns
     * @return \Ormbuild\Lib\Modelcontents
     */
    public function setColumns($columns) {
        $this->_columns = (array)$columns;

        return $this;
    }

    /**
     * 重置
     */
    public function reset() {
        $this->_columns = null;
    }

    public function build() {
        $columns = $this->_columns;

        if (empty($columns)) {
            throw new \Ormbuild\Lib\Exception('not found any columns');
        }

        $buffer  = \Ormbuild\Model\Buffer::getInstance();
        $build   = \Ormbuild\Model\Build::getInstance();
        $options = \Ormbuild\Lib\Options::getInstance();
        $items   = array();
        if(!empty($this->_tableInfo) && $this->_tableInfo instanceof \Ormbuild\Model\Tablestruct) {
            $tableInfo    = $this->_tableInfo;
            $tableComment = array(
                $tableInfo->getTable_comment(),
                '',
                '@Table Schema: ' . $tableInfo->getTable_schema(),
                '@Table Name: ' . $tableInfo->getTable_name()
            );
            
            $buffer->pushHeader($build->toComment($tableComment, false));
        }

        $namespace = ltrim(trim($options->getNamespace(), '\\'), '_');

        if (!empty($namespace) && $options->getOnNamespace() === true) {
            $buffer->pushHeader('namespace ' . $namespace . ';');
            $buffer->pushHeader(''); // 增加一空行,处理代码格式
            $namespace = '';
        }

        $buffer->pushClass($build->toClass($namespace . ucfirst($this->_tableName)));
        $buffer->pushProperty($build->toComment(array('Params','','@var array'))); /*_params*/
        $buffer->pushProperty($build->toProperty('_params', null));
        $buffer->pushFunc($build->toComment(array('Params','','Column Type: array','Default: null','','@var array')));
        $buffer->pushFunc($build->toGetFunc('params', array(str_repeat($this->_tab, 2) . 'return $this->_params;')));
        foreach ($columns as $column) {
            $struct  = new \Ormbuild\Model\Columnstruct($column);
            $colName = $struct->getColumn_name();

            if (empty($colName)) {
                continue;
            }
            if($colName == 'params'){
                throw new \Ormbuild\Lib\Exception($tableInfo->getTable_name().'中的[params] 为生成模型保留字段，建议修改该表的字段名');
            }

            \Ormbuild\Lib\State::notice('    Parsing [' . $colName . ']...', false);
            $result = $this->parseColumn($struct);
            \Ormbuild\Lib\State::notice('\t' . ($result === false ? 'failed' : 'OK'));

            $items[] = $colName;
        }

        $buffer->pushToArray($build->toComment(array('Return a array of model properties', '', '@return array')));
        $buffer->pushToArray($build->toToArray($items));
    }

    /**
     * 解析 columns
     *
     * @param \Ormbuild\Model\Columnstruct $struct
     */
    private function parseColumn(\Ormbuild\Model\Columnstruct $struct) {
        $name = $struct->getColumn_name();

        if (\Ormbuild\Lib\Options::getInstance()->getColunderline() === false) {
            $name = trim(str_replace('_', '', $name));
        }

        $commentArr = $this->buildCommonComments($struct);

        $this->buildPropertyContent($struct, $commentArr);
        $this->buildSetfuncContent($struct, $commentArr);
        $this->buildGetfuncContent($struct, $commentArr);

        return true;
    }

    /**
     * 转成字符串按顺序合并
     *
     * @return string
     */
    public function toString() {
        $buffer  = \Ormbuild\Model\Buffer::getInstance();
        $options = \Ormbuild\Lib\Options::getInstance();

        $items   = array();
        \Ormbuild\Lib\State::notice('Building php head');
        $items[] = $buffer->pullHeader();

        $namespace = ($options->getOnNamespace() === false) ? $options->getNamespace() : '';

        \Ormbuild\Lib\State::notice('Building class [' . $namespace . sprintf($options->getModelType(), ucfirst($this->_tableName)) . ']');
        $items[] = $buffer->pullClass();

        \Ormbuild\Lib\State::notice('Building class property');
        $items[] = $buffer->pullProperty();

        \Ormbuild\Lib\State::notice('Building class function');
        $items[] = $buffer->pullFunc();
        $items[] = $buffer->pullToArray();

        $items[] = $buffer->pullEnd();

        $buffer->clearAll();

        return implode("\n", $items);
    }

    /**
     * 创建属性内容
     *
     * @param \Model\Columnstruct $struct
     * @param array $commentArr
     */
    protected function buildPropertyContent(\Ormbuild\Model\Columnstruct $struct, array $commentArr) {
        $build  = \Ormbuild\Model\Build::getInstance();
        $buffer = \Ormbuild\Model\Buffer::getInstance();

        $commentArr[] = '@var ' . $this->getDateType($struct->getData_type());

        $buffer->pushProperty($build->toComment($commentArr));
        $buffer->pushProperty($build->toProperty('_' . $struct->getColumn_name(), $struct->getColumn_default()));
    }

    /**
     * 创建set方法内容
     *
     * @param \Model\Columnstruct $struct
     * @param array $commentArr
     */
    protected function buildSetfuncContent(\Ormbuild\Model\Columnstruct $struct, array $commentArr) {
        $build    = \Ormbuild\Model\Build::getInstance();
        $buffer   = \Ormbuild\Model\Buffer::getInstance();
        $options  = \Ormbuild\Lib\Options::getInstance();
        $name     = $struct->getColumn_name();
        $dataType = $this->getDateType($struct->getData_type());

        $commentArr[] = '@param ' . $dataType . ' $' . $name;
        $commentArr[] = '@return ' . ltrim($options->getNamespace(), '_')
                . sprintf($options->getModelType(), ucfirst($this->_tableName));

        $buffer->pushFunc($build->toComment($commentArr));
        $buffer->pushFunc($build->toSetFunc($name, array(
            str_repeat($this->_tab, 2) . '$this->_' . $name . ' = (' . $dataType . ')$' . $name . ';',
            str_repeat($this->_tab, 2) . '$this->_params[\'' . $name .'\']' . ' = (' . $dataType . ')$' . $name . ';',
            str_repeat($this->_tab, 2) . 'return $this;'
        ), $name));
    }

    /**
     * 创建get方法内容
     *
     * @param \Model\Columnstruct $struct
     * @param array $commentArr
     */
    protected function buildGetfuncContent(\Ormbuild\Model\Columnstruct $struct, array $commentArr) {
        $build  = \Ormbuild\Model\Build::getInstance();
        $buffer = \Ormbuild\Model\Buffer::getInstance();
        $name   = $struct->getColumn_name();

        $commentArr[] = '@return ' . $this->getDateType($struct->getData_type());

        $buffer->pushFunc($build->toComment($commentArr));
        $buffer->pushFunc($build->toGetFunc($name, array(str_repeat($this->_tab, 2) . 'return $this->_' . $name . ';')));
    }

    /**
     * 创建公共注释部分
     *
     * @param \Model\Columnstruct $struct
     * @return array
     */
    protected function buildCommonComments(\Ormbuild\Model\Columnstruct $struct) {
        $extra      = $struct->getExtra();
        $key        = $struct->getColumn_key();
        $default    = $struct->getColumn_default();
        $commentArr = array();

        $commentArr[] = $struct->getColumn_comment();
        $commentArr[] = '';
        $commentArr[] = 'Column Type: ' . $struct->getColumn_type();

        if (mb_strlen($default) > 0) {
            $commentArr[] = 'Default: ' . $struct->getColumn_default();
        }

        if (!empty($extra)) {
            $commentArr[] = $extra;
        }

        if (!empty($key)) {
            $commentArr[] = $key;
        }

        $commentArr[] = '';

        return $commentArr;
    }

    /**
     * 数据类型转换
     *
     * @param string $type
     * @return string
     */
    protected function getDateType($type) {
        return (isset($this->_typeArr[$type])) ? $this->_typeArr[$type] : 'string';
    }

}
