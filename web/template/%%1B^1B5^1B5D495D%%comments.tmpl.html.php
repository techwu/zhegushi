<?php /* Smarty version 2.6.26, created on 2013-10-18 19:06:42
         compiled from /Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/grape/container/comments/comments.tmpl.html */ ?>

		<?php if (! empty ( $this->_tpl_vars['comments'] )): ?>
		<?php $_from = $this->_tpl_vars['comments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
		<li class="comment-item">
			<div class="avatar">
				<img src="<?php echo $this->_tpl_vars['c']['avatar']; ?>
_2020.jpg" alt="<?php echo $this->_tpl_vars['c']['username']; ?>
">
			</div>
			<div class="comment-content">
				<a href="profile/u/<?php echo $this->_tpl_vars['c']['user_id']; ?>
" class="userlogin"><?php echo $this->_tpl_vars['c']['username']; ?>
</a>:
				<span class="body"><?php echo $this->_tpl_vars['c']['comment']; ?>
</span>
			</div>
			<div class="reply" style="height: 6px;">
				<a href="javascript:;;" class="link" rel="common-reply" data-value="<?php echo $this->_tpl_vars['c']['username']; ?>
"><i class="glyphicon glyphicon-share-alt inline"></i></a>
			</div>
		</li>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>