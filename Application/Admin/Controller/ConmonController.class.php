<?php 
	namespace Admin\Controller;
	use Think\Controller;
	class ConmonController extends Controller{
		public function _initialize(){
			if(empty($_SESSION['login']['id'])){
				$this->redirect('Admin/Login/login');
			}elseif($_SESSION['login']['level'] == 1){
				$this->redirect('Home/Index/index');
			}
		}
	}
 ?>