<?php
namespace Business;

class UserModel  extends \Business\AbstractModel
{
    use \Base\Model\InstanceModel;

    /**用户账户修改
     * @param \UsersModel $user
     * @param $fee
     * @param $showFee
     * @param $type
     * @return bool
     */
    public function flow(\UsersModel $user , $fee , $showFee ,$type){

        switch ($type){
            case 'normal_balance':
                if($user->getShow_normal_balance()<$fee){
                    return $this->getMsg('行业短信余额不足',29302);
                }
                $update = array('normal_balance'=>'normal_balance-'.$fee , 'show_normal_balance'=>'show_normal_balance-'.$showFee);
                break;
            case 'market_balance';
                if($user->getShow_marketing_balance()<$fee){
                    return $this->getMsg('营销短信余额不足',29304);
                }
                $update = array('marketing_balance'=>'marketing_balance-'.$fee , 'show_marketing_balance'=>'show_marketing_balance-'.$showFee);
                break;
            default:
                return $this->getMsg('未匹配正确的类型',29300);
                break;
        }
        $update['updated_at'] = date('Y-m-d H:i:s');
        $where = array('id'=>$user->getId());
        $res = \Mapper\UsersModel::getInstance()->update($update,$where);
        if(!$res){
            return $this->getMsg('扣款失败',29305);
        }
        return true;
    }

}