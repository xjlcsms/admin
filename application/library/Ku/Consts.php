<?php

namespace Ku;

final class Consts {

    // 系统保留字段或名称
    const KEEP_FIELD = 'system.keep.field';

    // 最后登录KEY标识
    const LAST_LOGIN_TIME = 'prev_logintime';
    
    // mobile send pre ikey
    const MOBILE_IKEY = 'user.resource.mobile.precheck.i.%s';

    // 短信验证码
    const MOBILEPHONE_MESSAGECODE = 'user.resource.v.messagecode.%s';

    // 是否使用验证码
    const USING_CAPTCHA     = 'user.resource.usingcaptcha.%s';
    // 验证码频道
    const USING_CAPTCHA_CHANNEL = 'user.resource.captcha.%s';

    const USING_CAPTCHA_CHANNEL_TIME = 'user.resource.captcha.time.%s';
    // 资料上传安全码
    const PROFILE_UPLOAD_CODE = 'profile.icons.securecode.%s';

    // 邮件链接验证
    const EMAIL_LINK = 'emaillink.%s';

    // Email send pre ikey
    const EMAIL_IKEY = 'user.resource.email.precheck.i.%s';

    // Email send pre mkey
    const EMAIL_MKEY = 'user.resource.email.precheck.m.%s';

    //任务的时间段集合
    const TASK_LOG_TIMESLOT = '.task.log.timeslot';

    //按码类型统计访问请求数
    const QR_REQ = 'qr.req.%s';
    //缓存请求数，限制请求数量
    const QR_REQ_LIMIT = 'qr.req.limit.%s.%s';

    private function __construct() {}
    private function __sleep() {}
    private function __clone() {}
}