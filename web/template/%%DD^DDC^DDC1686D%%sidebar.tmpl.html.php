<?php /* Smarty version 2.6.26, created on 2013-10-16 13:57:22
         compiled from /Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/grape/common/sidebar.tmpl.html */ ?>
<div class="col-md-4 sidebars">
<?php if ($this->_tpl_vars['tmpl'] == 'main-sidebar'): ?>
	<div class="white-bg bs2 bar">
		<h4 class="title">收听</h4>
		<div class="content rss">
			<div class="qrcode">
				<img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
weixin/weixin.jpg_avatar8686.jpg" alt="这故事"/>
				<p>用微信看故事</p>
			</div>
			<div class="snslink">
				<a href="http://user.qzone.qq.com/947252246" target="_blank">QQ空间</a>
				<a href="http://t.qq.com/zhegushier" target="_blank">腾讯微博</a>
				<a href="http://weibo.com/zhegushi" target="_blank">新浪微博</a>
			</div>
		</div>
	</div>
	<div class="white-bg bs2 bar">
		<h4 class="title">推荐标签</h4>
		<div class="content tag">
			<?php $_from = $this->_tpl_vars['validate_tags']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['t']):
?>
				<a class="tag"href="tag/index/<?php echo $this->_tpl_vars['t']['id']; ?>
"><?php echo $this->_tpl_vars['t']['tag']; ?>
</a>
			<?php endforeach; endif; unset($_from); ?>
		</div>
	</div>
	<div class="white-bg bs2 bar">
		<h4 class="title">热门标签</h4>
		<div class="content tag">
			<?php $_from = $this->_tpl_vars['hot_tags']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['t']):
?>
				<a class="tag"href="tag/index/<?php echo $this->_tpl_vars['t']['id']; ?>
"><?php echo $this->_tpl_vars['t']['tag']; ?>
</a>
			<?php endforeach; endif; unset($_from); ?>
		</div>
	</div>
<?php endif; ?>
</div>