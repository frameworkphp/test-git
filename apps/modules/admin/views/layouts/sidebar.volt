<aside class="fixed skin-6">
    <div class="sidebar-inner scrollable-sidebar">
        <div class="size-toggle">
            <a class="btn btn-sm" id="sizeToggle">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="btn btn-sm pull-right" href="{{url('admin/logout')}}">
                <i class="fa fa-power-off"></i>
            </a>
        </div><!-- /size-toggle -->
        <div class="user-block clearfix">
            <img src="{{ auth.getAvatar() }}" alt="User Avatar">
            <div class="detail">
                <strong>{{ auth.getName() }}</strong><span class="badge badge-danger m-left-xs bounceIn animation-delay4">4</span>
                <p>{{ auth.getRole() }}</p>
            </div>
        </div><!-- /user-block -->
        <div class="main-menu">
            <ul>
                <li class="{% if dispatcher.getControllerName() == 'index' %}active{% endif %}">
                    <a href="{{url('admin')}}">
								<span class="menu-icon">
									<i class="fa fa-desktop fa-lg"></i>
								</span>
								<span class="text">
									Dashboard
								</span>
                        <span class="menu-hover"></span>
                    </a>
                </li>
                <li class="{% if dispatcher.getControllerName() == 'user' %}active{% endif %}">
                    <a href="{{url('admin/user')}}">
								<span class="menu-icon">
									<i class="fa fa-user fa-lg"></i>
								</span>
								<span class="text">User</span>
                        <span class="menu-hover"></span>
                    </a>
                </li>
                <li class="openable open {% if dispatcher.getControllerName() == 'page' %}active{% endif %}">
                    <a href="{{url('admin/page')}}">
								<span class="menu-icon">
									<i class="fa fa-file-text fa-lg"></i>
								</span>
								<span class="text">Page</span>
                        <span class="menu-hover"></span>
                    </a>
                    <ul class="submenu">
                        <li><a href="login.html"><span class="submenu-label">Sign in</span></a></li>
                        <li><a href="register.html"><span class="submenu-label">Sign up</span></a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /main-menu -->
    </div><!-- /sidebar-inner -->
</aside>