<?php
namespace app\api\controller;
header("Content-Type: text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
use think\Controller;
use think\Db;

class User extends Controller
{

	public function getuser(){
		$uId = input('uid');
		$targetUser = Db::name('user_ck')->where('uid',$uid)->find();
		if ($targetUser){
			return json([
							'upic' => $targetUser['upic'],
							'uname' => $targetUser['uname'],
							'uphone' => $targetUser['uphone'],
							'uemail' => $targetUser['uemail'] ]);
		} else{
			return json([
				    		'error'=>'0',
				 			'msg'=>'未找到用户信息' ]);
		}		
		
	}


	public function login(){
		$uName = input('uname');
		$uPassword = input('upassword');
		$targetUser = Db::name('user_ck')->where('uname',$uName)->find();
		if ($targetUser){
			if(md5($uPassword) == $targetUser['upassword']){
				return json([
				            	'uid' => $targetUser['uid'],
				                'uname' => $targetUser['uname'],
				                'uemail' => $targetUser['uemail'],
				            	'uphone' => $targetUser['uphone'],
				            	'ustate' => $targetUser['ustate'],
				                'uzctime' => $targetUser['uzctime'] ]);
			} else{
				return json([
				            	'error' => '0',
				                'msg' => '密码错误' ]);
			}
		} else{
			return json([
				        	'error' => '1',
				            'msg' => '用户不存在' ]);
		}
	}


	public function register(){
		$uName = input('uname');
        $uPhone = input('uphone');
        $uPassword = input('upassword');
        $targetUser = Db::name('user_ck')->where('uname',$uName)->find();
        if ($targetUser){
            return json([
				        	'error' => '0',
				            'msg' => '用户名已被注册' ]);
        } else{
        	$data = [
						'uname' => $uName,
                    	'uphone' => $uPhone,
                    	'upassword' => md5($uPassword),
						'uzctime' => date('Y-m-d H:i:s')
				];
            $registerReslut = Db::name('user_ck')->insert($data);
        	if ($registerReslut){
                return json([
                				'error'=>'1',
				              	'msg'=>'注册成功' ]);
        	} else{
                return json([
				                'error'=>'0',
				                'msg'=>'注册失败' ]);
        	}
        }
    }


    public function repasswd(){
    	$uName = input('uname'); 
    	$uEmail = input('uemail');
    	$targetUser = Db::name('user_ck')->where('uname',$uName)->find();
    	if ($targetUser){
	    	if ($targetUser['uemail'] == $uEmail){
	    		$data = [
	    					'upassword' => md5(123456)
	    			];
	    		$repasswdResult = Db::name('user_ck')->where('uid',$res['uid'])->update($data);
	    		if ($repasswdResult){
	    			return json([
	    				     		'error' => '1',
	    				     		'msg' => '重置成功 密码123456' ]);
	    		}
	 	  	} else
	 	  		return json([
	 	  						'error' => '0',
	 	  						'msg' => '邮箱与用户名不匹配' ]);
 		} else
 			return json([
	 	  					'error' => '0',
	 	  					'msg' => '用户名不存在' ]);
	}



	public function updatepass(){
		$uId = input('uid');
		$newPassword = input('newpassword');
		$newName = input('newname');

		$targetUser = Db::name('user_ck')->where('uid',$uid)->find();
		if ($newPassword == null)
			$newPassword = $targetUser['upassword'];

		if ($newName == null)
			$newName = $targetUser['uname'];

		if ($targetUser){
			$data = [	'upassword' => md5($newPassword),
						'uname' => $newName
				];
			$updateResult = Db::name('user_ck')->where('uid',$uId)->update($data);
            if ($updateResult){
                return json([
				                'status' => '1',
				                'msg' => '修改成功' ]);
            } else{
                return json([
				                'status' => '0',
				                'msg' => '修改失败' ]);
             	}
		} else{

			return json([
							'error' => '0',
							'msg' => '用户不存在' ]);
			}
	}

}
