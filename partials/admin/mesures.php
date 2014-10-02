<div  class="skin-blue">
	<?php
		include("header.php");
	?>

	<!-- Right side column. Contains the navbar and content of the page -->
	<aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1 ng-show="mesures">
				Mesures des utilisateurs
			</h1>
            <h1 ng-show="mesmesures">
				Mes mesures
			</h1>
			<ol class="breadcrumb">
				<li><a href="/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li class="active" ng-show="mesures"><a href="/mesures"><i class="fa fa-area-chart"></i>Mesures des utilisateurs</a></li>
                <li class="active" ng-show="mesmesures"><a href="/mesmesures"><i class="fa fa-area-chart"></i>Mes mesures</a></li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="row">
                <div class="col-xs-6 text-left" ng-show="mesures">
					Utilisateur : <select data-ng-model="usermesure" data-ng-options="user.email for user in users" id="usermesure" data-ng-required="true" ng-change="changeUserMesure()"></select>
				</div>
                <div class="text-right" ng-class="{col-xs-6:mesures,col-xs-12:mesmesures}">
					<button class="btn btn-info" data-toggle="modal" ng-click="createPoidsLoad();">
                        Ajout d'une mesure de poids
					</button>
                    <button class="btn btn-warning" data-toggle="modal" ng-click="createRepasLoad();">
                        Ajout d'un repas
					</button>
				</div>
              </div>
              <br/>
              <div class="row"> 
				<div class="col-xs-12">
					<div class="box">
						<div class="box-body table-responsive">

							<table id="mesures" class="display">

							</table>
							
						</div><!-- /.box-body -->
					</div><!-- /.box -->
				</div>
			</div>
		</section><!-- /.content -->
	</aside><!-- /.right-side -->

    <?php
		include("popupPoids.php");
	?>

</div><!-- ./wrapper -->


