<div ng-controller="EspaceNutritionCtrl">		
	<header class="header" >
		<a href="/dashboard" class="logo">
	        <!-- Add the class icon to your logo image or logo icon to add the margining -->
	        Espace Nutrition
	    </a>
	    <!-- Header Navbar: style can be found in header.less -->
	    <nav class="navbar navbar-static-top" role="navigation" >
			<!-- Sidebar toggle button-->
            <a href="#" class="navbar-btn sidebar-toggle" ng-click="offcanvas()" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
	        <!-- Sidebar toggle button-->
	        <div class="navbar-right">
	            <ul class="nav navbar-nav">
	                
	                <!-- User Account: style can be found in dropdown.less -->
	                <li class="dropdown user user-menu">
	                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
	                        <i class="fa fa-user"></i>
	                        <span>{{user.email}}<i class="caret"></i></span>
	                    </a>
	                    <ul class="dropdown-menu">
	                        <!-- Menu Footer-->
	                        <li class="user-footer">
	                            <div class="pull-left">
	                                <a href="#" class="btn btn-default btn-flat" ng-click="monprofilLoad();">Mon profil</a>
	                            </div>
	                            <div class="pull-right">
	                                <a href="#" class="btn btn-default btn-flat" ng-click="logout()">Déconnexion</a>
	                            </div>
	                        </li>
	                    </ul>
	                </li>
	            </ul>
	        </div>
	    </nav>
	</header>
	<?php
		include("popupProfil.php");
	?>
</div>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
<!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li ng-class="{active : dashboard}">
                    <a href="/dashboard">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li ng-class="{active : utilisateurs}" data-access-level='accessLevels.admin'>
                    <a href="/utilisateurs">
                        <i class="fa fa-th"></i> <span>Utilisateurs</span>
                    </a>
                </li>
                
            </ul>
        </section>
        <!-- /.sidebar -->                
        
    </aside>
