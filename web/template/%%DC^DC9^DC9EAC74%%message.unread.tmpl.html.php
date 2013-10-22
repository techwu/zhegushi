<?php /* Smarty version 2.6.26, created on 2013-10-15 11:04:10
         compiled from /Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/grape/container/message/message.unread.tmpl.html */ ?>
<li>
	<p>您有<?php echo $this->_tpl_vars['session_user_info']['message_num']; ?>
新消息提醒</p>
</li>
<?php if (! empty ( $this->_tpl_vars['message'] )): ?>
<?php $_from = $this->_tpl_vars['message']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m']):
?>
<li>
	<a <?php if (! empty ( $this->_tpl_vars['m']['url'] )): ?>href="<?php echo $this->_tpl_vars['m']['url']; ?>
" target="_blank"<?php endif; ?>>
		<span class="photo"><img src="<?php echo $this->_tpl_vars['m']['avatar']; ?>
" alt="<?php echo $this->_tpl_vars['m']['username']; ?>
"></span>
		<span class="subject">
			<span class="from"><?php echo $this->_tpl_vars['m']['username']; ?>
</span>
			<span class="time"><?php echo $this->_tpl_vars['m']['created']; ?>
</span>
		</span>
		<span class="message">
			<?php echo $this->_tpl_vars['m']['content']; ?>

		</span>  
	</a>
</li>
<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
<li class="external">
	<a href="message">查看所有消息<i class="glyphicon glyphicon-circle-arrow-right"></i></a>
</li>