<?php

namespace Home\Model;

use Think\Model;

class UserModel extends Model {

	protected $_validate = array(

			array('UE_account','UE_account1','用户名两次输入不对！',1,'confirm',self::MODEL_BOTH),

			array('UE_account','','用户名已存在！',1,'unique',self::MODEL_BOTH),

			array('UE_accName','require','介绍人必填！',1),

			array('UE_accName','validate','介绍人不存在！',1,'callback',self::MODEL_BOTH),

			array('UE_theme','1,50','昵称1-10个字元！',1,'length',self::MODEL_BOTH),

			array('UE_phone','require','手机必填！',1),

			array('UE_password','/^[a-zA-Z0-9]{6,15}$/','登入密码6-12个字元,大小写英文+数字,请勿用特殊词符！',1),

			array('UE_password','UE_repwd','两次输入密码不同！',1,'confirm',self::MODEL_BOTH),

	);

	protected $_auto = array (

			array('UE_password','md5',3,'function') ,

			array('UE_secpwd','md5',3,'function')

	);

	public function validate($data){

		if(M('user')->where(array('UE_account'=>$data))->find()){

			return TRUE;

		}else{

			return FALSE;

		}

	}

	

	public function pinyz($data){

		if(M('pin')->where(array('pin'=>$data,'zt'=>'0'))->find()){

			return TRUE;

		}else{

			return FALSE;

		}

	}

	

	public function validate1($data){

		if(M('user')->where(array('UE_account'=>$data))->find()){

			return TRUE;

		}else{

			return FALSE;

		}

	}

			

}