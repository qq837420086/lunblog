<?php
namespace Admin\Controller;
use Think\Controller;
class ArticleController extends BaseController {
    private $createlife_fields = array('a_title', 'cate_id ','photo','a_keyword','a_remark','a_content','a_red','a_type','a_views','a_writer','create_time','create_ip','last_time','a_from');
    private $editlife_fields = array('a_title', 'cate_id ','photo','a_keyword','a_remark','a_content','a_red','a_type','a_views','a_writer','create_time','create_ip','last_time','a_from');
    private $create_fields = array('cate_name', 'orderby',);
    private $edit_fields = array('cate_name', 'orderby', );	
	
    public function index(){
        $Article = D('Article');
        $article_cate = D('article_cate');
        $count = $Article->count(); 
        $p = getpage($count,C('PAGE_SIZE'));
        $show = $p->show(); 
        $list = $Article->page(I('get.p'))->order('a_id desc')->limit(C('PAGE_SIZE'))->select();
        $list1 = $article_cate->select();
        $this->assign('page', $show); 
        $this->assign('list', $list);
        $this->assign('list1', $list1);
        $this->display();
    }
 
	 /*
	  * 添加文章
	  */   
    public function add(){
    	
        $article_cate = D('article_cate');
        $list = $article_cate->select();//查询分类		
        if (IS_POST) {
            $data = $this->addCheck();
            $obj = D('article');
            if ($obj->add($data)) {
                $this->success("保存成功", U('article/index'));
                return;
            }else {
            $this->error('操作失败！');
            return;
         } 
        }
        $this->assign('list', $list);
        $this->display();
    }

    private function addCheck() {
    	
        $param = I('post.');
        $data = $this->checkFields($param["data"], $this->createlife_fields);
        $create_time = strtotime(date("Y-m-d H:i:s",time()));
		$last_time = strtotime(date("Y-m-d H:i:s",time()));
        $data['a_title'] = trim(I('post.a_title', '', 'htmlspecialchars'));        
        $data['cate_id'] = trim(I('post.cate_id', '', 'htmlspecialchars'));
        $data['photo'] = trim(I('post.photo', '', 'htmlspecialchars'));
		$data['a_keyword'] = trim(I('post.a_keyword', '', 'htmlspecialchars'));
		$data['a_remark'] = trim(I('post.a_remark', '', 'htmlspecialchars'));
		$data['a_content'] = $_POST['a_content'];
		$data['a_red'] = trim(I('post.a_red', '', 'htmlspecialchars'));
		$data['a_type'] = trim(I('post.a_type', '', 'htmlspecialchars'));
		$data['a_views'] = trim(I('post.a_views', '', 'htmlspecialchars'));
		$data['a_writer'] = trim(I('post.a_writer', '', 'htmlspecialchars'));
		$data['create_time'] = $create_time;
		$data['create_ip'] = get_client_ip();
       	$data['last_time'] = $last_time;
		$data['a_from'] = getOS();	
		
        
		if (empty($data['a_title'])) {
            $this->error('标题不能为空');
        }	
        if (empty($data['a_keyword'])) {
            $this->error('关键字不能为空');
        }        
        if (empty($data['a_remark'])) {
            $this->error('文章描述不能为空');
        }
		if (empty($data['a_content'])) {
            $this->error('内容不能为空');
        }

        return $data;
    }
 
 
	 /*
	  * 编辑文章
	  */   
    public function edit($a_id = 0) {
        if ($a_id = (int) $a_id) {
            $obj = D('article');
            $article_cate = D('article_cate');
            $list = $article_cate->select();
            if (!$detail = $obj->find($a_id)) {
                $this->error('请选择要编辑的信息');
            }
            if (IS_POST) {
                $data = $this->editCheck();
                $data['a_id'] = $a_id;  
                if ($obj->save($data)) {
                    $this->success('操作成功', U('Article/index'));
                } else {
                    $this->error('操作失败');
                }
            } else {
                $this->assign('list', $list);
                $this->assign('detail', $detail);
                $this->display();
            }
        } else {
            $this->error('请选择要编辑的信息');
        }
    }

