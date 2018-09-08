<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/6/30
 * Time: 10:25
 */
namespace Mapper;

class QueueModel extends \Mapper\AbstractModel
{

    use \Base\Model\InstanceModel;

    protected $modelClass = '\QueueModel';

    protected $table = 'queue';

    /**获取
     * @param string $type
     * @return \Base\Model\AbstractModel|null
     */
    public function pull($type=''){
        $where = array('status'=>0,'isfail'=>0);
        if(!empty($type)){
            $where['type'] = $type;
        }
        $model = $this->fetch($where,array('id asc'));
        if (!$model instanceof \QueueModel){
            return null;
        }
        $model->setStatus(1);
        $this->update($model);
        return $model;
    }

    public function pullSuccess($type=''){
        $where = array('status'=>2);
        if(!empty($type)){
            $where['type'] = $type;
        }
        $model = $this->fetch($where,array('id asc'));
        if (!$model instanceof \QueueModel){
            return null;
        }
        $model->setStatus(11);
        $this->update($model);
        return $model;
    }


}