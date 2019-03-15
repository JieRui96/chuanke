<?php
namespace app\api\controller;
header("Content-Type: text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
use think\Controller;
use think\Db;
class Add extends Controller
{
  public function file(){
    $uId = input('uid');
    $fName = input('fname');
    $file = request()->file('fpic');
    if ($file){
      $info = $file->validate(['size'=>3000000,'ext'=>'jpg,png,gif'])
      ->rule('uniqid')->move(ROOT_PATH.'public'.DS. 'static'.DS.'Fileimages');
      if ($info){
        $data = [
                  'uid' => $uId,
                  'fname' => $fName,
                  'fupdatetime' => date('Y-m-d H:i:s'),
                  'fpic' => $info->getFilename()
              ];
        $addPicResult = Db::name('file_ck')->insert($data);
        if ($addPicResult)
          return json([
                        'status' => '1',
                        'msg' => '保存成功',]);
        else
          return json([
                        'error' => '0',
                        'msg' => '保存失败',]);
      } else 
        return json([
                      'status' => '0',
                      'msg' => $file->getError()]);
    } else{
          return json([
                        'status' => '2',
                        'msg' => $file->getError()]);
    }
  }

  public function collect(){
    $uId = input('uid');
    $tId = input('tid');
    $target = Db::name('template_ck')->where('tid',$tId)->find();
    $status = Db::name('collecion_ck')->where('uid',$uId)->where('tid',$tId)->find();
    if ($status){
      return json([
                    'error' => '0',
                    'msg' => '已经收藏过了']);
    } else{
      $data = [
                'uid' => $uId,
                'tid' => $tId
            ];
    $addCollect = Db::name('collecion_ck')->insert($data);
    if ($addCollect){
      $data = [
                'tcollectnum' => $target['tcollectnum']+1
            ];
      Db::name('template_ck')->where('tid',$tId)->update($data);
      return json([
                    'status' => '1',
                    'msg' => '收藏成功' ]);
    } else
      return json([
                    'status' => '0',
                    'msg' => '收藏失败' ]);
    }
  }
  
  public function order(){
    $uId = input('uid');
    $sId = input('sid');
    $fId = input('fid');
    $oPay = input('opay');
    $oSum = input('osum');
    $oEndTime = input('oendtime');
    $oStartTime = input('ostarttime');
    $oNo = date('Ymd').str_pad(mt_rand(1, 99999),5,'0',STR_PAD_LEFT);
    //eg  2019-03-12 13:00:00 得到13
    $endTime =  msubstr($oEndTime,11,2,"utf-8",false);
    $startTime = msubstr($oStarTtime,11,2,"utf-8",false);
    
    $insertTimes = $endTime - $startTime;
    //eg  2019-03-12 15:00:00 得到2019-03-12   
    $insertDate = msubstr($oStartTime,0,10,"utf-8",false); 

    for ($i = 0; $i < $insertTimes; $i++){
      $cntValue = Db::name('time_ck')->where('sid',$sId)
      ->where('date',$insertDate)->where('time',$startTimes + $i)->value('count');

      if ($cntValue){
         $data = [
                  'sid' => $sId,
                  'date' => $date,
                  'count' => $cntValue + 1,
                  'time' => $startTimes + $i
              ];
        $addCnt = Db::name('time_ck')->where('sid',$sId)
        ->where('date',$insertDate)->where('time',$startTimes + $i)->update($data);
        
      } else{
          $data = [
                    'sid' => $sId,
                    'date' => $date,
                    'count' => 1,
                    'time' => $startTimes + $i
                ];
          $addCnt = Db::name('time_ck')->insert($data);

      }
      if (!$addCnt)
          return json([
                      'status'=>'2',
                      'msg'=>'网络异常' ]);
      
    }

    $targetFile = Db::name('file_ck')->where('uid',$uId)->where('fid',$fId)->find();
    $filePath = ROOT_PATH.'public'.DS. 'static'.DS.'Fileimages'.DS.$targetFile['fpic'];
    $orderPath = ROOT_PATH.'public'.DS. 'static'.DS.'Orderimages'.DS.$targetFile['fpic'];
    $moveFile = copy($filePath,$orderPath);
    $data = [
              'ono' => $oNo,
              'uid' => $uId,
              'sid' => $sId,
              'opay' => $oPay,
              'oupdatetime' => date('Y-m-d H:i:s'),
              'ostate' => 0,
              'oplaystate' => 0,
              'opaystate' => 0,
              'fid' => $fId,
              'opic' => $targetFile['fpic'],
              'ostarttime' =>$oStartTime,
              'oendtime' => $oEndTime,
              'osum' => $oSum
          ];
    if ($moveFile){
      $addOrder = Db::name('order_ck')->insert($data);

      if($addOrder)
        return json([
                      'status'=>'1',
                      'msg'=>'下单成功' ]);
      else
        return json([
                      'status'=>'0',
                      'msg'=>'下单失败' ]);
  } else
    return "移动失败";

}

}