<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends ConmonController{
		public function Index(){
			$information = M('message');
			$num = $information->where('goal_id='.$_SESSION['login']['id'].' AND is_read=0')->count();
			$this->assign('count',$num);
			$this->display();
		}
		public function getSession(){
			dump($_SESSION['login']);
		}
    }
