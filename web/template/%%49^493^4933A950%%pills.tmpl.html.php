<?php /* Smarty version 2.6.26, created on 2013-10-18 18:41:25
         compiled from /Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/grape/container/story/pills.tmpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', '/Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/grape/container/story/pills.tmpl.html', 19, false),array('modifier', 'escape', '/Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/grape/container/story/pills.tmpl.html', 43, false),)), $this); ?>
<div class="col-md-8 items">
			<ul class="list-unstyled storys">
			<?php if (! empty ( $this->_tpl_vars['story_list'] )): ?>
			<?php $_from = $this->_tpl_vars['story_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['s']):
?>
			<li class="col-md-12 white-bg bs2 item <?php if ($this->_tpl_vars['k'] == 0): ?>first<?php endif; ?>" data-value="<?php echo $this->_tpl_vars['s']['id']; ?>
">
				<div class="col-md-12 title">
					<a href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
story/page/<?php echo $this->_tpl_vars['s']['id']; ?>
"><?php echo $this->_tpl_vars['s']['title']; ?>
</a>
				</div>
				<?php if (isset ( $this->_tpl_vars['s']['user_info'] )): ?>
					<div class="col-md-12">
						<span class="time"><?php echo $this->_tpl_vars['s']['create']; ?>
</span>
						<a class="user-info" href="profile/u/<?php echo $this->_tpl_vars['s']['user_info']['user_id']; ?>
">
							<img src="<?php echo $this->_tpl_vars['s']['user_info']['avatar']; ?>
_avatar4040.jpg" alt="<?php echo $this->_tpl_vars['s']['user_info']['username']; ?>
">
							<span><?php echo $this->_tpl_vars['s']['user_info']['username']; ?>
</span>
						</a>
					</div>
				<?php endif; ?>
				<div class="col-md-12 content">
					<p><?php if (! empty ( $this->_tpl_vars['s']['title'] )): ?><a href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
story/page/<?php echo $this->_tpl_vars['s']['id']; ?>
" class="title">#<?php echo $this->_tpl_vars['s']['title']; ?>
#</a><?php endif; ?></p><p><?php echo ((is_array($_tmp=$this->_tpl_vars['s']['story'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>
				</div>
				<?php if (isset ( $this->_tpl_vars['s']['img'] ) && ! empty ( $this->_tpl_vars['s']['img'] )): ?>
				<div class="col-md-12 thumb">
					<a class="miniImg artZoom" href="<?php echo $this->_tpl_vars['s']['img']; ?>
_w480.jpg" rel="<?php echo $this->_tpl_vars['s']['img']; ?>
_w480.jpg">
						<img data-original="<?php echo $this->_tpl_vars['s']['img']; ?>
_wsmall.jpg" src="" alt="<?php echo $this->_tpl_vars['s']['title']; ?>
" class="lazy">
					</a>
				</div>
				<?php endif; ?>
				<div class="col-md-12 bar">
					<ul class="list-unstyled bar-actions">
						<li class="bar-action up">
							<a rel="bar-action" data-action="10" title="顶"><i class="glyphicon glyphicon-thumbs-up inline"></i><?php echo $this->_tpl_vars['s']['up']; ?>
</a>
						</li>
						<!-- <li class="bar-action down">
							<a rel="bar-action" data-action="11" title="臭鸡蛋"><i class="glyphicon glyphicon-thumbs-down inline"></i><?php echo $this->_tpl_vars['s']['down']; ?>
</a>
						</li> -->
						<li class="bar-action comment">
							<a rel="bar-action-comment" data-action="12" title="评论"><i class="glyphicon glyphicon-comment inline"></i><?php echo $this->_tpl_vars['s']['comments']; ?>
</a>
						</li>
						<li class="bar-action share">
							<div class="dropdown">
								<a rel="bar-action-share" title="分享" data-toggle="dropdown" data-target="#"><i class="glyphicon glyphicon-share-alt inline"></i>分享</a>
								<ul class="dropdown-menu">
				                    <li><a target="blank" href="http://share.baidu.com/s?type=text&amp;searchPic=0&amp;sign=on&amp;to=tsina&amp;url=<?php echo $this->_tpl_vars['BASE_URL']; ?>
story/page/<?php echo $this->_tpl_vars['s']['id']; ?>
&amp;title=<?php echo ((is_array($_tmp=$this->_tpl_vars['s']['share_story'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;">新浪微博</a></li>
				                    <li><a target="blank" href="http://share.baidu.com/s?type=text&amp;searchPic=0&amp;sign=on&amp;to=tqq&amp;url=<?php echo $this->_tpl_vars['BASE_URL']; ?>
story/page/<?php echo $this->_tpl_vars['s']['id']; ?>
&amp;title=<?php echo ((is_array($_tmp=$this->_tpl_vars['s']['share_story'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;key=">腾讯微博</a></li>
				                    <li><a target="blank" href="http://share.baidu.com/s?type=text&amp;searchPic=0&amp;sign=on&amp;to=douban&amp;url=<?php echo $this->_tpl_vars['BASE_URL']; ?>
story/page/<?php echo $this->_tpl_vars['s']['id']; ?>
&amp;title=<?php echo ((is_array($_tmp=$this->_tpl_vars['s']['share_story'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
">豆瓣</a></li>
				                    <li><a target="blank" href="http://share.baidu.com/s?type=text&amp;searchPic=0&amp;sign=on&amp;to=renren&amp;url=<?php echo $this->_tpl_vars['BASE_URL']; ?>
story/page/<?php echo $this->_tpl_vars['s']['id']; ?>
&amp;title=<?php echo ((is_array($_tmp=$this->_tpl_vars['s']['share_story'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
">人人网</a></li>
				                    <li><a target="blank" href="http://share.baidu.com/s?type=text&amp;searchPic=0&amp;sign=on&amp;to=qzone&amp;url=<?php echo $this->_tpl_vars['BASE_URL']; ?>
story/page/<?php echo $this->_tpl_vars['s']['id']; ?>
&amp;title=<?php echo ((is_array($_tmp=$this->_tpl_vars['s']['share_story'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
">QQ空间</a></li>
				                    <li class="divider"></li>
				                    <li><a href="mailto:?subject=【这故事】好故事分享给最好的你&amp;body=<?php echo ((is_array($_tmp=$this->_tpl_vars['s']['story'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
<?php echo $this->_tpl_vars['BASE_URL']; ?>
story/page/<?php echo $this->_tpl_vars['s']['id']; ?>
">E-Mail</a></li>
				                    <li><a target="blank" href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
story/page/<?php echo $this->_tpl_vars['s']['id']; ?>
">查看全文</a></li>
				                 </ul>
				            </div>
						</li>
					</ul>
				</div>
				<div class="comments <?php if (isset ( $this->_tpl_vars['page'] ) && $this->_tpl_vars['page'] == 'page'): ?><?php else: ?>hide<?php endif; ?>">
					<ul class="list-unstyled comment-items" rel="comment-items">
						<?php if (! empty ( $this->_tpl_vars['s']['comment'] )): ?>
						<?php $_from = $this->_tpl_vars['s']['comment']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
</a>
								<span class="body"><?php echo $this->_tpl_vars['c']['comment']; ?>
</span>
							</div>
							<div class="reply">
								<a href="javascript:;;" class="link" rel="common-reply" data-value="<?php echo $this->_tpl_vars['c']['username']; ?>
"><i class="glyphicon glyphicon-share-alt inline"></i></a>
							</div>
						</li>
						<?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</ul>
					<div class="input-block clearfix">
						<?php if (empty ( $this->_tpl_vars['session_user_id'] )): ?>
						<div class="logout login-block">
							<a class="close-comments" rel="close-comments">收起</a>
							<a href="signin" >登陆</a> 后才能发表评论</span>
						</div>
						<?php else: ?>
							<form class="form-horizontal comment-form" rel="form-story-add-comment" action="story/ajax_addUserComments" method="post">
								<fieldset>
								<div class="col-md-10">
									<div class="form-group" >
										<textarea id="textStoryComment" name="comment" rows="2" rel="text-area" maxlength="200" class="form-control on placeholder" placeholder="评论他的故事，和他/她一起喜怒哀乐"></textarea>
										<span class="help-inline"></span>
										<p><span rel="textStoryComment" class="words-limits">0/200</span></p>
									</div>
								</div>
								<div class="col-md-2">
									<button type="submit" class="btn btn-default btn-sm mt18">发表</button>
								</div>
								</fieldset>
							</form>
						<?php endif; ?>
					</div>
				</div>
			</li>
			<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
			</ul>
			<div class="pagination-centered">
				<?php echo $this->_tpl_vars['pages_fliper']; ?>

			</div>
		</div>