<?php
namespace app\api\controller;
header("Content-Type: text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
use think\Controller;
use think\Db;
class Update extends Controller
{
    public function file(){
    $uId = input('uid');
    $fId = input('fid');
    $fName = input('fname');
    $file = request()->file('fpic');
    $picBefore = Db::name("file_ck")->where("fid='$fId'")->value('fpic');
    $delPic = unlink(ROOT_PATH.'public'.DS.'static'.DS.'Fileimages'.DS.$picBefore);

    if (!$delPic){ 
      return json([
                    'error' => '0',
                    'msg' => '删除图片失败']);
    } else{
      if ($file){
        $info = $file->validate(['size'=>3000000,'ext'=>'jpg,png,gif'])
        ->rule('uniqid')->move(ROOT_PATH.'public'.DS.'static'.DS.'Fileimages');
        if ($info){
          $data = [
                    'uid' => $uId,
                    'fname' => $fName,
                    'fupdatetime' => date('Y-m-d H:i:s'),
                    'fpic' => $info->getFilename() 
                ];
          $addPic = Db::name('file_ck')->where('fid',$fId)->update($data);
          if ($addPic)
            return json([
                          'status' => '1',
                          'msg' => '保存成功' ]);
          else
            return json([
                          'error' => '1',
                          'msg' => '保存失败' ]);
        }else
          return json([
                        'status' => '0',
                        'msg' => $file->getError() ]);
      }else
        return json([
                      'status' => '2',
                      'msg' => $file->getError() ]);  
     }

    }

    public function user(){
      $uId = input('post.uid');
      $uName = input('post.uname');
      $uPhone = input('post.uphone');
      $uEmail = input('post.uemail');
      $target = Db::name('user_ck')->where('uid',$uId)->find();
      if ($target){
        $data = [
                  "uname" => $uName,
                  "uphone" => $uPhone,
                  "uemail" => $uEmail
              ];
        $request = Db::name("user_ck")->where("uid='$uId'")->update($data);
        if ($request){
          return json([
                        "status" => "1",
                        "msg" => "信息修改成功" ]);
        } else{
          return json([
                        "status" => "0",
                        "msg" => "信息修改失败" ]);
        }     
      } else{
        return json([
                      "error" => "0",
                      "msg" => "用户ID不存在" ]);
      }
  }
  public function userimage(){
    $uId = input('post.uid');
    $file = request()->file('upic');
    if (request()->isPost()){
            if ($file){
              $picBefore = Db::name("user_ck")->where("uid='$uId'")->value('upic');
              if (!is_null($picBefore)){
                unlink(ROOT_PATH.'public'.DS. 'static'.DS.'Userimages'.DS.$picBefore);
              }

              $info = $file->validate(['size'=>3000000,'ext'=>'jpg,png,gif'])->
              rule('uniqid')->move(ROOT_PATH.'public'.DS. 'static'.DS.'Userimages');
                  if ($info) {
                              $data = [
                                        "upic" => $info->getFilename()
                                    ];
                        $request = Db::name("user_ck")->where("uid='$uId'")->update($data);
                        if ($request){
                          return json([
                                        "status" => "1",
                                        "msg" => "修改成功" ]);
                        } else{
                          return json([
                                        "status" => "0",
                                        "msg" => "修改失败" ]); 
                        }
                  } else{
                      echo $file->getError();
                    }
              
            } else{
              return json([
                            "error" => "0",
                            "msg" => "文件上传失败" ]);
            }
    } else{
      return json([
                    "error" => "1",
                    "msg" => "未使用Post方法" ]);
    }
}


}