    private function editCheck() {

		$param = I('post.');
        $data = $this->checkFields($param["data"], $this->createlife_fields);
		$last_time = strtotime(date("Y-m-d H:i:s",time()));
        $data['a_title'] = trim(I('post.a_title', '', 'htmlspecialchars'));        
        $data['cate_id'] = trim(I('post.cate_id', '', 'htmlspecialchars'));
        $data['photo'] = trim(I('post.photo', '', 'htmlspecialchars'));
		$data['a_keyword'] = trim(I('post.a_keyword', '', 'htmlspecialchars'));
		$data['a_remark'] = trim(I('post.a_remark', '', 'htmlspecialchars'));
		$data['a_content'] = $_POST['a_content'];
		$data['a_red'] = trim(I('post.a_red', '', 'htmlspecialchars'));
		$data['a_type'] = trim(I('post.a_type', '', 'htmlspecialchars'));
		$data['a_views'] = trim(I('post.a_views', '', 'htmlspecialchars'));
		$data['a_writer'] = trim(I('post.a_writer', '', 'htmlspecialchars'));
		$data['create_ip'] = get_client_ip();
       	$data['last_time'] = $last_time;
		$data['a_from'] = getOS();			       
		if (empty($data['a_title'])) {
            $this->error('标题不能为空');
        }	
        if (empty($data['a_keyword'])) {
            $this->error('关键字不能为空');
        }        
        if (empty($data['a_remark'])) {
            $this->error('文章描述不能为空');
        }
		if (empty($data['a_content'])) {
            $this->error('内容不能为空');
        }

        return $data;
    }                     
 
 
	 /*
	  *删除文章
	  */   
    public function del() {
        $article = D('article');
        $a_id = I('get.a_id');
        $result = $article->where('a_id =' . $a_id . '')->delete();
        if ($result) {
            $this->success('删除成功', U('Article/index'));
        } else {
            $this->error('删除失败');
        }
    }

	/*
	 *文章分类 
	 */	
	public function indextype() {
        $article_cate = D('article_cate');
        $count = $article_cate->count(); // 查询满足要求的总记录数 
        $p = getpage($count, C('PAGE_SIZE'));
        $show = $p->show();
        $list = $article_cate->page(I('get.p'))->limit(C('PAGE_SIZE'))->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	/*
	 * 添加文章分类
	 */
    public function addtype() {
        $obj = D('article_cate');
        if (IS_POST) {
            $data = $this->createCheck1();
          
            if ($obj->add($data)) {
                $this->success("保存成功", U('article/indextype'));
                return;
            } else {
                $this->error('操作失败！');
                return;
            }
        }
        $this->display();
    }
	


    private function createCheck1() {

        $param = I('post.');
        $data = $this->checkFields($param["data"], $this->create_fields);
        $data['cate_name'] = trim(I('post.cate_name', '', 'htmlspecialchars'));
        $data['orderby'] =  trim(I('post.orderby', '', 'htmlspecialchars'));
        if ($data['cate_name'] == null) {
            $this->error('分类名称不能为空');
            return;
        }
        if ($data['orderby'] == null) {
            $this->error('排序不能为空');
            return;
        }
        
        return $data;
    }
	
	/*
	 * 编辑文章分类
	 */
    public function edittype($cate_id = 0) {
        $obj = D('article_cate');
        if ($cate_id = (int) $cate_id) {
            if (!$detail = $obj->find($cate_id)) {
                $this->error('请选择要编辑的分类');
            }
            if (IS_POST) {
                $data = $this->editCheck1();
                $data['cate_id'] = $cate_id;
                if ($obj->save($data)) {
                    $this->success('操作成功', U('article/indextype'));
                    return;
                } else {
                    $this->error('操作失败');
                    return;
                }
            } else {

                $this->assign('detail', $detail);
                $this->display();
            }
        } else {
            $this->error('请选择要编辑的分类');
        }
    }

    private function editCheck1() {

        $param = I('post.');
        $data = $this->checkFields($param['data'], $this->edit_fields);
        $data['cate_name'] = trim(I('post.cate_name', '', 'htmlspecialchars'));
        $data['orderby'] =  trim(I('post.orderby', '', 'htmlspecialchars'));
        if ($data['cate_name'] == null) {
            $this->error('分类名称不能为空');
            return;
        }
        if ($data['orderby'] == null) {
            $this->error('排序不能为空');
            return;
        }
        
        return $data;
    }

	/*
	 * 删除分类
	 */
     public function deltype() {
        $article_cate = D('article_cate');
        $cate_id = I('get.cate_id');
        $result = $article_cate->where('cate_id =' . $cate_id . '')->delete();
        if ($result) {
            $this->success('删除成功', U('article/indextype'));
        } else {
            $this->error('删除失败');
        }
    }

}

      