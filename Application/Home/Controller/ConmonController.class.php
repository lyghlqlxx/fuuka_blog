<?php 
	namespace Home\Controller;
	use Think\Controller;
	class ConmonController extends Controller{
		public function _initialize(){
			if(empty($_SESSION['login']['id'])){
				$this->redirect('Home/Index/index');
			}elseif($_SESSION['login']['level'] == 0){
				$this->redirect('Admin/Index/index');
			}
		}
	}
 ?>

