<?php if (!defined('THINK_PATH')) exit();?><!-- $Id: category_info.htm 16752 2009-10-20 09:59:38Z wangleisvn $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>ECSHOP 管理中心 - <?php echo ($meta_title); ?> </title>
        <meta name="robots" content="noindex, nofollow"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="http://admin.shop.com/Public/CSS/general.css" rel="stylesheet" type="text/css" />
        <link href="http://admin.shop.com/Public/CSS/main.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="http://admin.shop.com/Public/EXT/ztree/css/zTreeStyle/zTreeStyle.css" />
    </head>
    <body>
        <h1>
            <span class="action-span"><a href="<?php echo U('index');?>">菜单分类</a></span>
            <span class="action-span1"><a href="<?php echo U('Index/main');?>">ECSHOP 管理中心</a></span>
            <span id="search_id" class="action-span1"> - <?php echo ($meta_title); ?> </span>
        </h1>
        <div style="clear:both"></div>
        <div class="main-div">
            <form action="<?php echo U();?>" method="post" name="theForm">
                <table width="100%" id="general-table">
                    <tr>
                        <td class="label">菜单名称:</td>
                        <td>
                            <input type='text' name='name' maxlength="20" value='<?php echo ($row["name"]); ?>' size='27' /> <font color="red">*</font>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">上级菜单:</td>
                        <td>
                            <input type="hidden" id="parent_id" name="parent_id" value="" />
                            <input type="text" id="parent_name" value="" disabled="disabled"/>
                            <ul id="treeDemo" class="ztree"></ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">拥有权限:</td>
                        <td>
                            <div id="permission_ids"></div>
                            <ul id="permissionDemo" class="ztree"></ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">路径:</td>
                        <td>
                            <input type="text" name='url'  value="<?php echo ($row["url"]); ?>" size="30" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label">排序:</td>
                        <td>
                            <input type="text" name='sort'  value="<?php echo ((isset($row["sort"]) && ($row["sort"] !== ""))?($row["sort"]):50); ?>" size="15" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label">是否显示:</td>
                        <td>
                            <input type="radio" name="status" value="1" /> 是 
                            <input type="radio" name="status" value="0" /> 否
                        </td>
                    </tr>
                    <tr>
                        <td class="label">简介:</td>
                        <td>
                            <input type="text" name="intro" value='<?php echo ($row["intro"]); ?>' size="50">
                        </td>
                    </tr>
                </table>
                <div class="button-div">
                    <input type="hidden" name="id" value="<?php echo ($row["id"]); ?>" />
                    <input type="submit" value=" 确定 " />
                    <input type="reset" value=" 重置 " />
                </div>
            </form>
        </div>

        <div id="footer">
            共执行 3 个查询，用时 0.162348 秒，Gzip 已禁用，内存占用 2.266 MB<br />
            版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
        <script type="text/javascript" src="http://admin.shop.com/Public/JS/jquery-1.11.2.js"></script>
        <script type="text/javascript" src="http://admin.shop.com/Public/EXT/ztree/js/jquery.ztree.core.min.js"></script>
        <script type="text/javascript" src="http://admin.shop.com/Public/EXT/ztree/js/jquery.ztree.excheck.js"></script>
        <script type="text/javascript">
                $(':input[name=status]').val([<?php echo ((isset($row["status"]) && ($row["status"] !== ""))?($row["status"]):1); ?>]);

            //准备节点数据
            var zNodes = <?php echo ($rows); ?>;
            //基本配置
            var setting = {
                data: {simpleData: {
                        enable: true,
                        pIdKey: "parent_id",}},
                callback:{
                    onClick:function(event,treeid,tree_node){
                        $('#parent_id').val(tree_node.id);
                        $('#parent_name').val(tree_node.name);
                        console.debug(arguments);},},};
            $(document).ready(function () {
                //默认选中[是否显示]
                //将html节点初始化成ztree的效果
            var ztree_obj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
                
                
                //编辑页面的时候,找到父级节点,并且回显出来
                <?php if(!empty($row)): ?>//找到父级节点
                    var parent_node = ztree_obj.getNodeByParam('id',<?php echo ($row["parent_id"]); ?>);
                    //将父级节点选中
                    ztree_obj.selectNode(parent_node);
                    //将值放到文本框和隐藏域
                    $('#parent_id').val(parent_node.id);
                    $('#parent_name').val(parent_node.name);<?php endif; ?>
        
                //展开所有的ztree节点
                ztree_obj.expandAll(true);


            //////////////////////////准备权限相关的数据////////////////////////////

            //准备节点数据
            var permission_nodes = <?php echo ($permissions); ?>;
            //基本配置
            var permissin_setting = {
                check: {enable: true,
                    checkboxType:{ "Y" : "ps", "N" : "ps" },},
                data: {simpleData: {
                        enable: true,
                        pIdKey: "parent_id",}},
                callback:{onCheck:function(event,treeid,tree_node){
                        //获取输入框被勾选true的节点结合
                        var checked_nodes = permission_ztree_obj.getCheckedNodes(true);
                        //取得操作节点
                        var ele = $('#permission_ids');
                        //清空隐藏域
                        var html = '';
                        //遍历每个节点
                        $(checked_nodes).each(function(){
                            //获取节点checkbox/radio半勾选状态
                            var check_status = this.getCheckStatus();
                            //忽略半选的节点
                            if(!check_status.half){
                                //添加权限id到隐藏域
                                html += '<input type="hidden" name="permission_id[]" value="'+this.id+'"/>';}});
                        //在节点后加入html代码
                        ele.html(html);},},};
            //将html节点初始化成ztree的效果,#permissionDemo展示节点,setting配置,zNodes数据
            var permission_ztree_obj = $.fn.zTree.init($("#permissionDemo"), permissin_setting, permission_nodes);

            //编辑页面的时候,找到父级节点,并且回显出来
            //当row中有值时显示
            <?php if(!empty($row)): ?>//选中关联的权限列表
            //获取权限id
            var permission_checked_nodes = <?php echo ($row["permission_id"]); ?>;
            //权限id对象便利,i时循环第几次,n id值
            $(permission_checked_nodes).each(function(i,n){
                        //通过id参数找到树形节点中的对应参数的节点
                        var permission_tree_node = permission_ztree_obj.getNodeByParam('id',n);
                        //在树形节点中选中节点
                        permission_ztree_obj.checkNode(permission_tree_node,true,true,true);
                    }
            );<?php endif; ?>

                //展开所有的ztree节点
            permission_ztree_obj.expandAll(false);
            });

        </script>
    </body>
</html>