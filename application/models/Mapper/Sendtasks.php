<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/6/30
 * Time: 10:25
 */
namespace Mapper;

class SendtasksModel extends \Mapper\AbstractModel
{

    use \Base\Model\InstanceModel;

    protected $modelClass = '\SendtasksModel';

    protected $table = 'send_tasks';



}