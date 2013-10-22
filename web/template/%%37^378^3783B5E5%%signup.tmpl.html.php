<?php /* Smarty version 2.6.26, created on 2013-10-14 23:43:17
         compiled from /Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/grape/container/sign/signup.tmpl.html */ ?>
<div class="container">
	<form rel="form-signup" role="form" class="form-signin form-col form-horizontal" action="user/ajax_signupUser" method="post">
		<fieldset>
		  <div class="form-group center">
		    快速注册这故事账号，分享你的故事
		  </div>
		  <div class="form-group <?php if (! empty ( $this->_tpl_vars['error'] )): ?>has-error<?php endif; ?>">
		    <label for="inputName" class="col-sm-2 control-label">昵称</label>
		    <div class="col-sm-10">
		    <input type="text" class="form-control check-exist placeholder on" name="username" placeholder="昵称" rel="input-text" id="inputName" value="" popover="tooltip" data-trigger="focus" data-html="true" data-placement="right" data-title="4-30个字符，支持中英文、数字、“_”" data-original-title="">
		    </div>
		  </div>
		  <div class="form-group <?php if (! empty ( $this->_tpl_vars['error'] )): ?>has-error<?php endif; ?>">
		    <label for="inputEmail" class="col-sm-2 control-label">邮箱</label>
		    <div class="col-sm-10">
		    <input type="text" class="form-control check-exist placeholder on" name="email" placeholder="邮箱" rel="input-email" id="inputEmail" value="">
		    </div>
		  </div>
		  <div class="form-group <?php if (! empty ( $this->_tpl_vars['error'] )): ?>has-error<?php endif; ?>">
		    <label for="inputPassword" class="col-sm-2 control-label">密码</label>
		    <div class="col-sm-10">
		    <input type="password" class="form-control on placeholder" name="pwd" placeholder="密码" id="inputPassword">
		    </div>
		  </div>
		  <div class="form-group <?php if (! empty ( $this->_tpl_vars['error'] )): ?>has-error<?php endif; ?>">
		    <label for="inputCheck" class="col-sm-2 control-label">确认密码</label>
		    <div class="col-sm-10">
		    <input type="password" class="form-control on placeholder" name="repwd" placeholder="确认密码" id="inputCheck">
		    </div>
		  </div>
		  <div class="action-group">
		  	<span class="help-block <?php if (! empty ( $this->_tpl_vars['error'] )): ?>alert<?php endif; ?>"><?php if (! empty ( $this->_tpl_vars['error'] )): ?><?php echo $this->_tpl_vars['error']; ?>
<?php endif; ?></span>
		    <button type="submit" class="btn btn-primary btn-block" rel="signup">注册</button>
		  </div>
		</fieldset>
    </form>
</div>