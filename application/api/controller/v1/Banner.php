<?php


namespace app\api\controller\v1;

use app\api\model\Banner as BannerModel;
use app\lib\exception\banner\BannerException;
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
}