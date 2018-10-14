<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/6/30
 * Time: 10:25
 */
namespace Mapper;

class UsersModel extends \Mapper\AbstractModel
{

    use \Base\Model\InstanceModel;

    protected $modelClass = '\UsersModel';

    protected $table = 'users';

    /**获取username
     * @param $userId
     * @return string
     */
    public function getUsername($userId){
        $user = $this->findById($userId);
        if($user instanceof \UsersModel){
            return $user->getUsername();
        }
        return '';
    }

    /**获取公司名称
     * @param $userId
     * @return string
     */
    public function getName($userId){
        $user = $this->findById($userId);
        if($user instanceof \UsersModel){
            return $user->getName();
        }
        return '';
    }

}