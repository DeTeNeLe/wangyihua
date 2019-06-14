<?php
namespace Home\Controller;
use Think\Controller;
class ShopController extends CommonController {
  
public function jbzg_list() {

		$User = M ( 'shopsj' ); // 實例化User對象
		$count = $User->where ( array (
				'leixin' => 'jbzgq' 
		) )->count (); // 查詢滿足要求的總記錄數
		$page = new \Think\Page ( $count, 60 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)
			
		// $page->lastSuffix=false;
		$page->setConfig ( 'header', '<li class="rows">共<b>%TOTAL_ROW%</b>條記錄    第<b>%NOW_PAGE%</b>頁/共<b>%TOTAL_PAGE%</b>頁</li>' );
		$page->setConfig ( 'prev', '上一頁' );
		$page->setConfig ( 'next', '下一頁' );
		$page->setConfig ( 'last', '末頁' );
		$page->setConfig ( 'first', '首頁' );
		$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
		;
	
		$show = $page->show (); // 分頁顯示輸出
		// 進行分頁數據查詢 注意limit方法的參數要使用Page類的屬性
		$list = $User->where ( array (
				'leixin' => 'jbzgq' 

		) )->order ( 'id DESC' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
		$this->assign ( 'list', $list ); // 賦值數據集
		$this->assign ( 'page', $show ); // 賦值分頁輸出
	
	
	
	
	
	
	
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
	
		$this->display ( 'Index/jbzg_list' );
	}
	
	
	public function zsbyg_list() {

		//////////////////----------
		$User = M ( 'info' ); // 實例化User對象
		
		if(I('get.type')==''){
			$map['zt']='0';
		}else{
			$map['IF_type']=I('get.type');
		}

		$count = $User->where ( $map )->count (); // 查詢滿足要求的總記錄數
		//$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)
		
		$p = getpage($count,100);
		
		$list = $User->where ( $map )->order ( 'IF_ID DESC' )->limit ( $p->firstRow, $p->listRows )->select ();
        foreach( $list as $key => $item)
        {
            $cur_cate = M('cate')->where(array('id'=>$item['if_type1id']))->find();
            $item['cate_name'] = $cur_cate['cate_name'];
            $list[$key] = $item;
        }


		$this->assign ( 'list', $list ); // 賦值數據集
		$this->assign ( 'page', $p->show() ); // 賦值分頁輸出
		/////////////////----------------

	
	
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
	
		$this->display ( 'Index/zsbyg_list' );
	}
	
	
	
	public function ly_list() {
	
	
	
	
	
	
	
	
		//////////////////----------
		$User = M ( 'message' ); // 實例化User對象
	
		if(I('post.user')==''){
			$map['zt']='0';
		}else{
			$map['MA_userName']=I('post.user');
		}
		
		if(I('get.type')=='0'){
			$map['zt']='0';
		}elseif(I('get.type')=='1'){
			$map['zt']='1';
		}
		
		
	
		$count = $User->where ( $map )->count (); // 查詢滿足要求的總記錄數
		//$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)
	
		$p = getpage($count,100);
	
		$list = $User->where ( $map )->order ( 'MA_ID DESC' )->limit ( $p->firstRow, $p->listRows )->select ();
		$this->assign ( 'list', $list ); // 賦值數據集
		$this->assign ( 'page', $p->show() ); // 賦值分頁輸出
		/////////////////----------------
	
	
	
	
		$thehost = "http://" . $_SERVER["HTTP_HOST"];
		$this->assign ( 'thehost', $thehost );
	
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
	
		$this->display ( 'Index/ly_list' );
	}
	
	
	public function applylist() {
	
		//////////////////----------
		$User = M ( 'apply' ); // 實例化User對象
	
		if(I('post.user')==''){
			$map['zt']='0';
		}else{
			$map['MA_userName']=I('post.user');
		}
		
		if(I('get.type')=='0'){
			$map['zt']='0';
		}elseif(I('get.type')=='1'){
			$map['zt']='1';
		}
		
		
	
		$count = $User->where ( $map )->count (); // 查詢滿足要求的總記錄數
		//$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)
	
		$p = getpage($count,100);
	
		$list = $User->where ( $map )->order ( 'MA_ID DESC' )->limit ( $p->firstRow, $p->listRows )->select ();
		$this->assign ( 'list', $list ); // 賦值數據集
		$this->assign ( 'page', $p->show() ); // 賦值分頁輸出
		/////////////////----------------
	
	
	
	
		$thehost = "http://" . $_SERVER["HTTP_HOST"];
		$this->assign ( 'thehost', $thehost );
	
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
	
		$this->display ( 'Index/ly_list' );
	}
	
	
	
	
	
    //编辑文章
	public function zsbyg_list_xg2() {

        //获取文章分类
        $cate_data = M('cate')->select();
        $this->assign('cate_data',$cate_data);
	
		$caution = M ( 'info' )->where ( array (
				'IF_ID'=> I('get.id') ,
		) )->find ();

		$this->caution=$caution;

	
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
	
		$this->display ( 'Index/zsbyg_list_xg2' );
	}
	
	
	public function ly_list_cl() {
	
	
	
		$caution = M ( 'message' )->where ( array (
				'MA_ID'=> I('get.id') ,
		) )->find ();
	
	
		$this->caution=$caution;
	
	
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
	
		$this->display ( 'Index/ly_list_cl' );
	}


    /**
     * 添加文章
     */
	public function zsbyg_list_xg() {

		//获取文章分类
        $cate_data = M('cate')->select();
        $this->assign('cate_data',$cate_data);
	
		$userData = M ( 'user' )->where ( array (
				'UE_ID' => $_SESSION ['uid']
		) )->find ();
		$this->userData = $userData;
	
		$this->display ( 'Index/zsbyg_list_xg' );
	}

    /**
     * 添加文章分类
     */
    public function add_article_cate()
    {
        if( IS_POST ){
            $post = I('post.');
            if( empty($post['cate_name']) )
            {
                $this->error('添加出错');
            }

            $cateData = M('cate')->where(array('cate_name'=>$post['cate_name']))->find();
            if( !empty($cateData) )
            {
                $this->error('当前文章分类已存在');
            }

            $addData['cate_name'] = $post['cate_name'];
            $addData['create_time'] = date("Y-m-d H:i:s",time());
            if( M('cate')->add($addData) )
            {
                $this->success('添加成功');
            }
            $this->error('添加出错');

        }
        $this->display('Index/add_article_cate');
    }
	
	
	
	public function jbzg_list_xgcl() {
	if(I('post.IF_type1ID')<>''&&I('post.IF_theme')<>''&&$_POST['content']<>''){
	    $imgRes = $this->upload();
        $faceImgPath = '';
	    if( $imgRes['code'] == 0 ){
            $this->error($imgRes['msg']);
        }else{
	        $faceImgPath =  $imgRes['rootPath'].$imgRes['data']['face']['savepath'].$imgRes['data']['face']['savename'];
        }
        $faceImgPath = trim($faceImgPath,'.');
		//$data['IF_type']=I('post.IF_type');
        $data['IF_type1ID']=I('post.IF_type1ID');//文章分类id
		$data['IF_theme']=I('post.IF_theme');
		$data['IF_webImg']=$faceImgPath;
		$data['IF_content']=$_POST['content'];
		$data['IF_time']=date ( 'Y-m-d H:i:s', time () );
	
		if(M('info')->add($data)){
			$this->success('添加成功！');
		}else{
			$this->success('添加失敗！');
		}
		//$this->success('成功！');
	}else{
		$this->success('数据不完整！');
	}
	}
    
	
	
	
	public function jbzg_list_xgcl2() {
		if(I('post.IF_type1ID')<>''&&I('post.IF_theme')<>''&&$_POST['content']<>''){
			$data['IF_type1ID']=I('post.IF_type1ID');
			$data['IF_theme']=I('post.IF_theme');
			$data['IF_webImg']=I('post.face180');
			$data['IF_content']=$_POST['content'];
			$data['IF_time']=date ( 'Y-m-d H:i:s', time () );
	
			if(M('info')->where(array('IF_ID'=>I('post.id')))->save($data)){
				$this->success('修改成功！');
			}else{
				$this->success('修改失敗！');
			}
			//$this->success('成功！');
		}else{
			$this->success('数据不完整！');
		}
	}
	
	
	
	public function ly_list_xgcl2() {
		
			
			$data['MA_reply']=$_POST['content'];
			$data['MA_replyTime']=date ( 'Y-m-d H:i:s', time () );
			$data['zt']='1';
			$data['jl']=$_POST['jl'];

			if(M('message')->where(array('MA_ID'=>I('post.id')))->save($data)){
				$this->success('处理成功！');
			}else{
				$this->success('处理失敗！');
			}
			//$this->success('成功！');
		
	}
	
	
	
	
	
	public function zsbyg_list_xgcl() {
	
		$data['sjmc']=I('post.sjmc');
		$data['jyxm']=I('post.jyxm');
		$data['lxfs']=I('post.lxfs');
		$data['dz']=I('post.dz');
		$data['slt']=I('post.face180');
		$data['content']=I('post.content');
		$data['zt']=I('post.zt');
		$data['date']=date ( 'Y-m-d H:i:s', time () );
		$data['leixin']='zsbygq';
	
		if(M('shopsj')->where(array('id'=>I('post.id'),'user'=>$_SESSION['uname']))->save($data)){
			$this->success('修改成功！');
		}else{
			$this->success('修改失敗！');
		}
		//$this->success('成功！');
	
	}
	
	
	
	
	
	public function zsbyg_list_del() {
	
	
	
		$caution = M ( 'info' )->where ( array (
				'IF_ID'=> I('get.id') ,
		) )->delete();
	
		if($caution){$this->success('刪除成功!');}else{$this->error('刪除失敗!');}
	
	}
	
	
	public function ly_list_del() {
	
	
	
		$caution = M ( 'apply' )->where ( array (
				'MA_ID'=> I('get.id') ,
		) )->delete();
	
		if($caution){$this->success('刪除成功!');}else{$this->error('刪除失敗!');}
	
	}



	/**
     * 上传文件的方法
     */
    public function upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();

        if(!$info) {// 上传错误提示错误信息
            //$this->error($upload->getError());
            $res = [
                'code' => 0,
                'msg'=>$upload->getError(),
                'data'=>''
            ];
        }else{// 上传成功
            $res = [
                'code' => 1,
                'msg'=>'上传成功',
                'data'=>$info,
                'rootPath'=>$upload->rootPath,
            ];
            //$this->success('上传成功！');
        }
        return $res;
    }

}