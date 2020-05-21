<?php


namespace app\api\model;


use think\model\concern\SoftDelete;

class Banner extends BaseModel
{
    //使用软删除
    use SoftDelete;

    public function items(){
        return $this->hasMany('BannerItem','banner_id','id');
    }

    public static function add($params){
        // 调用当前模型的静态方法create()，第一个参数为要写入的数据，第二个参数标识仅写入数据表定义的字段数据
        $banner = self::create($params,true);
        // 调用关联模型，实现关联写入；saveAll()方法用于批量新增数据
        $banner->items()->saveAll($params['items']);

//        解释
//        由于提交过来的$params参数中同时含有两个表需要插入的内容，所以这里我们在插入banner表数据的时候，
//        给create()方法的第二个参数传了个true，这样模型在插入数据的时候就会自动帮我们过滤掉$params参数中的items那部分数据。
//        插入成功后，create()方法会返回Banner模型的实例，我们利用这个模型实例调用上一小结中定义的items()得到了BannerItem模型，
//        然后调用saveAll()并把$params中items部分的数据传递进去来实现关联新增。
    }
}