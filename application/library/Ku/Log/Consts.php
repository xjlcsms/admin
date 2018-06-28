<?php

namespace Ku\Log;

final class Consts {

    private function __construct() {
        
    }

    private function __clone() {
        
    }

    private function __sleep() {
        
    }

    /**
     * 驱动
     */
    const DRIVER_REDIS = 'redis';
    const DRIVER_MYSQL = 'mysql';
    const DRIVER_FILES = 'files';



    /**
     * 日志来源 类型 (0x00 - 0x0A)
     */
    const FROM_SYSTEM = 0x01;//系统操作
    const FROM_ADMIN  = 0x02;//管理员操作
    const FROM_USER   = 0x03;//用户操作
    const FROM_USER_LOGIN = 0x04;
    const FROM_USER_GOLD_AWARD = 0x05;
    const FROM_USER_EXCHANGE = 0x06;
    const FROM_USER_GAMEPLAY = 0x07;
    const FROM_API  = 0x08;
    const FROM_TASK = 0x09;
    const FROM_APPLICATION = 0x10;

    /**
     * 日志类型
     */
    const USER_LOGIN = 1;
    const USER_LOGOUT = 2;
    const USER_CHANGEPASSWD = 3;
    const USER_PUCHARGE_PRODUCT = 4;
    const USER_RENEWAL_PRODUCT = 5;
    const USER_RECHARGE = 6;
    const USER_PUCHARGE_PACKAGE = 7;
    const USER_SET_PATTERN = 8;
    const ADMIN_LOGIN = 101;
    const ADMIN_LOGOUT = 102;
    const APPLICATION = 103;

    /**
     * 格式
     */
    const FORMAT = array(
        1 => '%s (%s) 在 %s 时成功登录',
        2 => '%s (%s) 在 %s 时登出',
        3 => '%s (%s) 在%s是使用修改密码功能修改了密码',
        4 => '%s 在 %s 时购买了%s数据服务，有效期%s年,消费了%s金额，赠送了%s积分',
        5 => '%s 在 %s 时续费了%s数据服务，续费%s年,消费了%s金额，赠送了%s积分',
        6 => '%s 在 %s 时充值了%s积分',
        7 => '%s 在 %s 时购买了%s次次数包，花费%s积分',
        8 => '%s 在 %s 时重置了配置',
        101 => '%s (%s) 在 %s 时成功登录',
        102 => '%s (%s) 在 %s 时登出',
        103 => '%s msg:%s class:%s function:%s line:%s',
    );

    /**
     * 相关名称处理
     */
    const FILESNAME = '%sapplication-%s-%s.log';    // {PATH}log-{FROM}-{DATA}.LOG
    const REDISKEY = 'srkl.%s.%s';         // system.resource.keepkey.log.{FROM}.{DATE}

}
