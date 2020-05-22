<?php


namespace app\api\controller\v1;

use app\api\model\Banner as BannerModel;
use app\api\model\BannerItem;
use app\lib\exception\banner\BannerException;
use Exception;
use think\facade\Hook;
use think\facade\Request;
use think\response\Json;

class Banner
{
    public function getBanners()
    {
        $result = BannerModel::with('items.img')->select();
        return $result;
    }


    /**
     * 新增轮播图接口
     * @validate('BannerForm')
     */
    public function addBanner()
    {
        // Request::post()用于获取当前POST类型请求所携带所有参数，结果是一个数组
        // 给post()传递一个字符串则表示获取指定参数名称的值，比如post('id')
        $params = Request::post();
        BannerModel::add($params);
        return writeJson(201, [], '新增成功！');
    }


    /**
     * @return Json
     * @auth('删除轮播图','轮播图管理')
     * @param('ids','待删除的轮播图id列表','require|array|min:1')
     */
    public function deleteBanner()
    {
        $ids = Request::delete('ids');
        array_map(function ($id) {
            // 查询指定id的轮播图记录
            $banner = BannerModel::get($id, 'items');
            // 指定id的轮播图不存在则抛异常
            if (!$banner) throw new BannerException(['msg' => 'id为' . $id . '的轮播图不存在']);
            // 执行关联删除
            $banner->together('items')->delete();
        }, $ids);

        //记录删除日志/user/permissions
        Hook::listen('logger','删除了id为' . implode(',', $ids) . '的轮播图');

        return writeJson(201, [], '轮播图删除成功！');
    }


    /**
     * 编辑轮播图基础信息
     * @param $id
     * @return Json
     * @throws BannerException
     * @param ("id","轮播图id","require|number")
     * @param('name','轮播图名称','require')
     */
    public function editBannerInfo($id){
        $bannerInfo = Request::patch();
        $banner = BannerModel::get($id);
        if(!$banner) throw new BannerException(['msg' => 'id为' . $id . '的轮播图不存在']);
        $banner->save($bannerInfo);
        return writeJson(201, [], '轮播图基础信息更新成功！');
    }

    /**
     * 新增轮播图元素
     * @validate('BannerItemForm.add')
     * @return \think\response\Json
     * @throws \LinCmsTp5\exception\ParameterException
     */
    public function addBannerItem()
    {
        $data = Request::post('items');

        foreach ($data as $key => $value) {
            var_dump($value);
            BannerItem::create($value);
        }
        return writeJson(201, [], '新增轮播图元素成功！');
    }

    /**
     * 编辑轮播图元素
     * @validate('BannerItemForm.edit')
     * @throws Exception
     */
    public function editBannerItem(){
        $data = Request::put("items");
        $bannerItem  = new BannerItem();
        # allowField(true)表示只允许写入数据表中存在的字段。
        # saveAll()接收一个数组，用于批量更新或者新增。通过判断传入的数组中是否设置了id属性，如果有则视为更新，无则视为新增
        $bannerItem ->allowField(true)->saveAll($data);
        return writeJson(201,[],'编辑轮播图元素成功');
    }

    /**
     * 删除轮播图元素
     * @param('ids','待删除的轮播图元素id列表','require|array|min:1')
     * @return Json
     */
    public function delBannerItem(){
        $ids = Request::delete('ids');
        // 传入多个id组成的数组进行批量删除
        BannerItem::destroy($ids);
        return writeJson(201,[],'删除成功');
    }
}