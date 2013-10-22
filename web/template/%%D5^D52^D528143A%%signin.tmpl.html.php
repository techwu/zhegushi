<?php /* Smarty version 2.6.26, created on 2013-10-09 12:58:20
         compiled from /Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/admin/container/sign/signin.tmpl.html */ ?>
<div class="container">
	<form class="form-signin form-col" rel="form-login" action="user/ajax_logon" method="post">
		<fieldset>
			<div class="form-group <?php if (! empty ( $this->_tpl_vars['error'] )): ?>has-error<?php endif; ?>" >
				<i class="icon-inside icon-email"></i>
			    <input type="text" class="form-control on placeholder" placeholder="用户名" name="email" rel="input-text" >
			</div>
			<div class="form-group <?php if (! empty ( $this->_tpl_vars['error'] )): ?>has-error<?php endif; ?>">
			    <i class="icon-inside icon-password"></i>
			    <input type="password" class="form-control on placeholder" placeholder="密码" name="password" >
			    <a href="user/forgetpwd" class="block center">忘记密码？</a>|&nbsp;&nbsp;<a href="signup" class="block center">还没有账号？先注册</a>
			</div>
			  
			<div class="action-group">
			    <label class="checkbox inline">
			      <input type="checkbox" name="set-cookie" checked>记住密码
			    </label> 
			    <p><span class="help-block <?php if (! empty ( $this->_tpl_vars['error'] )): ?>alert<?php endif; ?>"><?php if (! empty ( $this->_tpl_vars['error'] )): ?><?php echo $this->_tpl_vars['error']; ?>
<?php endif; ?></span></p>
			    <button type="submit" class="btn btn-lg btn-primary btn-block" rel="login">登录</button>
			</div>
        </fieldset>
	</form>
</div> 