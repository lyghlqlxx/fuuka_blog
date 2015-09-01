<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends EmptyController {
    public function index(){
        // 分类
    	$category = M('category');
    	$catelist = $category->where('form_id=0 AND display=1')->select();
    	foreach($catelist as &$val){
    		$val['son'] = $category->where('display=1 AND form_id='.$val['id'])->getField('id,name');
    	}
    	$this->assign('list',$catelist);
        // 评论
    	$comment = $category->table('bk_comment')->limit(7)->where('send_time')->select();
    	foreach($comment as &$val){
    		$val['conmment'] = mb_substr($val['conmment'],0,25,'utf-8').'...';
    		$val['img'] = $category->table('bk_user')->where('id='.$val['send_id'])->field('pic')->select();
    	}
    	$this->assign('com',$comment);
        //点击量列
        $dian = $category->table('bk_post')->order('view desc')->getField('id,title,pic',6);
        foreach($dian as &$val){
            if(isset($val['title']{20})){
                $val['title'] = mb_substr($val['title'],0,20,'utf-8').'...';
            }
        }
        $this->assign('dian',$dian);
        //猜你喜欢
        $model = M();
        $cai = $model->query("SELECT t1.id,t1.title,t1.pic FROM bk_post AS t1 JOIN (SELECT ROUND(RAND() * (SELECT MAX(id) FROM bk_post)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id ASC LIMIT 6");
        foreach($cai as &$val){
               if(isset($val['title']{20})){
                    $val['title'] = mb_substr($val['title'],0,20,'utf-8').'...';
                } 
        }
        $this->assign('cailist',$cai);
        // 文章
        $shumu = $category->table(__POST__)->count();
        $page = new \Think\Page($shumu,5);
        $button = $page->show();
        $date['display'] = 1;
        if($_GET['sid']){
            $date['term_id'] = $_GET['sid'];
        }
        //首页子
        if($_GET['lid']){
            $lids = $category->where('form_id='.$_GET['lid'])->getField('id',true);
            $lidss=count($lids);
            for($i=0;$i<$lidss;$i++){
                $ld .= $lids[$i].',';
            }
            $ld = substr($ld,0,strlen($ld)-1);
            $date['term_id'] = array('in',$ld);
        }

    	$post = $category->table(__POST__)->where($date)->order('addtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        if(!empty($post)) $this->assign('page',$button);
           
    	foreach($post as &$val){
    		$val['name'] = $category->where('id='.$val['term_id'])->getField('name');
            if(isset($val['title']{20})){
                $val['title'] = mb_substr($val['title'],0,20,'utf-8').'...';
            }
            if(isset($val['plain_text']{250})){
                $val['plain_text'] = mb_substr($val['plain_text'],0,200,'utf-8').'...';
            }
    		$val['lasttime'] = date('Y'.'年'.'m'.'月'.'d'.'日'.' H'.'点'.'i'.'分'.'s'.'秒',$val['addtime']);
    		$val['num'] = $category->table(__POST__)->where('post_id='.$val['id'])->count();
    		$val['post_author'] = $category->table(__POST__)->where('id='.$val['post_author'])->getField('name');
    	}
    	$this->assign('post',$post);
        $banner = M('banner');
        $banner = $banner->where('display=1')->limit(0,3)->select();
        $this->assign('banner',$banner);
    	// dump($post);
        $this->display();
    }
    //赞
    public function love(){
        if(!$_SESSION['login']['id']) return '0';
        if(!$_SESSION['login']['zan_'.$_GET['id']]){
            $post = M('Post');
            $love = $post->where('id='.$_GET['id'])->setInc('zan',1);
            if($love){
                echo '1';
                $_SESSION['login']['zan_'.$_GET['id']] = true;
            }else{
                echo '0';
            }   
        }

    }
    public function denglu(){
    $yzm = $_POST['yzm'];
    $action['email'] = $_POST['mail'];
    $action['password'] = md5($_POST['password']);
    $Verify = new \Think\Verify();
        $user = M('User');
        $result = $user->where($action)->select();
        $y = $Verify->check($yzm);                      //验证验证码是否正确
        if(!$y){
            $this->error('验证码错误');
        }
        if(!$result){
            $this->success('账号或者密码错误');
        }else{
            $xinxi = $result[0];
            $_SESSION['login'] = $xinxi;
            $this->assign('deng','yes');
            $this->display('index');
            $this->redirect('Home/Index/index');
        }
    }
    public function userDIV(){
        if(empty($_SESSION['login'])){
            $this->display('loginDiv');
        }else{
            $post = M('post');
            $newpost = $post->where('post_author='.$_SESSION['login']['id'])->order('addtime desc')->limit(1,2)->select();
            foreach($newpost as &$val){
                $val['title'] = mb_substr(htmlspecialchars($val['title']),0,12,'utf-8').'……';
            }
            $this->assign('userinfo',$_SESSION['login']);
            $this->assign('newpost',$newpost);
            $this->display('userDiv');
        }
    }
    public function loginout(){
        unset($_SESSION['login']);
        $this->index();
        $this->display('index');
    }
}
