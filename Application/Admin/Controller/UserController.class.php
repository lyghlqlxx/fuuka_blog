<?php
    namespace Admin\Controller;
    use Think\Controller;
    header("content-type:text/html;charset=utf-8");
    
    class UserController extends ConmonController{
        public function userlist(){
            $user = M('User');
            $shumu = $user->count();
            $page = new \Think\Page($shumu,10);
            $button = $page->show();
            $users = $user->limit($page->firstRow.','.$page->listRows)->select();
            $this->assign('page',$button);
            foreach($users as &$val){
                if($val['sex'] == 0){
                    $val['sex'] = '保密';
                }elseif($val['sex'] == 1){
                    $val['sex'] = '男';
                }elseif($val['sex'] == 2){
                    $val['sex'] = '女';
                }
                if($val['level'] == 0){
                    $val['level'] = '管理员';
                }else{
                    $val['level'] = '普通用户';
                }
                if($val['display'] == 1) {
                    $val['display'] = '已启用';
                }else{
                    $val['display'] = '已停用';
                }
                $val['addtime'] = date('Y'.'年'.'m'.'月'.'d'.'日',$val['addtime']);
                if($val['lasttime']==0){
                    $val['lasttime'] = $val['addtime'];
                }else{
                    $val['lasttime'] = date('Y'.'年'.'m'.'月'.'d'.'日',$val['lasttime']);
                }
            }
            $this->assign('list',$users);
            $this->display();
        }
        public function ss(){
            $name = $_GET['name'];
            $lei = $_GET['lei'];
            // dump($lei);
            if($lei == '1'){
                $cha['id'] = array('like','%'.$name.'%');
            }elseif($lei == '2'){
                $cha['username'] = array('like','%'.$name.'%');
            }
            $user = M('User');
            $shumu = $user->where($cha)->count();
            $page = new \Think\Page($shumu,5);
            $users = $user->where($cha)->limit($page->firstRow.','.$page->listRows)->select();
            if(empty($users)){
                $this->assign('lists','暂无数据哦');
            }else{
                $this->assign('list',$users);
            }
            $button = $page->show();
            foreach($users as &$val){
                if($val['sex'] == 0){
                    $val['sex'] = '保密';
                }elseif($val['sex'] == 1){
                    $val['sex'] = '男';
                }elseif($val['sex'] == 2){
                    $val['sex'] = '女';
                }
                if($val['level'] == 0){
                    $val['level'] = '管理员';
                }else{
                    $val['level'] = '普通用户';
                }
                if($val['display'] == 1) {
                    $val['display'] = '已启用';
                }else{
                    $val['display'] = '已停用';
                }
                $val['addtime'] = date('y'.'年'.'m'.'月'.'d'.'日',$val['addtime']);
                $val['lasttime'] = date('y'.'年'.'m'.'月'.'d'.'日',$val['lasttime']);
            }
            
            $this->assign('names',$name);
            $this->assign('lei',$lei);
            $this->assign('page',$button);
            $this->display('userlist');
        }
        public function del(){
            if($_POST['id'] != '1'){
                $id = $_POST['id'];
                $user = new \Think\Model('User');
                $pic = $user->where('id='.$id)->select();
                $pic = $pic[0]['pic'];
                unlink('./public/admin/uploads/'.$pic);
                $result = $user->delete($id);
                if($result){
                    echo '1';
                }else{
                    echo '2';
                } 
            }else{
                echo '0';
            }
        }
        public function dopic(){
            $upload = new \Think\Upload();
                $upload->maxSize   =     3145728 ;
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');
                $upload->savePath  =     '/uploads/';
                $info   =   $upload->uploadOne($_FILES['pic']);
            if($info){
                    $_POST['pic'] = $info['savename'];
                    $user = D('User');
                    $result = $user->dopic($_POST['id'],$_POST['path']);
                    unset($_POST['path']);
                    $user->where('id='.$_POST['id'])->save($_POST);
            }
            header('location:'.$_SERVER[HTTP_REFERER]);
        }
        public function useradd(){
            if(empty($_POST)){
                $this->display();
            }else{
                $user = M('User');
                $_POST['addtime'] = time();
                $_POST['password'] = md5($_POST['password']);
                $upload = new \Think\Upload();
                $upload->maxSize   =     3145728 ;
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');
                $upload->savePath  =     '/uploads/';
                $info   =   $upload->uploadOne($_FILES['pic']);
                if($info){
                   $_POST['pic'] = $info['savename'];
                }
                $user->create();
                $user->add();
                $this->redirect('userlist');
            }
        }
        public function userdoadd(){
            $user = M('User');
            $username = $user->where($_POST)->select();
            if($username){
                echo '1';
            }else{
                echo '0';
            }
        }

        public function edit(){
            $id = $_POST['id'];
            $user = M('User');
                $_POST['addtime'] = time();
                $upload = new \Think\Upload();
                $upload->maxSize   =     3145728 ;
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');
                $upload->savePath  =     '/uploads/';
                $info   =   $upload->uploadOne($_FILES['pic']);
                if($info){
                   $_POST['pic'] = $info['savename'];
                }
            $user = M('User');
            $user->where('id='.$id)->save($_POST);
            header("location:".$_SERVER['HTTP_REFERER']);
        }
        public function displays(){
            $id = $_GET['id'];
            $user = M('User');
            $dis = $user->find($id);
            $disp['display'] = $dis['display'];
            if($disp['display'] == 0){
                $disp['display'] = 1;
                $user->where('id='.$id)->save($disp);
                echo "y";
            }elseif($disp['display'] == 1){
                $disp['display'] = 0;
                $user->where('id='.$id)->save($disp);
                echo "n";
            }
        }
    }
?>
