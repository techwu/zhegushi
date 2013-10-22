<?php /* Smarty version 2.6.26, created on 2013-10-09 12:49:36
         compiled from /Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/admin/navigation/navbar.tmpl.html */ ?>
 <div class="navbar navbar-inverse navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
">后台管理</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <?php if (empty ( $this->_tpl_vars['session_user_id'] ) || $this->_tpl_vars['session_user_id'] == 0): ?>
				<li><a href="signin">登陆|注册</a></li>
			<?php else: ?>
				<li class="dropdown">
					<a id="user-menu" class="dropdown-toggle" data-toggle="dropdown" role="button" data-target="#">
						<span><?php if (empty ( $this->_tpl_vars['session_user_info']['admin'] )): ?>我<?php else: ?><?php echo $this->_tpl_vars['session_user_info']['admin']; ?>
<?php endif; ?></span>
						<i class="icon-down"></i>
					</a>
					<ul class="dropdown-menu" rel="user-menu" role="menu" aria-labelledby="user-menu">
						<li>
							<a href="notification">发系统私信</a>
						</li>
						<li class="divider"></li>
						<li>
						    <a href="/setting">设置</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="/user/logout">退出</a>
						</li>
					</ul>
				</li>
			<?php endif; ?>
          </ul>
<!--           <form class="navbar-form navbar-right" method="GET" action="search/s" >
		  	<div class="form-group">
				<input type="text" class="form-control placeholder" id="nav-search-input" placeholder="36氪+创业搜索" name="q">
			</div>
			<div class="form-group">
				<button  id="nav-search-submit" type="submit" class="btn btn-success"><i class="glyphicon glyphicon-search"></i></button>
			</div>
		  </form>
 -->        </div>
      </div>
    </div>