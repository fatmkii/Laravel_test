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

// 00	Bad Request	客户端请求的语法错误，服务器无法理解
// 401	Unauthorized	请求要求用户的身份认证
// 402	Payment Required	保留，将来使用
// 403	Forbidden	服务器理解请求客户端的请求，但是拒绝执行此请求
// 404	Not Found	服务器无法根据客户端的请求找到资源（网页）。通过此代码，网站设计人员可设置"您所请求的资源无法找到"的个性页面
// 405	Method Not Allowed	客户端请求中的方法被禁止
// 406	Not Acceptable	服务器无法根据客户端请求的内容特性完成请求
// 407	Proxy Authentication Required	请求要求代理的身份认证，与401类似，但请求者应当使用代理进行授权
// 408	Request Time-out	服务器等待客户端发送的请求时间过长，超时
// 409	Conflict	服务器完成客户端的 PUT 请求时可能返回此代码，服务器处理请求时发生了冲突
// 410	Gone	客户端请求的资源已经不存在。410不同于404，如果资源以前有现在被永久删除了可使用410代码，网站设计人员可通过301代码指定资源的新位置
// 411	Length Required	服务器无法处理客户端发送的不带Content-Length的请求信息
// 412	Precondition Failed	客户端请求信息的先决条件错误
// 413	Request Entity Too Large	由于请求的实体过大，服务器无法处理，因此拒绝请求。为防止客户端的连续请求，服务器可能会关闭连接。如果只是服务器暂时无法处理，则会包含一个Retry-After的响应信息
// 414	Request-URI Too Large	请求的URI过长（URI通常为网址），服务器无法处理
// 415	Unsupported Media Type	服务器无法处理请求附带的媒体格式
// 416	Requested range not satisfiable	客户端请求的范围无效
// 417	Expectation Failed	服务器无法满足Expect的请求头信息