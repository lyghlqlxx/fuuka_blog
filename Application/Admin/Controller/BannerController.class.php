<?php 
	namespace Admin\Controller;
	use Think\Controller;
	class BannerController extends ConmonController{
		public function bannerlist(){
			$banner = M('banner');
			$list = $banner->select();
			foreach ($list as &$val) {
				if($val['display'] == '1'){
					$val['display'] = '已显示';
				}elseif($val['display'] == '0'){
					$val['display'] = '未显示';
				}
			}
			if($_GET['type'] == 'add'){
                $this->assign('type','add');
            }else{
                $this->assign('type','index');
            }
			$this->assign('list',$list);
			$this->display('list');
		}
		public function del(){
			$banner = M('banner');
			$result = $banner->where('id='.$_POST['id'])->delete();
			if($result){
				echo '1';
			}else{
				echo '0';
			}
		}
		public function edit(){
			if(!empty($_POST['filename'])){
				$filename = $_POST['filename'];
				$type = '.jpg';
				//end(explode('.',$_FILES['myFile']['name']));
				$path = './public/banner/'.$filename.$type;
				move_uploaded_file($_FILES['myFile']['tmp_name'],$path);
			}else{
				$banner = M('banner');
				$result = $banner->save($_POST);
				if($result){
					echo '1';
				}else{
					echo '0';
				}
			}

		}
		public function add(){
			if(!empty($_POST['filename'])){
				$filename = $_POST['filename'];
				$type = '.jpg';
				//end(explode('.',$_FILES['myFile']['name']));
				$path = './public/banner/'.$filename.$type;
				move_uploaded_file($_FILES['myFile']['tmp_name'],$path);
			}
			$banner = M('banner');
			$banner->create($_POST);
			$result = $banner->add();
			if($result){
				echo '1';
			}else{
				echo '0';
			}
		}
		public function status(){
			$banner = M('banner');
			$result = $banner->save($_POST);
			if($result){
				echo '1';
			}else{
				echo '0';
			}

		}
	}
?>