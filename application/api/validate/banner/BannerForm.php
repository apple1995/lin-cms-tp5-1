<?php


namespace app\api\validate\banner;


use LinCmsTp5\validate\BaseValidate;

class BannerForm extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'items' => 'require|array|min:1'
    ];

    protected $message = [
        'name' => '轮播图名称不能为空',
        'items.require' => '轮播图元素不能为空',
        'items.array' => '轮播图元素的值必须为数组'
    ];
    //在继承BaseValidate后这个类就成为了自定义验证器类，通过定义成员变量$rule指定要校验的字段和校验规则，
    //校验规则可以使用正则表达式、自定义规则或者TP5框架提供的内置规则，这里的require表示这属于一个调用接口时必传的字段，
    //这里我们要求当调用新增轮播图接口时，轮播图的名称和描述以及包含的元素都必须传递，其中轮播元素字段还要求是一个数组且长度至少为1（多个规则之间以|分隔）。
    //
    //通过定义成员变量$message来指定当字段校验不通过时所显示的错误提示信息，这里不定义的话会采用TP5框架提供的默认提示信息。
    //当一个字段有多个校验规则时，可以通过字段名+.规则名指定不同规则校验不通过时要显示的信息。
}