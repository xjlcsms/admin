<?php
/**
 *
 *@return \Business\QueueModel
**/
namespace Business;

final class QueueModel extends \Business\AbstractModel {
    use \Base\Model\InstanceModel;
    
    protected $_data    = array();
    protected $_type    = 'message';
    protected $_typeArr = array(
        'message' => 1,
        'sms'     => 1,
        'email'   => 1
    );

    /**
     * 数据内容写入
     *
     * @param string $key
     * @param fix $value
     * @return \Business\QueueModel
     */
    public function setContent($key, $value) {
        $this->_data[$key] = $value;

        return $this;
    }

    /**
     * 队列数据类型
     *
     * @param string $type
     * @return \Business\QueueModel
     */
    public function setType($type){
        $this->_type = (string)$type;

        return $this;
    }

    /**
     * 压入队列 [目前写数据库]
     *
     * @return boolean
     */
    public function push() {
        if (!empty($this->_data) && isset($this->_typeArr[$this->_type])) {
            $queueModel = new \QueueModel(array(
                'type'    => $this->_type,
                'content' => \json_encode($this->_data)
            ));
            return (bool)(\Mapper\QueueModel::getInstance()->push($queueModel));
        }

        return false;
    }

    /**
     * 彃出队列数据
     *
     * @return NUll
     */
    public function pop(){
        return ((isset($this->_typeArr[$this->_type])) ? \Mapper\QueueModel::getInstance()->pull($this->_type) : null);
    }
}
