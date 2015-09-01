<?php
    namespace Admin\Controller;
	use Think\Controller;
	class RegController extends Controller{
        public function index(){
            $this->display();
        }

        public function doreg(){
        	$_POST['addtime'] = time();
        	$_POST['lasttime'] = time();
        	$_POST['password'] = md5($_POST['password']);
        	$user = M('user');
        	$user->create($_POST);
        	$result = $user->add();
        	if($result){
        		$_SESSION['login'] = $user->where('id='.$result)->select()[0];
        		echo '1';
        	}else{
        		echo '0';
        	}
        }
    }
?>
