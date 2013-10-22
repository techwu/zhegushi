<?php /* Smarty version 2.6.26, created on 2013-10-09 17:26:58
         compiled from /Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/admin/container/dashboard/user.tables.tmpl.html */ ?>
<div class="col-md-12">
	<div class="portlet box green">
		<div class="portlet-title">
			<div class="caption"><i class="icon-group"></i>&nbsp;人物数据</div>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				<a href="javascript:;" class="reload"></a>
				<a href="javascript:;" class="remove"></a>
			</div>
		</div>
		<div class="portlet-body">
			<table class="table table-hover">
				<thead>
					<tr><th>事件</th><th>用户数</th><th>新增情况</th></tr>
				</thead>
				<tbody>
					<tr><td>总注册用户数</td><td><?php echo $this->_tpl_vars['user_count']; ?>
</td><td><span class="label <?php if ($this->_tpl_vars['user_count_add'] > 0): ?>label-success<?php elseif ($this->_tpl_vars['user_count_add'] == 0): ?>label-warning<?php else: ?>label-danger<?php endif; ?>"><?php echo $this->_tpl_vars['user_count_add']; ?>
</span></td></tr>
				</tbody>
			</table>
		</div>
	</div>
</div>