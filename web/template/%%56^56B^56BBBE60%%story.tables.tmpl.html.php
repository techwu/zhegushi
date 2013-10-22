<?php /* Smarty version 2.6.26, created on 2013-10-09 17:20:49
         compiled from /Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/admin/container/dashboard/story.tables.tmpl.html */ ?>
<div class="col-md-12">
	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption"><i class="icon-barcode"></i>&nbsp;故事数据</div>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				<a href="javascript:;" class="reload"></a>
				<a href="javascript:;" class="remove"></a>
			</div>
		</div>
		<div class="portlet-body">
			<table class="table table-hover">
				<thead>
					<tr><th>事件</th><th>故事数</th><th>新增数</th></tr>
				</thead>
				<tbody>
					<tr><td><a href="story?begin=<?php echo $this->_tpl_vars['begin']; ?>
&end=<?php echo $this->_tpl_vars['end']; ?>
">总故事数</a></td><td><?php echo $this->_tpl_vars['story_count']; ?>
</td><td><span class="label <?php if ($this->_tpl_vars['story_count_add'] > 0): ?>label-success<?php elseif ($this->_tpl_vars['story_count_add'] == 0): ?>label-warning<?php else: ?>label-danger<?php endif; ?>"><?php echo $this->_tpl_vars['story_count_add']; ?>
</span></td></tr>
					<tr><td><a href="story/validated?begin=<?php echo $this->_tpl_vars['begin']; ?>
&end=<?php echo $this->_tpl_vars['end']; ?>
">审核通过故事数</a></td><td><?php echo $this->_tpl_vars['v_story_count']; ?>
</td><td><span class="label <?php if ($this->_tpl_vars['v_story_count_add'] > 0): ?>label-success<?php elseif ($this->_tpl_vars['v_story_count_add'] == 0): ?>label-warning<?php else: ?>label-danger<?php endif; ?>"><?php echo $this->_tpl_vars['v_story_count_add']; ?>
</span></td></tr>
				</tbody>
			</table>
		</div>
	</div>
</div>