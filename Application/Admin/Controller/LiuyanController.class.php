<?php

namespace Admin\Controller;

use Think\Controller;

header('Content-type:text/html;charset=utf-8');

class LiuyanController extends BaseController {
	
	private $edit_fields   = array('c_photo', 'c_name','c_content', 'c_time');

	public function index() {
		
		$Liuyan = D('Liuyan');		
		$count = $Liuyan -> count();
		$p = getpage($count, C('PAGE_SIZE'));
		$show = $p -> show();
		$list = $Liuyan -> page(I('get.p')) -> limit(C('PAGE_SIZE')) ->order('add_time desc')-> select();
		$newIp = new \Org\Util\IP();
        for ($i=0; $i < count($list); $i++) {
          $list[$i]['ip'] = getIpaddr($list[$i]['ip'],$newIp);
        } 
		$this -> assign('list', $list);
		$this -> assign('page', $show);
		$this -> display();
	}

	public function edit($id = 0) {
		$obj = D('Liuyan');
		
		if ($id = (int)$id) {
			if (!$detail = $obj -> find($id)) {
				$this -> error('请选择要回复的留言');
			}
			if (IS_POST) {
				$data = $this -> editCheck();
				$data['id'] = $id;
				if ($obj -> save($data)) {
					$this -> success('操作成功', U('Liuyan/index'));
					return;
				} else {
					$this -> error('操作失败');
					return;
				}
			} else {
				$this -> assign('detail', $detail);
				$this -> display();
			}
		} else {
			$this -> error('请选择要回复的留言');
		}
	}

	private function editCheck() {

		$param = I('post.');
		$data = $this -> checkFields($param['data'], $this -> edit_fields);
		$update_time = strtotime(date("Y-m-d H:i:s", time()));
		$data['c_content'] = $_POST['c_content'];
		if ($data['c_content'] == null) {
			$this -> error('回复内容不能为空');
			return;
		}		
		$data['c_photo'] = session('admin.userimg');
		$data['c_name'] = session('admin.username');	
		$data['c_time'] = $update_time;
		return $data;
	}


	public function del() {
		$Liuyan = D('Liuyan');
		$id = I('get.id');
		$result = $Liuyan -> where('id =' . $id . '') -> delete();
		if ($result) {
			$this -> success('删除成功', U('Liuyan/index'));
		} else {
			$this -> error('删除失败');
		}
	}

}
