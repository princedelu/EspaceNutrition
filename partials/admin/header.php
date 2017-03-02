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
	                <!-- Notifications: style can be found in dropdown.less -->
                        <li class="dropdown notifications-menu" data-access-level='accessLevels.userOnly' ng-if="notifications.length != 0">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-warning"></i>
                                <span class="label label-warning">{{ notifications.length }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <li ng-repeat="notification in notifications">
                                            <a href="/monrepas/{{notification.ID}}">
                                                <i class="fa fa-comment warning"></i> Repas du {{notification.DATEMESURE}} à {{notification.HEUREMESURE}}
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">Fin des notifications</a></li>
                            </ul>
                        </li>
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
                        <i class="fa fa-users"></i> <span>Utilisateurs</span>
                    </a>
                </li>
				<li ng-class="{active : paiements}" data-access-level='accessLevels.admin'>
                    <a href="/paiements">
                        <i class="fa fa-paypal"></i> <span>Paiements</span>
                    </a>
                </li>
				<li ng-class="{active : abonnements}" data-access-level='accessLevels.admin'>
                    <a href="/abonnements">
                        <i class="fa fa-list"></i> <span>Abonnements</span>
                    </a>
                </li>
                <li ng-class="{active : mesabonnements}" data-access-level='accessLevels.userOnly'>
                    <a href="/mesabonnements">
                        <i class="fa fa-list"></i> <span>Mes abonnements</span>
                    </a>
                </li>
                <li ng-class="{active : mesures}" data-access-level='accessLevels.admin'>
                    <a href="/mesures">
                        <i class="fa fa-area-chart"></i> <span>Nutrition</span>
                    </a>
                </li>
                <li ng-class="{active : mesmesures}" data-access-level='accessLevels.userOnly'>
                    <a href="/mesmesures">
                        <i class="fa fa-area-chart"></i> <span>Nutrition</span>
                    </a>
                </li>
				<li ng-class="{active : articles}" data-access-level='accessLevels.admin'>
                    <a href="/articles">
                        <i class="fa fa-paragraph"></i> <span>Articles</span>
                    </a>
                </li>
				<li ng-class="{active : temoignages}" data-access-level='accessLevels.admin'>
                    <a href="/temoignages">
                        <i class="fa fa-book"></i> <span>Témoignages</span>
                    </a>
                </li>
            
                
            </ul>
        </section>
        <!-- /.sidebar -->                
        
    </aside>
