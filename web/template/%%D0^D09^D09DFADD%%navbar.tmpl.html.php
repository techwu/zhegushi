<?php /* Smarty version 2.6.26, created on 2013-10-18 16:02:50
         compiled from /Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/grape/navigation/navbar.tmpl.html */ ?>
<div class="banner autumn">
	<div class="container">
		<!-- <img class="picture" src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
banner/aipic.png_banner960200.jpg"> -->
		<img class="logo" src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
logo/zhegushi.png">
		<img class="title " src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
banner/autumnfont.png">
	</div>
</div>
<div class="navbar navbar-inverse navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
">看这些故事<!-- <img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
logo/zhegushi.png_logonavbar.jpg"> --></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
          	 <li <?php if ($this->_tpl_vars['navaction'] == 'home' || $this->_tpl_vars['navaction'] == 'user'): ?>class="active"<?php endif; ?>><a href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
">首页</a></li>
          	 <li <?php if ($this->_tpl_vars['navaction'] == 'newest'): ?>class="active"<?php endif; ?>><a href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
newest">最新</a></li>
          	 <li <?php if ($this->_tpl_vars['navaction'] == 'hot'): ?>class="active"<?php endif; ?>><a href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
hot">最热</a></li>
          	 <li><a <?php if (! empty ( $this->_tpl_vars['session_user_id'] )): ?>rel="modal" modal-target="#operateModal" href="javascript:;;"<?php else: ?>href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
signin"<?php endif; ?>>写故事</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <?php if (empty ( $this->_tpl_vars['session_user_id'] ) || $this->_tpl_vars['session_user_id'] == 0): ?>
				<li><a href="signin">登陆</a></li>
				<li><a href="signup">注册</a></li>
			<?php else: ?>
				<li class="dropdown">
					<a href="javascript:;;" id="user-message-envelope" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-envelope"></i>
						<?php if (isset ( $this->_tpl_vars['session_user_info']['message_num'] ) && $this->_tpl_vars['session_user_info']['message_num'] > 0): ?>
							<span class="badge" id="user-message-badge"><?php echo $this->_tpl_vars['session_user_info']['message_num']; ?>
</span>
						<?php endif; ?>
					</a>
					<ul class="dropdown-menu extended inbox" id="user-message-box">
						<li>
							<p>loading...</p>
						</li>
						<li class="external">
							<a href="message">查看所有消息<i class="glyphicon glyphicon-circle-arrow-right"></i></a>
						</li>
					</ul>
				</li>
				<li class="dropdown">
					<a id="user-menu" data-toggle="dropdown"  href="javascript:;;" class="dropdown-toggle">
						<span><?php if (empty ( $this->_tpl_vars['session_user_info']['username'] )): ?>我<?php else: ?><?php echo $this->_tpl_vars['session_user_info']['username']; ?>
<?php endif; ?></span>
						<i class="glyphicon glyphicon-collapse-down"></i>
					</a>
					<ul class="dropdown-menu" rel="user-menu" role="menu" aria-labelledby="user-menu">
						<li>
							<a href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
me">看我的故事</a>
						</li>
						<!-- <li class="divider"></li>
						<li>
						    <a href="/setting">设置</a>
						</li> -->
						<li class="divider"></li>
						<li>
							<a href="/user/logout">退出</a>
						</li>
					</ul>
				</li>
			<?php endif; ?>
          </ul>
 		</div>
	</div>
</div>
<?php if (! empty ( $this->_tpl_vars['session_user_id'] )): ?>
	<div class="modal form-horizontal" id="operateModal">
		<div class="modal-dialog">
			<form class="form-horizontal modal-content" rel="form-story-add" action="story/ajax_addStory" method="post">	
	     	 	<div class="modal-header">
	    			<button type="button" class="close modal-close" data-dismiss="modal" aria-hidden="true">&times;</button>
	     	 		<h4 class="modal-title">分享故事</h4>
				</div>
				<div class="modal-body">
						<fieldset>
							<div class="form-group">
								<label class="control-label" for="inputTitle">标题</label>
							    <input type="text" class="form-control placeholder" placeholder="故事标题，可为空" name="title" id="inputTitle" rel="input-text" value="" ><span class="help-inline"></span>
							</div>
							<div class="form-group" >
								<label class="control-label" for="textStoryContent">故事内容<span class="control-label-r">&nbsp;*</span></label>
								<textarea id="textStoryContent" name="content" rows="4" rel="text-area" maxlength="1000" class="form-control on placeholder" placeholder="分享我的故事，分享我的喜怒哀乐" popover="tooltip" data-trigger="focus" data-html="true" data-placement="right" data-title="分享我的故事，分享我的喜怒哀乐" data-original-title=""></textarea>
								<span class="help-inline"></span>
								<p><span rel="textStoryContent" class="words-limits">0/1000</span></p>
							</div>
							<div class="form-group">
				                <label class="control-label" for="logoUpload">上传图片</label>
				                <div class="controls">
				                    <div class="img-upload-box" id="logoUpload">
				                        <img src="http://img.36tr.com/css/image/defaults/160160.png"
				                             rel="img-target-logo"
				                             data-value=""
				                             data-check=""
				                             class="img-target">
				                        <div class="img-upload">
				                            <a modal-target="#img-modal" rel="ajax-upload-logo"><img src="http://img.36tr.com/img/file/fileupload.png"/></a>
				                        </div>
				                    </div> 
				                </div>
				            </div>
							<div class="form-group tags" >
								<label>故事标签：</label>
								<div class="row">
									<div class="col-md-5">
										<input id="inputTags" type="text" value="" class="form-control tags-autocomplete" placeholder="标签之间用空格分隔" popover="tooltip" data-trigger="focus" data-html=true data-placement="right" data-html=true data-title="打上故事标签"  placeNotice="">
										<ul class="tags-area"></ul>
									</div>
									<div class="col-md-7">
										<div class="tagsbox">
    										<p>推荐标签：</p>
  											<div class="supertags">
  											<?php $_from = $this->_tpl_vars['nav_validate_tags']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['t']):
?>
												<a title="添加标签" data-tag="<?php echo $this->_tpl_vars['t']['tag']; ?>
"><em>+</em><?php echo $this->_tpl_vars['t']['tag']; ?>
</a>
											<?php endforeach; endif; unset($_from); ?>
  											</div>
  										</div>
									</div>
								</div>
							</div>
							<!-- <div class="form-group" >
								<ul class="tags-area"></ul>
								<input id="inputTags" type="text" value="" class="form-control tags-autocomplete" placeholder="打上故事标签，以空格为分隔符" popover="tooltip" data-trigger="focus" data-html=true data-placement="right" data-html=true data-title="打上故事标签"  placeNotice="">
                        		<span class="help-inline"></span>
							</div> -->
							<div class="form-group">
				                <span class="help-block" rel="form-help"></span>
				            </div>
				        </fieldset>
				</div>
				<div class="modal-footer">
				    <button type="button" class="btn btn-default modal-close" data-dismiss="modal">关闭</button>
        			<button type="submit" class="btn btn-primary">投递</button>
				</div>
			</form>
		</div>
	</div>
<?php endif; ?>