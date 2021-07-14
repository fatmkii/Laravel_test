<?php

namespace App\Common;

class ResponseCode
{
    const SUCCESS = 200;

    const FILED_ERROR = 20422;

    const USER_NOT_FOUND = 21404;

    const USER_PASSWORD_ERROR = 21001;
    
    const ADMIN_UNAUTHORIZED = 22401;

    const CANNOTLOGIN = 21401;

    const TOPIC_CANNOT_FOUND = 23404;

    const LOGIN_FAILED = 20001;

    const NOTALLOWDELETETHISREPLY = 24001;

    const REPLY_NOT_FOUND = 24002;

    const NOACCESSSITE = 10403;

    const NOCREATEPOST = 25001;

    const NOACCESSADMINCENTER = 30001;

    const INVITECODECANNOTNULL = 40001;

    const INVITECODEERROR = 40002;

    const POSTIDNOTALLOWNULL = 20423;
    
    const POSTNOTFOUND =26001;

    const DEFAULT = 99999;

    public static $codeMap = [
        self::SUCCESS => '请求成功',
        self::DEFAULT => '服务器异常',
        self::FILED_ERROR => '表单验证未通过',
        self::USER_NOT_FOUND => '用户无法找到',
        self::USER_PASSWORD_ERROR => '密码输入错误',
        self::ADMIN_UNAUTHORIZED => '管理员权限不足',
        self::CANNOTLOGIN => '用户无法登录',
        self::TOPIC_CANNOT_FOUND => '主题贴获取失败',
        self::LOGIN_FAILED => '登录态失效',
        self::NOTALLOWDELETETHISREPLY => '无权删除此回复',
        self::REPLY_NOT_FOUND => '回帖已经被删除',
        self::NOACCESSSITE => '此饼干禁止访问站点',
        self::NOCREATEPOST => '此饼干禁止发布内容',
        self::NOACCESSADMINCENTER => '禁止访问管理员后台',
        self::INVITECODECANNOTNULL => '邀请码不能为空',
        self::INVITECODEERROR => '邀请码输入错误',
        self::POSTNOTFOUND => '帖子资源无法查询到',
        self::POSTIDNOTALLOWNULL => '帖子ID不能为空',
    ];
}