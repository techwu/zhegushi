<?php /* Smarty version 2.6.26, created on 2013-10-11 23:56:11
         compiled from /Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/admin/container/story/story.edit.tmpl.html */ ?>
<form class="form-horizontal" rel="form-story-edit" method="post" action="story/ajax_editStory" data-value="<?php echo $this->_tpl_vars['story']['id']; ?>
">
	<fieldset>
		<div class="portlet box green"> 
			<div class="portlet-title event-i-a">
				<a href="story/page/<?php echo $this->_tpl_vars['story']['id']; ?>
">查看</a>
			</div>
			<div class="portlet-body">
				<div class="form-group">
					<label class="control-label col-lg-2" for="inputTitle">标题<span class="control-label-r">&nbsp;*</span></label>
					<div class="col-lg-10">
				    	<input type="text" class="form-control placeholder" placeholder="故事标题，可为空" name="title" id="inputTitle" rel="input-text" value="<?php if (isset ( $this->_tpl_vars['story']['title'] )): ?><?php echo $this->_tpl_vars['story']['title']; ?>
<?php endif; ?>" ><span class="help-inline"></span>
				    </div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-2" for="textStoryContent">内容<span class="control-label-r">&nbsp;*</span></label>
					<div class="col-lg-10">
						<textarea id="textStoryContent" name="content" rows="4" rel="text-area" maxlength="1000" class="form-control on placeholder" placeholder="分享我的故事，分享我的喜怒哀乐" popover="tooltip" data-trigger="focus" data-html="true" data-placement="right" data-title="分享我的故事，分享我的喜怒哀乐" data-original-title=""><?php if (isset ( $this->_tpl_vars['story']['story'] )): ?><?php echo $this->_tpl_vars['story']['story']; ?>
<?php endif; ?></textarea>
						<span class="help-inline"></span>
						<p><span rel="textStoryContent" class="words-limits">0/1000</span></p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-2" for="inputSettingName">关键词<span class="control-label-r">&nbsp;*</span></label>
					<div class="col-lg-10">
						<ul class="tags-area" <?php if ($this->_tpl_vars['story']['tags_info']): ?>style="display:block;"<?php endif; ?>>
								<?php if ($this->_tpl_vars['story']['tags_info']): ?>
			                	<?php $_from = $this->_tpl_vars['story']['tags_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['a']):
?>
			                		<li class="story-tag"><a><?php echo $this->_tpl_vars['a']['tag']; ?>
</a><img src="http://img.36tr.com/img/tags/close-tag.png" class="close-tag"></li>
			                	<?php endforeach; endif; unset($_from); ?>
			                	<?php endif; ?>
			                    </ul>
						</ul>
						<input id="inputTags" type="text" value="" class="form-control tags-autocomplete" placeholder="打上故事标签，以空格为分隔符" popover="tooltip" data-trigger="focus" data-html=true data-placement="right" data-html=true data-title="打上故事标签"  placeNotice="">
                    	<span class="help-inline"></span>
					</div>
				</div>
				<div class="action-group">
					<p><span class="help-block"></span></p>
					<label class="control-label col-lg-2" for="inputSettingName"></label>
					<div class="col-lg-10">
						<button type="submit" class="btn btn-lg btn-primary" rel="login">确定</button>
					</div>
				</div><br/><br/>
			</div>
		</div>
    </fieldset>
</form>