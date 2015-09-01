<?php 
	namespace Admin\Controller;
	use Think\Controller;
	class LoginController extends Controller{
		public function Login(){
			$this->display('Login');
		}
		public function dologin(){
			$coo = $_POST['checkbox'];
			$action['email'] = $_POST['email'];
			$action['password'] = md5($_POST['password']);
			$user = M('User');
			$result = $user->where($action)->select();
			if(!$result){
				echo '0';
			}else{
                $info = $result[0];
                $_SESSION['login'] = $info;
                $level = $info['level'];
                if($level!=0){
				    echo '2';
			    }else{
				    echo '1';
                }	
			}
		}
		 public function yzm(){
		 	ob_clean();
        	$Verify = new \Think\Verify();
        	$Verify->entry();
        }
        public function tc(){
        	unset($_SESSION['login']);
        	$this->redirect('admin/login/login');
        }
	}
 ?>
