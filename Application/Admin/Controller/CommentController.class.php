<?php 
	namespace Admin\Controller;
    use Think\Controller;
    header("content-type:text/html;charset=utf-8");
    class CommentController extends ConmonController{
    	public function index(){
    		$com = M('Comment');
            $shumu = $com->count();
            $page = new \Think\Page($shumu,5);
            $button = $page->show();
            $coms = $com->limit($page->firstRow.','.$page->listRows)->select();
            $this->assign('page',$button);
            foreach($coms as &$val){
                $val['send_time'] = date('Y-m-d H:i:s',$val['send_time']);
            }
            $this->assign('list',$coms);
            $this->display();
    	}
    	public function paixu(){
    		$com = M('Comment');
            $shumu = $com->count();
            $page = new \Think\Page($shumu,5);
            $button = $page->show();
            $coms = $com->order('send_time desc')->limit($page->firstRow.','.$page->listRows)->select();
            $this->assign('page',$button);
            foreach($coms as &$val){
                $val['send_time'] = date('Y-m-d H:i:s',$val['send_time']);
                $val['conmment'] = mb_substr($val['conmment'], 0, 8, 'utf-8');
            }
            $this->assign('list',$coms);
            $this->display('index');
    	}
        public function del(){
                $id = $_POST['id'];
                $coms = new \Think\Model('Comment');
                $result = $coms->delete($id);
                if($result){
                    echo '1';
                }else{
                    echo '2';
                }
        }
        public function ss(){
            $name = $_GET['name'];
            $lei = $_GET['lei'];
            // dump($lei);
            if($lei == '1'){
                $cha['send_id'] = array('like','%'.$name.'%');
            }elseif($lei == '2'){
                $cha['post_id'] = array('like','%'.$name.'%');
            }
            $coms = M('Comment');
            $shumu = $coms->where($cha)->count();
            $page = new \Think\Page($shumu,5);
            $comss = $coms->where($cha)->limit($page->firstRow.','.$page->listRows)->select();
            if(empty($comss)){
                $this->assign('lists','暂无数据哦');
            }else{
                foreach($comss as &$val){
                 $val['send_time'] = date('Y-m-d H:i:s',$val['send_time']);
                 $val['conmment'] = mb_substr($val['conmment'], 0, 2, 'utf-8');
            }
                $this->assign('list',$comss);
            }
            $button = $page->show();
            
            $this->assign('names',$name);
            $this->assign('lei',$lei);
            $this->assign('page',$button);
            $this->display('index');
        }
    }
 ?>
    