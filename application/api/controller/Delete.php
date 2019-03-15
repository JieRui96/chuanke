<?php
namespace app\api\controller;
header("Content-Type: text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
use think\Controller;
use think\Db;
class Delete extends Controller
{

    public function collect(){
      $uId = input('uid');
      $tId = input('tid');
      $target = Db::name('collecion_ck')->where("uid='$uId' AND tid='$tId'")->find();

      if ($target){
        $delete = Db::name('collecion_ck')->where('cid',$target['cid'])->delete();
        if($delete){
          $newCollectNum=Db::name('template_ck')->where('tid',$tId)->value('tcollectnum')-1;
          $data = [
                    'tcollectnum' => $newCollectNum
                ];
          Db::name('template_ck')->where('tid',$tId)->update($data);
          return json([
                        'status' => '1',
                        'msg' => '取消收藏成功']);
        }else
          return json([
                        'status' => '0',
                        'msg' => '取消收藏失败']);
      }else{
          return json([
                        'error' => '0',
                        'msg' => '没有收藏该模板']); 
      }
    }

    public function file(){
    	$uId = input('uid');
    	$fId = input('fid');
    	$target = Db::name('file_ck')->where("uid='$uId' AND fid='$fId'")->find();
    	if ($target){
        $picBefore = Db::name("file_ck")->where("fid='$fId'")->value('fpic');
        $delPic = unlink(ROOT_PATH.'public'.DS. 'static'.DS.'Fileimages'.DS.$picBefore);
        if (!$delPic){
          return json([
				                'error' => '0',
				                'msg' => '删除图片失败' ]);
        } else{
          $delFile = Db::name('file_ck')->where('fid',$fId)->delete();
          if($delFile)
      		    return json([
                            'status' => '1',
                            'msg' => '删除成功' ]);
      	  else
      		    return json([
				                    'status' => '0',
				                    'msg' => '删除失败' ]);
        }
    	} else{
    		 return json([
				              'error' => '1',
				              'msg' => '无权删除' ]);
    	}
    }

    public function order(){
      $uId = input('uid');
      $oId = input('oid');
      $target = Db::name('order_ck')->where("uid='$uId' AND oid='$oId'")->find();
      if ($target){
        $data = [
                  'opaystate' => 2
              ];
        $cancelOrder = Db::name('order_ck')->where('oid',$oId)->update($data);
        if ($cancelOrder)
          return json([
                        'status' => '1',
                        'msg' => '取消订单成功' ]);
        else 
          return json([
                        'status' => '0',
                        'msg' => '取消订单失败' ]);       
      } else{
         return json([
                      'status' => '2',
                      'msg' => '无权取消订单' ]);
      }
    }

}