<?php

namespace Admin\Controller;

use Think\Controller;

header('Content-type:text/html;charset=utf-8');

class SaidController extends BaseController {

	private $create_fields = array('s_content', 's_img','s_from', 's_writer', 's_view', 'create_ip','create_time');
	private $edit_fields   = array('s_content', 's_img','s_from', 's_writer', 's_view', 'create_ip','create_time');

	public function index() {
		
		$Said = D('Said');		
		$count = $Said -> count();
		$p = getpage($count, C('PAGE_SIZE'));
		$show = $p -> show();
		$list = $Said -> page(I('get.p')) -> limit(C('PAGE_SIZE')) ->order('create_time desc')-> select();
		$this -> assign('list', $list);
		$this -> assign('page', $show);
		$this -> display();
	}

	public function add() {
		$obj = D('Said');
		
		if (IS_POST) {
			$data = $this -> createCheck();

			if ($obj -> add($data)) {
				$this -> success("保存成功", U('Said/index'));
				return;
			} else {
				$this -> error('操作失败！');
				return;
			}
		}
		$this -> display();
	}

	private function createCheck() {

		$param = I('post.');
		$data = $this -> checkFields($param["data"], $this -> create_fields);
		$create_time = strtotime(date("Y-m-d H:i:s", time()));
		$data['s_content'] = $_POST['s_content'];
		if ($data['s_content'] == null) {
			$this -> error('内容不能为空');
			return;
		}
		$data['create_time'] = $create_time;		
		$data['s_from'] = getOS();
		$data['s_img'] = session('admin.userimg');	
		$data['s_writer'] = trim(I('post.s_writer', '', 'htmlspecialchars'));
		$data['s_view'] = trim(I('post.s_view', '', 'htmlspecialchars'));
		$data['create_ip'] = get_client_ip();

		return $data;
	}

	public function edit($s_id = 0) {
		$obj = D('Said');
		
		if ($s_id = (int)$s_id) {
			if (!$detail = $obj -> find($s_id)) {
				$this -> error('请选择要编辑的说说');
			}
			if (IS_POST) {
				$data = $this -> editCheck();
				$data['s_id'] = $s_id;
				if ($obj -> save($data)) {
					$this -> success('操作成功', U('Said/index'));
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
			$this -> error('请选择要编辑的说说');
		}
	}

	private function editCheck() {

		$param = I('post.');
		$data = $this -> checkFields($param['data'], $this -> edit_fields);
		$create_time = strtotime(date("Y-m-d H:i:s", time()));
		$data['s_content'] = $_POST['s_content'];
		if ($data['s_content'] == null) {
			$this -> error('内容不能为空');
			return;
		}		
		$data['s_writer'] = trim(I('post.s_writer', '', 'htmlspecialchars'));
		$data['s_view'] = trim(I('post.s_view', '', 'htmlspecialchars'));
		
		return $data;
	}


	public function del() {
		$said = D('said');
		$s_id = I('get.s_id');
		$result = $said -> where('s_id =' . $s_id . '') -> delete();
		if ($result) {
			$this -> success('删除成功', U('Said/index'));
		} else {
			$this -> error('删除失败');
		}
	}

}
