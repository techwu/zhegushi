<?php /* Smarty version 2.6.26, created on 2013-10-10 19:28:35
         compiled from /Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/admin/container/story/list.tmpl.html */ ?>
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
			<div class="clearfix">
				<div class="btn-group">
					
				</div>
				<div class="btn-group pull-right">
					<button class="btn dropdown-toggle" data-toggle="dropdown">操作 <i class="icon-angle-down"></i></button>
					<ul class="dropdown-menu pull-right">
						<li><a href="#">搜索故事</a></li>
						<li><a href="#">下载</a></li>
					</ul>
				</div>
			</div>
			<div class="col-md-12">
					<?php echo $this->_tpl_vars['story_content']; ?>

				<div class="pagination-centered">
					<?php echo $this->_tpl_vars['pages_fliper']; ?>

				</div>
			</div>
		</div>
</div>