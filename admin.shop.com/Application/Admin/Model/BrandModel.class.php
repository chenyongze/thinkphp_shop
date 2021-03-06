<?php
/**
 * Created by PhpStorm.
 * User: 孙歆雁1
 * Date: 2016/3/7
 * Time: 17:47
 */

namespace Admin\Model;


class BrandModel extends \Think\Model
{
    //自动验证
    protected $_validate=array(
        array('name','require','商品名字不能为空'),
        array('name','','商品名字已存在',self::EXISTS_VALIDATE,'unique'),
    );

    //获取列表
    //分页
    //搜索
    //排序
    //过滤已删除
    public function getPageResult(array $cond=array(),$page=1){
        $count=$this->where($cond)->where('status<>-1')->count();
        $rows=$this->where($cond)->where('status<>-1')->order('sort asc')->page($page,C('PAGE_SIZE'))->select();
        $page=new \Think\Page($count,C('PAGE_SIZE'));
        $page->setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $page_html=$page->show();
        return array('page_html'=>$page_html,'rows'=>$rows);
    }

    //修改或删除商品，删除的话名字加上_del
    /**
     * @param $id 修改的id
     * @param int $status 修改后的状态
     * @return bool|int 返回失败或影响的行数
     */
    public function changeStatus($id,$status=-1){
        $data=array(
            'status'=>$status,
            'name'=>array('exp','concat(name,"_del")'),
            'id'=>$id,
        );
        if($status!=-1){
            unset($data['name']);
        }
       return $this->save($data);
    }

    /**
     * 添加品牌
     */
    public function addBrand($logo){
        $this->data['logo']=$logo;
        if(empty($logo)){
            unset($this->data['logo']);
        }
        return $this->add();
    }

    /**
     * 修改跟新品牌
     * @param $logo
     * @return bool
     */
    public function updateBrand($logo){
        $this->data['logo']=$logo;
        if(empty($logo)){
            unset($this->data['logo']);
        }
        return $this->save();
    }

    /**
     * 获取品牌列表,只要id,name字段过滤状态不为-1数据
     * @return mixed
     */
    public function getList(){
        return $this->where(array('status'=>array('gt',-1)))->getField('id,id,name');
    }
}