<?php 
    namespace Admin\Controller;
    use Think\Controller;
    header("content-type:text/html;charset=utf-8");
    class PostController extends ConmonController{
    	public function add(){
    		$cate = M('category');
    		$catelist = $cate->where('form_id=0')->select();
    		foreach($catelist as &$val){
    			$val['son'] = $cate->where('form_id='.$val['id'])->getField('id,name',true);
    		}

            $this->assign('cate',$catelist);
    		$this->display();
    	}

    	public function doadd(){
    		$_POST['post_author'] = $_SESSION['login']['id'];
    		$_POST['addtime'] = time();
            $_POST['lasttime'] = time();
            $preg = '#<img\ssrc=\"(.*?)\"\sstyle=\".*?\"\stitle=\".*?\"\/>|<img\ssrc=\"(.*?)\"\stitle=\".*?\"\/>#';
            $result = preg_match($preg,$_POST['content'],$match);
            if($result){
                foreach ($match as $key=>$val) {
                    if(@in_array(end(explode('.', $val)),array('jpg','png','bmp','gif'))){
                        $_POST['pic'] = $match[$key];
                    }
                }
            }else{
                $_POST['pic'] = '/ueditor/php/upload/image/default.jpg';
            }
    		$post = M('post');
    		$post->data($_POST);
    		$result = $post->add();
    		if($result){
    			echo '1';
    		}else{
    			echo '0';
    		}
    	}
    	public function postlist(){
            $post = M('post');
            $total = $post->where('display=1')->count();
            $page = new \Think\Page($total,10);
            $button = $page->show();
            $postlist = $post->where('display=1')->limit($page->firstRow.','.$page->listRows)->select();
            foreach ($postlist as &$val) {
                $val['post_author'] = $post->table(__USER__)->where('id='.$val['post_author'])->getField('username');
                $val['title'] = mb_substr(htmlspecialchars($val['title']),0,12,'utf-8').'……';
                $val['content'] = mb_substr($val['plain_text'],0,12,'utf-8').'……';
                $val['addtime'] = date('Y-m-d',$val['addtime']);
                if($val['lasttime'] == 0){
                    $val['lasttime'] = $val['addtime'];
                }else{
                    $val['lasttime'] = date('Y-m-d',$val['lasttime']);
                }
                if($val['term_id'] == '8'){
                    $val['term_id'] = '未分类';
                }else{
                    $val['term_id'] = $post->table(__CATEGORY__)->where('id='.$val['term_id'])->getField('name');
                }
            }
            $this->assign('page',$button);
            $this->assign('list',$postlist);
            $this->display('list');
    	}
        public function del(){
            $post = M('post');
            if($post->delete($_POST['id'])){
                echo '1';
            }else{
                echo '0';
            }
        }
        public function edit(){
            $id = $_GET['id'];
            $post = M('post');
            $cate = M('category');
            $post = $post->where('id='.$id)->select();
            $catelist = $cate->where('form_id=0')->select();
            foreach($catelist as &$val){
                $val['son'] = $cate->where('form_id='.$val['id'])->getField('id,name',true);
            }
            $this->assign('cate',$catelist);
            $this->assign('post',$post[0]);
            $this->display();
        }
        public function doedit(){
            $_POST['post_author'] = $_SESSION['login']['id'];
            $_POST['lasttime'] = time();
            $post = M('post');
            $preg = '#<img\ssrc=\"(.*?)\"\sstyle=\".*?\"\stitle=\".*?\"\/>|<img\ssrc=\"(.*?)\"\stitle=\".*?\"\/>#';
            $result = preg_match($preg,$_POST['content'],$match);
            if($result){
                foreach ($match as $key=>$val) {
                    if(@in_array(end(explode('.', $val)),array('jpg','png','bmp','gif'))){
                        $_POST['pic'] = $match[$key];
                    }
                }
            }else{
                $_POST['pic'] = '/ueditor/php/upload/image/default.jpg';
            }
            $result = $post->save($_POST);
            if($result){
                echo '1';
            }else{
                echo '0';
            }
        }

        public function draft(){
            $post = M('post');
            $total = $post->where('display=0')->count();
            $page = new \Think\Page($total,10);
            $button = $page->show();
            $postlist = $post->where('display=0')->limit($page->firstRow.','.$page->listRows)->select();
            foreach ($postlist as &$val) {
                $val['post_author'] = $post->table(__USER__)->where('id='+$val['post_author'])->getField('username');
                $val['title'] = mb_substr(htmlspecialchars($val['title']),0,12,'utf-8').'……';
                $val['content'] = mb_substr($val['plain_text'],0,12,'utf-8').'……';
                $val['addtime'] = date('Y-m-d',$val['addtime']);
                $val['lasttime'] = date('Y-m-d',$val['lasttime']);
                if($val['term_id'] == '8'){
                    $val['term_id'] = '未分类';
                }else{
                    $val['term_id'] = $post->table(__CATEGORY__)->where('id='.$val['term_id'])->getField('name');
                }
            }
            $this->assign('page',$button);
            $this->assign('list',$postlist);
            $this->display();
        }
    }
?>