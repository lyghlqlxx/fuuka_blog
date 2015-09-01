<?php
    namespace Admin\Controller;
    use Think\Controller;
    header("content-type:text/html;charset=utf-8");
    class TremController extends ConmonController{
        public function categorylist(){
            $category = M('category');
            $total = $category->where('form_id=0')->count();
            $page = new \Think\Page($total,5);
            $button = $page->show();
            $categorylist = $category->where('form_id=0')->limit($page->firstRow.','.$page->listRows)->select();
            $this->assign('page',$button);
            foreach ($categorylist as &$val) {
                if($val['display'] == 1){
                    $val['display'] = '已启用';
                }elseif($val['display'] == 0){
                    $val['display'] = '已禁用';
                }
                if($val['path'] == '0,'){
                    $val['gpath'] = $val['path'];
                    $val['path'] = '根目录';
                }else{
                    $val['gpath'] = $val['path'];
                }
            }
            if($_GET['type'] == 'add'){
                $this->assign('type','add');
            }else{
                $this->assign('type','index');
            }
            $this->assign('list',$categorylist);
            $this->display('categorylist');
        }

        public function stats(){
            $category = M('category');
            $result = $category->save($_POST);
            if($result){
                echo '已完成';
            }else{
                echo '操作失败';
            }
        }
        public function del(){
            if($_POST['pid'] == 0){
                echo '0';
            }else{
                $category = M('category');
                $result = $category->delete($_POST['id']);
                if($result){
                    echo '1';
                }else{
                    echo '2';
                }
            }
        }

        public function getSon(){
            $id = $_GET['id'];
            $son = M('category');
            $total = $son->where('form_id='.$id)->count();
            $page = new \Think\Page($total,5);
            $button = $page->show();
            $sonlist = $son->where('form_id='.$id)->limit($page->firstRow.','.$page->listRows)->select();
            $parent = $son->where('id='.$sonlist[0]['form_id'])->select();
            $this->assign('page',$button);
            foreach ($sonlist as &$val) {
                if($val['display'] == 1){
                    $val['display'] = '已启用';
                }elseif($val['display'] == 0){
                    $val['display'] = '已禁用';
                }
                if($val['path'] == '0,'){
                    $val['gpath'] = $val['path'];
                    $val['path'] = '根目录';

                }else{
                    $val['gpath'] = $val['path'];
                    $val['path'] = "/{$parent[0]['name']}/{$val['name']}";
                }
            }
            $this->assign('list',$sonlist);
            $this->display('categorylist');
        }

        public function edit(){
            $category = M('category');
            if($category->save($_POST)){
                echo '1';
            }else{
                echo '0';
            }
        }

        public function getAll(){
            $category = M('category');
            $categorylist = $category->where('form_id=0')->select();
            echo json_encode($categorylist);
        }

        public function add(){
            $category = M('category');
            $category->create($_POST);
            $result = $category->add();
            if($result){
                echo '1';
            }else{
                echo '0';
            }

        }
    }
