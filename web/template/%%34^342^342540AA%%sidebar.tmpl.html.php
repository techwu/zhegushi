<?php /* Smarty version 2.6.26, created on 2013-10-11 15:44:55
         compiled from /Users/wumin/Zend/workspaces/DefaultWorkspace/gushire/template/admin/common/sidebar/sidebar.tmpl.html */ ?>
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->        
			<ul class="page-sidebar-menu">
				<li>
					<div class="sidebar-toggler hidden-phone"></div>
				</li>
				<li>
					<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
					<form class="sidebar-search" action="search/s">
						<div class="input-box">
							<a href="javascript:;" class="remove"></a>
							<input type="text" placeholder="这故事搜索" name="q"/>
							<input type="button" class="submit" />
						</div>
					</form>
					<!-- END RESPONSIVE QUICK SEARCH FORM -->
				</li>
				<li class="start <?php if ($this->_tpl_vars['action'] == 'welcome'): ?>active<?php endif; ?>">
					<a href="<?php echo $this->_tpl_vars['BASE_URL']; ?>
">
					<i class="icon-home"></i> 
					<span class="title">数据统计</span>
					<span class="selected"></span>
					</a>
				</li>
				<li <?php if ($this->_tpl_vars['action'] == 'story'): ?>class="active"<?php endif; ?>>
					<a href="javascript:;">
						<i class="icon-barcode"></i> 
						<span class="title">故事信息</span>
						<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if ($this->_tpl_vars['method'] == 'index'): ?>class="active"<?php endif; ?>><a href="story">所有故事</a></li>
						<li <?php if ($this->_tpl_vars['method'] == 'validate'): ?>class="active"<?php endif; ?>><a href="story/validate">待审核故事</a></li>
						<li <?php if ($this->_tpl_vars['method'] == 'validated'): ?>class="active"<?php endif; ?>><a href="story/validated">审核通过故事</a></li>
						<li <?php if ($this->_tpl_vars['method'] == 'recommended'): ?>class="active"<?php endif; ?>><a href="story/recommended">首页推荐故事</a></li>
					</ul>
				</li>
				<li <?php if ($this->_tpl_vars['action'] == 'circle'): ?>class="active"<?php endif; ?>>
					<a href="javascript:;">
					<i class="icon-group"></i> 
					<span class="title">用户</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if ($this->_tpl_vars['method'] == 'index'): ?>class="active"<?php endif; ?>><a href="circle">用户统计</a></li>
						<li <?php if ($this->_tpl_vars['method'] == 'apply'): ?>class="active"<?php endif; ?>>
							<a href="javascript:;">
							投资人审核
							<span class="arrow"></span>
							</a>
							<ul class="sub-menu">
								<li><a href="circle/investor/apply">投资人审核</a></li>
								<li><a href="circle/investor/applydisable">未通过审核投资人</a></li>
							</ul>
						</li>
					</ul>
				</li>
				
				<li <?php if ($this->_tpl_vars['action'] == 'service'): ?>class="active"<?php endif; ?>>
					<a href="service">
						<i class="icon-cloud"></i> 
						<span class="title">标签管理</span>
						<span class="arrow"></span>
					</a>
					<ul class="sub-menu">
						<li <?php if ($this->_tpl_vars['method'] == 'add'): ?>class="active"<?php endif; ?>><a href="service/add" target="_blank">新增标签</a></li>
						<li <?php if ($this->_tpl_vars['method'] == 'uses'): ?>class="active"<?php endif; ?>><a href="uses">标签管理</a></li>
					</ul>
				</li>
				
				<li <?php if ($this->_tpl_vars['action'] == 'timeline'): ?>class="active"<?php endif; ?>>
					<a href="javascript:;">
					<i class="icon-time"></i> 
					<span class="title">专题</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li <?php if ($this->_tpl_vars['method'] == 'index'): ?>class="active"<?php endif; ?>><a href="timeline?index">专题列表</a></li>
						<li <?php if ($this->_tpl_vars['method'] == 'apply'): ?>class="active"<?php endif; ?>><a href="timeline/index?s=apply">新增专题</a></li>
					</ul>
				</li>
				<li class="last">
					<a href="javascript:;">
						<i class="icon-gift"></i> 
						<span class="title">运营管理</span>
						<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li >
							<a href="">运营人员列表</a>
						</li>
					</ul>
				</li>
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>