<?php


namespace app\api\model;


class Image extends BaseModel
{
//    protected $hidden = ['delete_time','update_time'];
      protected $visible = ['url'];

    // 我们要获取url这个字段,获取默认接收两个参数
    // $value是当前这条记录里url字段的值
    // $data是当前记录的完整数据
    public function getUrlAttr($value,$data){
        // 根据表的注释，1来自本地，2来自公网
        $final_url = $value;
        // 这里我们把本机的存放图片目录的域名地址写到了一个配置文件里。
        // 后续我们可能换了域名或者目录，又或者有其他来源渠道，以配置文件的形式这样以后只需改配置文件而不必改动代码
        // 利用TP5框架内置的助手函数config()快速指定获取配置文件下内容
        if($data['from']==1){
            $final_url = config('setting.img_prefix').$value;
        }
        // 这里如果from不是来自本地，那么存储的会是一个完整的公网访问地址，无需处理
        // 返回处理后的url
        return $final_url;
    }
}