<div  class="skin-blue">
	<?php
		include("header.php");
	?>

	<!-- Right side column. Contains the navbar and content of the page -->
	<aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				Listes des utilisateurs
			</h1>
			<ol class="breadcrumb">
				<li><a href="/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li class="active"><a href="/utilisateurs"><i class="fa fa-user"></i>Listes des utilisateurs</a></li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-md-4 col-md-offset-8 text-right">
					<button class="btn btn-link app-btn-add" data-toggle="modal" ng-click="createLoad();">
						<i class="fa fa-plus"></i>
					</button>
				</div>
				<div class="col-xs-12">
					<div class="box">
						<div class="box-body table-responsive">

							<table id="utilisateurs" class="display">

							</table>
							
						</div><!-- /.box-body -->
					</div><!-- /.box -->
				</div>
			</div>
		</section><!-- /.content -->
	</aside><!-- /.right-side -->



	<?php
		include("popupUtilisateur.php");
	?>

</div><!-- ./wrapper -->


