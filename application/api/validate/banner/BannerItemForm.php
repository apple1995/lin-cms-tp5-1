<?php


namespace app\api\validate\banner;


use LinCmsTp5\validate\BaseValidate;

class BannerItemForm extends BaseValidate
{

    //这里我们同样继承BaseValidate类，然后定义了一条items参数的验证规则，要求这个参数的值必须传而且是最小长度为1的数组，
    //接下来，我们定义了两条自定义验证规则——checkAddItem()和checkEditItem()，规则的内容就是遍历参数的值，判断值是否存在或者合法。
    //从方法名称我们可以看出，这是分别对应新增轮播图元素和编辑轮播图元素时用的，那么怎么让这两条规则在不同场景下生效呢？
    //方法就是上面的sceneEdit()和sceneAdd()（注：此为固定命名格式，scene+场景名，场景名首字母大写），
    //这两个方法定义了两个场景，分别是edit和add，每个场景内都执行了一个return $this->append('参数名', '规则名')这段代码，
    //这段代码的作用就是，当这个场景触发时，就给指定的参数名追加一条规则。这里我们默认只有对items参数的长度和类型做校验，
    //当触发了edit或者add场景时，对应的自定义规则就会被追加进去。
    protected $rule = [
        'items' => 'array|require|min:1',
    ];

    public function sceneEdit()
    {
        return $this->append('items', 'checkEditItem');

    }

    public function sceneAdd()
    {
        return $this->append('items', 'checkAddItem');

    }

    protected function checkAddItem($value)
    {
        foreach ($value as $k => $v) {
            if (!empty($v['id'])) {
                return '新增轮播图元素不能包含id';
            }
            if (empty($v['img_id']) || empty($v['key_word']) || empty($v['type']) || empty($v['banner_id'])) {
                return '轮播图元素信息不完整';
            }
        }
        return true;
    }

    protected function checkEditItem($value)
    {
        foreach ($value as $k => $v) {
            if (empty($v['id'])) {
                return '轮播图元素id不能为空';
            }
            if (empty($v['img_id']) || empty($v['key_word']) || empty($v['type'])) {
                return '轮播图元素信息不完整';
            }
        }
        return true;
    }
}