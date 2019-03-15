<?php
namespace app\api\controller;
header("Content-Type: text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
use think\Controller;
use think\Db;
class Lists extends Controller
{
  public function advertise(){
    $advertise = Db::name('advertise_ck')->where('adstate',1)->where('adplaystate',1)->select();
    return json($advertise);
  }
//获取模版列表、详情
  public function template(){
    $tId = input('tid');
    if ($tId == null)
      $template = Db::name('template_ck')->select();
    else
      $template = Db::name('template_ck')->where('tid',$tId)->find();
    return json($template);
   }

//获取文件列表、详情
    public function file(){
      $uId = input('uid');
      $fId = input('fid');
      if ($fId == null)
        $file = Db::name('file_ck')->where('uid',$uId)->select();
      else
        $file = Db::name('file_ck')->where('fid',$fId)->find();
      return json($file);
    }

//获取终端设备列表、详情
    public function screen(){
      $sId = input('sid');
      if ($sId == null)
        $screen = Db::name('screen_ck')->select();
      else 
        $screen = Db::name('screen_ck')->where('sid',$sId)->find();
      
      return json($screen);
   }
//获取终端状态
   public function getScreenState(){
        $arrCnt = [];
        $sId = input('sid');
        $nowDate = date('Y-m-d'); //服务器当前的日期
        $time = Db::name('time_ck')->where('sid',$sId)->where('date',$nowDate)->select(); //返回当前设备当天的 所有信息
        if ($time){
              //例如 arrCnt[0] = 2 即为0-1时间段有 2 张图片
              for($i = 0;$i < 24; $i++) {

                  $arrCnt[$i] = Db::name('time_ck')->where('sid',$sId)
                  ->where('date',$nowDate)->where('time',$i)->value('count');

              }
              return json([ 
                            'date' => $nowDate,
                            'count' => $arrCnt ]);
        } else{
          //说明这个日期一条信息都没有
          return json([ 'date' => $nowDate,
                        'time' => '0-24',
                        'count' => '0' ]);
        }

   }

//查询状态不同订单列表
//查询不同状态下某订单详情。
    public function order(){
      $uId = input('uid');
      $oId = input('oid');
      $oState = input('ostate');
      $oPlayState = input('oplaystate');
      $oPayState = input('opaystate');
      if ($oId == null) {
        if ($oPlayState == null && $oPayState == null) {
          if ($oState == 0) {
            $order = Db::name('order_ck')->where('uid',$uId)->where('ostate',0)->select();//待审核
          }
          }
          if ($oState == null && $oPayState == null){
            if ($oPlayState == 0)
              $order = Db::name('order_ck')->where('uid',$uId)->where('oplaystate',0)->select();//未播放
            
            if ($oPlayState == 1)
              $order = Db::name('order_ck')->where('uid',$uId)->where('oplaystate',1)->select();//正在播放
            
            if ($oPlayState == 2)
              $order = Db::name('order_ck')->where('uid',$uId)->where('oplaystate',2)->select();//结束播放
            
          }
          if ($oState == null && $oPayState == null){
            if ($oPayState == 0)
              $order = Db::name('order_ck')->where('uid',$uId)->where('opaystate',0)->select();//未付款
            
            if ($oPayState == 2)
              $order = Db::name('order_ck')->where('uid',$uId)->where('opaystate',2)->select();//用户取消
            
          }
      } else{

        $order = Db::name('order_ck')->where('oid',$oId)->select();//详情
  
      }
      return json($order);
   }
//收藏模版列表，详情
    public function collect(){
      $uId = input('uid');
      $cId = input('cid');
      if ($cId == null){
        $collect = Db::name('collecion_ck')->where('uid',$uId)->select();
        if ($collect)
          return json($collect);
        else
          return json([
                        'error' => '1',
                        'msg' => '无收藏' ]);
      } else{
        $target = Db::name('collecion_ck')->where('uid',$uId)->where('cid',$cId)->find();
        $collect = Db::name('template_ck')->where('tid',$target['tid'])->find();

        if ($collect)
          return json($collect);
        else
          return json([
                        'error'=>'1' ]);
      }
    }
}