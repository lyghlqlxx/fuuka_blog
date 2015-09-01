<?php 
	namespace Admin\Controller;
	use Think\Controller;
	class InformationController extends ConmonController{
		public function forlist(){
			$information = M('message');
			$num = $information->count('id');
			$page = new\Think\Page($num,5);
			$button = $page->show();
			$inforlist = $information->order('send_time desc')->where('goal_id='.$_SESSION['login']['id'])->limit($page->firstRow.','.$page->listRows)->select();
			foreach($inforlist as &$val){
				$val['send_id'] = $information->table('bk_user')->where('id='.$val['send_id'])->getField('name');
				$val['send_time'] = date('Y-m-d h-i-s',$val['send_time']);
				$val['name'] = $_SESSION['login']['name'];
			}
			$this->assign('list',$inforlist);
			$this->assign('page',$button);
			$this->display();
		}

		public function domessage(){
			$_POST['is_read'] = 1;
			$information = M('message');
			$inforlist = $information->where('id='.$_POST['id'].' AND goal_id='.$_SESSION['login']['id'])->getField('message');
			$information->where('id='.$_POST['id'])->save($_POST);
			echo $inforlist;
		}

		public function del(){
			$information = M('message');
			$result = $information->delete($_POST['id']);
			if($result){
				echo '1';
			}else{
				echo '2';
			}
		}

		public function read(){
			if($_GET['read']==0){
				$read = '0';
			}else{
				$read = '1';
			}
			$data['goal_id'] = $_SESSION['login']['id'];
			$date['is_read'] = $read;
			$information = M('message');
			$num = $information->count('id');
			$page = new\Think\Page($num,5);
			$button = $page->show();
			$inforlist = $information->where($date)->order('send_time desc')->limit($page->firstRow.','.$page->listRows)->select();
			foreach($inforlist as &$val){
				$val['send_id'] = $information->table('bk_user')->where('id='.$val['send_id'])->getField('name');
				$val['send_time'] = date('Y-m-d h-i-s',$val['send_time']);
				$val['name'] = $_SESSION['login']['name'];
			}

			$this->assign('list',$inforlist);
			$this->assign('page',$button);
			$this->display('forlist');
		}
	}
 ?>