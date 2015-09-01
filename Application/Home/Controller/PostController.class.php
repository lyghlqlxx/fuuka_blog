<?php 
namespace Home\Controller;
use Think\Controller;
class PostController extends Controller{
	public function index(){
		//分类导航
    	$category = M('category');
    	$catelist = $category->where('form_id=0 AND display=1')->select();
    	foreach($catelist as &$val){
    		$val['son'] = $category->where('display=1 AND form_id='.$val['id'])->getField('id,name');
    	}
    	$this->assign('catelist',$catelist);
    	//文章
		$post = M('Post');
		$postlist = $post->where('id='.$_GET['id'])->select();
		$postlist[0]['term'] = $post->table('bk_category')->where('id='.$postlist[0]['term_id'])->getField('name');
		$postlist[0]['name'] = $post->table('bk_user')->where('id='.$postlist[0]['post_author'])->getField('name');
		$postlist[0]['num'] = $post->table('bk_comment')->where('post_id='.$_GET['id'])->count();
		if(empty($_SESSION['login'])){
			// echo '请登录';
		}else{
			$postlist[0]['usname'] = $_SESSION['login']['username'];
		}
		$postlist[0]['lasttime'] = date('Y'.'年'.'m'.'月'.'d'.'日'.' H'.'点'.'i'.'分'.'s'.'秒',$postlist[0]['addtime']);
		$this->assign('list',$postlist[0]);

		$jinqi = $post->where('display =1 ')->order('addtime desc')->getField('id,title',5);
		foreach($jinqi as &$val){
			if(isset($val{20})){
				$val = mb_substr($val,0,20,'utf-8').'...';
			}
		}
		$this->assign('jinqi',$jinqi);
    	//文章浏览数
		$post->where('id='.$_GET['id'])->setInc('view',1);
		// 评论
    	$comment = $post->table('bk_comment')->limit(7)->where('post_id='.$_GET['id'])->select();
    	foreach($comment as &$val){
    		$val['conmment'] = $val['conmment'];
    		$val['img'] = $post->table('bk_user')->where('id='.$val['send_id'])->field('pic')->select();
 			$adds = $post->table('bk_user')->where('id='.$val['send_id'])->field('username')->select();
    		$val['send_id'] = $adds[0]['username'];
    		$val['send_time'] = date('Y'.'年'.'m'.'月'.'d'.'日'.' H'.'点'.'i'.'分'.'s'.'秒',$val['send_time']);
    	}
    	$this->assign('session',$_SESSION['login']);
    	$this->assign('com',$comment);
    	$this->display();
	}
	//喜欢
	public function love(){
		if($_SESSION['login']['id']) return '0';
		if(!$_SESSION['login']['zan_'.$_POST['id']]){
			$post = M('Post');
	    	$love = $post->where('id='.$_POST['id'])->setInc('zan',1);
	    	if($love){
	    		echo '1';
	    		$_SESSION['login']['zan_'.$_POST['id']] = true;
	    	}else{
	    		echo '0';
	    	}	
		}   	
    }
    public function message(){
    	if($_SESSION['login']['id']){
    		$message = M('Message');
    		$date['send_id'] = $_SESSION['login']['id'];
    		$date['goal_id'] = $_POST['goal_id'];
    		$date['send_time'] = time();
    		$date['message'] = $_POST['content'];
    		$message->create($date);
    		$message->add();
    		echo '1';
    	}else{
    		echo '0';
    	}
    }
	public function add(){
        $cate = M('category');
        $catelist = $cate->where('form_id=0')->select();
        foreach($catelist as &$val){
            $val['son'] = $cate->where('form_id='.$val['id'])->getField('id,name',true);
        }
        $this->assign('cate',$catelist);
        $this->display('postadd');
    }
    public function doadd(){
        $_POST['post_author'] = $_SESSION['login']['id'];
        $_POST['addtime'] = time();
        $_POST['lasttime'] = time();
        $preg = '#<img\ssrc=\"(.*?)\"\sstyle=\".*?\"\stitle=\".*?\"\/>|<img\ssrc=\"(.*?)\"\stitle=\".*?\"\/>#';
        $result = preg_match($preg,$_POST['content'],$match);
        if($result){
            foreach ($match as $key=>$val) {
                if(@in_array(end(explode('.', $val)),array('jpg','png','bmp','gif'))){
                    $_POST['pic'] = $match[$key];
                }
            }
        }else{
            $_POST['pic'] = '/ueditor/php/upload/image/default.jpg';
        }
        $post = M('post');
        $post->data($_POST);
        $result = $post->add();
        if($result){
            echo '1';
        }else{
            echo '0';
        }
    }
    public function postlist(){
        $post = M('post');
        $total = $post->where('display=1 AND post_author='.$_SESSION['login']['id'])->count();
        $page = new \Think\Page($total,10);
        $button = $page->show();
        $postlist = $post->where('display=1 AND post_author='.$_SESSION['login']['id'])->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($postlist as &$val) {
            $val['post_author'] = $_SESSION['login']['username'];
            $val['title'] = mb_substr(htmlspecialchars($val['title']),0,12,'utf-8').'……';
            $val['content'] = mb_substr($val['plain_text'],0,12,'utf-8').'……';
            $val['addtime'] = date('Y-m-d',$val['addtime']);
            if($val['lasttime'] == 0){
                $val['lasttime'] = $val['addtime'];
            }else{
                $val['lasttime'] = date('Y-m-d',$val['lasttime']);
            }
            if($val['term_id'] == '8'){
                $val['term_id'] = '未分类';
            }else{
                $val['term_id'] = $post->table(__CATEGORY__)->where('id='.$val['term_id'])->getField('name');
            }
        }
        $this->assign('page',$button);
        $this->assign('list',$postlist);
        $this->display();
    }
    public function draft(){
        $post = M('post');
        $total = $post->where('display=0 AND post_author='.$_SESSION['login']['id'])->count();
        $page = new \Think\Page($total,10);
        $button = $page->show();
        $postlist = $post->where('display=0 AND post_author='.$_SESSION['login']['id'])->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($postlist as &$val) {
            $val['post_author'] = $_SESSION['login']['username'];
            $val['title'] = mb_substr(htmlspecialchars($val['title']),0,12,'utf-8').'……';
            $val['content'] = mb_substr($val['plain_text'],0,12,'utf-8').'……';
            $val['addtime'] = date('Y-m-d',$val['addtime']);
            $val['lasttime'] = date('Y-m-d',$val['lasttime']);
            if($val['term_id'] == '8'){
                $val['term_id'] = '未分类';
            }else{
                $val['term_id'] = $post->table(__CATEGORY__)->where('id='.$val['term_id'])->getField('name');
            }
        }
        $this->assign('page',$button);
        $this->assign('list',$postlist);
        $this->display('postdraft');
    }
    public function comment(){
        $com = M('Comment');
        $shumu = $com->where('send_id='.$_SESSION['login']['id'])->count();
        $page = new \Think\Page($shumu,10);
        $button = $page->show();
        $coms = $com->where('send_id='.$_SESSION['login']['id'])->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('page',$button);
        foreach($coms as &$val){
            $val['send_time'] = date('Y-m-d H:i:s',$val['send_time']);
        }
        $this->assign('list',$coms);
        $this->display();
    }
    public function del(){
        $post = M('post');
        if($post->delete($_POST['id'])){
            echo '1';
        }else{
            echo '0';
        }
    }
    public function comment_del(){
        $id = $_POST['id'];
        $coms = new \Think\Model('Comment');
        $result = $coms->delete($id);
        if($result){
            echo '1';
        }else{
            echo '2';
        }
    }
    public function edit(){
        $id = $_GET['id'];
        $post = M('post');
        $cate = M('category');
        $post = $post->where('id='.$id)->select();
        $catelist = $cate->where('form_id=0')->select();
        foreach($catelist as &$val){
            $val['son'] = $cate->where('form_id='.$val['id'])->getField('id,name',true);
        }
        $this->assign('cate',$catelist);
        $this->assign('post',$post[0]);
        $this->display('postedit');
    }
    public function doedit(){
        $_POST['post_author'] = $_SESSION['login']['id'];
        $_POST['lasttime'] = time();
        $preg = '#<img\ssrc=\"(.*?)\"\sstyle=\".*?\"\stitle=\".*?\"\/>|<img\ssrc=\"(.*?)\"\stitle=\".*?\"\/>#';
        $result = preg_match($preg,$_POST['content'],$match);
        if($result){
            foreach ($match as $key=>$val) {
                if(@in_array(end(explode('.', $val)),array('jpg','png','bmp','gif'))){
                    $_POST['pic'] = $match[$key];
                }
            }
        }else{
            $_POST['pic'] = '/ueditor/php/upload/image/default.jpg';
        }
        $post = M('post');
        $result = $post->save($_POST);
        if($result){
            echo '1';
        }else{
            echo '0';
        }
    }
    public function paixu(){
        $com = M('Comment');
        $shumu = $com->where('send_id='.$_SESSION['login']['id'])->count();
        $page = new \Think\Page($shumu,5);
        $button = $page->show();
        $coms = $com->where('send_id='.$_SESSION['login']['id'])->order('send_time desc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('page',$button);
        foreach($coms as &$val){
            $val['send_time'] = date('Y-m-d H:i:s',$val['send_time']);
            $val['conmment'] = mb_substr($val['conmment'], 0, 8, 'utf-8');
        }
        $this->assign('list',$coms);
        $this->display('comment');
    }

}
?>
