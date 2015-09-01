<?php
    namespace Home\Controller;
    use Think\Controller;
    class EmptyController extends Controller{
        public function _Empty(){
        	// 分类
	    	$category = M('category');
	    	$catelist = $category->where('form_id=0 AND display=1')->select();
	    	foreach($catelist as &$val){
	    		$val['son'] = $category->where('display=1 AND form_id='.$val['id'])->getField('id,name');
	    	}
	    	$this->assign('list',$catelist);
            $this->display('Error/404');
        }
    }
?>
