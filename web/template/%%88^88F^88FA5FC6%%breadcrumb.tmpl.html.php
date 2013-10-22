<?php /* Smarty version 2.6.26, created on 2013-10-09 12:58:57
         compiled from /Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/admin/common/breadcrumb/breadcrumb.tmpl.html */ ?>
<h3 class="page-title"><?php echo $this->_tpl_vars['title']; ?>
<small>----<?php echo $this->_tpl_vars['coordinate']; ?>
from：<?php echo $this->_tpl_vars['begin']; ?>
 To：<?php echo $this->_tpl_vars['end']; ?>
</small></h3>
					<ul class="breadcrumb">
						<?php echo $this->_tpl_vars['breadcrumb']; ?>

						<li class="pull-right no-text-shadow dropdown">
							<i class="icon-calendar"></i>
							<span class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->_tpl_vars['begin']; ?>
~<?php echo $this->_tpl_vars['end']; ?>
</span>
							<ul class="dropdown-menu datapicker-form">
								<li>
									<form method="get">
										<div class="col-md-5">
											from:<input type="text" class="datepicker" name="begin">
										</div>
										<div class="col-md-5">
											To:<input type="text" class="datepicker" name="end">
										</div>
										<div class="col-md-2">
											<input type="submit" class="submit" value="确定"/>
										</div>
									</form>
								</li>
							</ul>
						</li>
					</ul>