<?php /* Smarty version 2.6.26, created on 2013-10-11 16:18:05
         compiled from /Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/admin/container/story/story.pills.tmpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', '/Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/admin/container/story/story.pills.tmpl.html', 32, false),array('modifier', 'nl2br', '/Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/admin/container/story/story.pills.tmpl.html', 72, false),)), $this); ?>
		<ul class="list-unstyled items">
				<li class="item yellow">
						<ul class="list-inline row list-title">
							<li class="li-checkbox">
								<div class="checker">
									<span>
										<input type="checkbox" class="group-checkable" data-set="ul.items > li.item .checkboxes" >
									</span>
								</div>
							</li>
							<li class="li-identify sorting">ID</li>
							<li class="li-name">发表人</li>
							<li class="li-time">发表时间</li>
							<li class="li-phase">内容</li>
							<li class="li-location">tags</li>
							<li class="li-status">内容状态</li>
						</ul>
					</li>
				<?php if (! empty ( $this->_tpl_vars['story_list'] )): ?>
					<?php $_from = $this->_tpl_vars['story_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['s']):
?>
					<li class="item">
						<ul class="list-inline row">
							<li class="li-checkbox">
								<div class="checker">
								<span>
									<input type="checkbox" class="checkboxes" name="storyids[]" value="<?php echo $this->_tpl_vars['s']['id']; ?>
">
								</span>
								</div>
							</li>
							<li class="li-identify toggle"><?php echo $this->_tpl_vars['s']['id']; ?>
</li>
							<li class="li-name toggle"><?php echo $this->_tpl_vars['s']['user_info']['username']; ?>
</li>
							<li class="li-time toggle"><?php echo ((is_array($_tmp=$this->_tpl_vars['s']['create_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %h') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %h')); ?>
</li>
							<li class="li-phase toggle"><?php echo $this->_tpl_vars['s']['story']; ?>
</li>
							<li class="li-location toggle">
							<?php if (! empty ( $this->_tpl_vars['s']['tags_info'] )): ?>
								<?php $_from = $this->_tpl_vars['s']['tags_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['t']):
?>
									<a href="<?php echo $this->_tpl_vars['WWW_PATH']; ?>
tag/index/<?php echo $this->_tpl_vars['t']['id']; ?>
" target="_blank"><?php echo $this->_tpl_vars['t']['tag']; ?>
</a>
								<?php endforeach; endif; unset($_from); ?>
							<?php endif; ?>
							</li>
							<li class="li-status toggle">
								<?php if ($this->_tpl_vars['s']['is_v'] == 1): ?>
									<span class="label label-success">已审核</span>
								<?php elseif ($this->_tpl_vars['s']['is_v'] == 0): ?>
									<span class="label label-info">待审核</span>
								<?php endif; ?>
							</li>
						</ul>
						
						<div class="item-boxes">
							<div class="item-show">
								<h4>详细信息</h4>
								<ul class="item-box list-unstyled">
									<li class="row">
										<div class="col-md-3">
											故事标题：
										</div>
										<div  class="col-md-9">
											<?php if (empty ( $this->_tpl_vars['s']['title'] )): ?>
											空
											<?php else: ?>
											<?php echo $this->_tpl_vars['s']['title']; ?>

											<?php endif; ?>
											<?php if ($this->_tpl_vars['s']['is_v'] == 1): ?>
												<span class="label label-success">已审核</span>
											<?php elseif ($this->_tpl_vars['s']['is_v'] == 0): ?>
												<span class="label label-info">待审核</span>
											<?php endif; ?>
										</div>
									</li>
									<li class="row">
										<div class="col-md-3">详细内容：</div><div  class="col-md-9"><?php echo ((is_array($_tmp=$this->_tpl_vars['s']['story'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</div>
									</li>
									<li class="row">
										<div class="col-md-3">故事关键词：</div><div  class="col-md-9">
										<?php if (! empty ( $this->_tpl_vars['s']['tags_info'] )): ?>
											<?php $_from = $this->_tpl_vars['s']['tags_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['t']):
?>
												<a href="<?php echo $this->_tpl_vars['WWW_PATH']; ?>
tag/index/<?php echo $this->_tpl_vars['t']['id']; ?>
" target="_blank"><?php echo $this->_tpl_vars['t']['tag']; ?>
</a>
											<?php endforeach; endif; unset($_from); ?>
										<?php endif; ?>
										</div>
									</li>
								</ul>
								<h4>发表用户信息</h4>
								<ul class="item-box list-unstyled">
									<li class="row">
										<div class="col-md-3">
											<img src="<?php echo $this->_tpl_vars['s']['user_info']['avatar']; ?>
_avatar8686.jpg" />
										</div>
										<div class="col-md-9">
											<p><?php echo $this->_tpl_vars['s']['user_info']['username']; ?>
(<?php echo $this->_tpl_vars['s']['user_info']['email']; ?>
)</p>
										</div>
									</li>
								</ul>
							</div>
							<div class="item-operate">
								<div class="row">
									<div class="col-md-11">
										<p><a class="btn btn-success" href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
story/page/<?php echo $this->_tpl_vars['s']['id']; ?>
?edit=true"  target="_blank">编辑</a></p>
										<?php if ($this->_tpl_vars['s']['is_v'] == 0): ?>
											<p><a class="btn btn-success" rel="operateStory" data-operate="release" data-value="<?php echo $this->_tpl_vars['s']['id']; ?>
">通过审核</a></p>
											<p><a class="btn btn-danger" rel="operateStory" data-operate="delete" data-value="<?php echo $this->_tpl_vars['s']['id']; ?>
">删除</a></p>
										<?php else: ?>
											<?php if ($this->_tpl_vars['s']['is_recommended']): ?>
											<p><a class="btn btn-info" rel="operateStory" data-operate="unrecommend" data-value="<?php echo $this->_tpl_vars['s']['id']; ?>
">取消推荐</a></p>
											<?php else: ?>
											<p><a class="btn btn-info" rel="operateStory" data-operate="recommend" data-value="<?php echo $this->_tpl_vars['s']['id']; ?>
">首页推荐</a></p>
											<?php endif; ?>
											<p><a class="btn btn-danger" rel="operateStory" data-operate="delete" data-value="<?php echo $this->_tpl_vars['s']['id']; ?>
">删除</a></p>
											<p><a class="btn btn-warning" rel="operateStory" data-operate="reset" data-value="<?php echo $this->_tpl_vars['s']['id']; ?>
">重置</a></p>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</li>
					<?php endforeach; endif; unset($_from); ?>
					<?php endif; ?>
		</ul>