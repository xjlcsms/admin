<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/6/30
 * Time: 10:25
 */
namespace Mapper;

class AdminModel extends \Mapper\AbstractModel
{

    use \Base\Model\InstanceModel;

    protected $modelClass = '\AdminModel';

    protected $table = 'admin';


    public function lastLogin(\AdminModel $adminModel){
        $adminModel->setUpdated_at(date('Y-m-d H:i:s'));
        $this->update($adminModel);
    }

}