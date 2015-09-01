<?php 
namespace Home\Controller;
use Think\Controller;
class CommentController extends Controller{

	public function ajax(){
		if(empty($_SESSION['login']['id'])){
			echo "no";exit;
		}
		$post = M('comment');
		$a = time();
		$p = $_GET['re'];
		$arr = explode(',',$p);
		$das['send_id'] = $arr[0];
		$das['send_time'] = $a;
		$das['post_id'] = $arr[2];
		$das['conmment'] = $arr[1];
		$result = $post->add($das);
		if($result){
			echo 1;
		}else{
			echo 0;
		}
	}
}