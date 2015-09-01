<?php 
	namespace Admin\Model;
	use Think\Model;
	class UserModel extends Model{
		public function dopic($id,$path){
            $result = $this->where('id='.$id)->getField('pic');
			if($result != 'default.jpg'){
				$dopath = $path.$result;
				$ko = unlink($dopath);
				return $dopath;
			}
		}
	}
 ?>