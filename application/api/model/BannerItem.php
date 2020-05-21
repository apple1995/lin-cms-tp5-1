<?php


namespace app\api\model;


use think\model\concern\SoftDelete;

class BannerItem extends BaseModel
{
    use SoftDelete;
    public function img(){
        return $this->belongsTo('Image','img_id','id');
    }
}