<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function login(){
        $this->display();
    }
    
    public function zhuce(){
    	$user = M('User');
    	$yzm = $_POST['yzm'];
    	$action['username'] = $_POST['username'];
    	$action['password'] = $_POST['password'];
    	$pwds = $_POST['pwds'];
    	if($action['password']!=$pwds){
    		$this->error('密码不一致','login',2);
    	}
    	$Verifys = new \Think\Verify();
    	$ys = $Verifys->check($yzm);
    	// dump($_SESSION);exit;
    	if(!$ys){
			$this->error('验证码错误','login',2);
		}
		$result = $user->data($action)->add();
		if($result){
			$this->assign('yes','cg');
			$this->display('login');
		}

    }	
    public function userdoadd(){
            $user = M('User');
            $username = $user->where($_POST)->select();
            if($username){
                echo 'yes';
            }else{
                echo 'no';
            }
        }
    public function yzm(){
		 	ob_clean();
        	$Verify = new \Think\Verify();
        	$Verify->entry();
        }

    public function tc(){
        	unset($_SESSION['login']);
        	$this->redirect('home/login/login');
        }
    public function add(){
        if (think_send_mail($_POST['mail'], $_POST['title'], $subject = '一起来欢乐!',$_POST['content'], $attachment = null)) {
        $this->success('发送成功！');
        } else {
        $this->error('发送失败');
        }
}
    public function email(){
        display('test');
    }
}
?>
