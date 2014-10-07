
		
<div  class="skin-blue">
	<?php
		include("header.php");
	?>

	<!-- Right side column. Contains the navbar and content of the page -->
	<aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				Dashboard
				<small>Control panel</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<!-- Small boxes (Stat box) -->
            <div class="row" data-access-level='accessLevels.userOnly'>
                <div class="col-lg-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>
                                Poids
                            </h3>
                            <p>
								Dernière mesure : {{lastPoids}} kg<br/>
                                Date de dernière mesure : {{ lastPoidsDate }}
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-area-chart"></i>
                        </div>
                        <a href="/addpoids" class="small-box-footer">
							Ajouter une nouvelle mesure <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div><!-- ./col -->
                <div class="col-lg-6">
                    <!-- small box -->
                    <div class="small-box bg-orange">
                        <div class="inner">
                            <h3>
                                Carnet alimentaire
                            </h3>
                            <p>
                                Date de dernière saisie : {{ lastRepasDate }}<br/>
                                &nbsp;
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-cutlery"></i>
                        </div>
                        <a href="/addrepas" class="small-box-footer">
                            Ajouter un repas <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div><!-- ./col -->
            </div><!-- /.row -->
			<!-- Graph -->
            <div class="row" data-access-level='accessLevels.userOnly'>
				<div class="boxEspace box-primary">
                    <div class="box-header">
						<i class="fa fa-bar-chart-o"></i>
                        <h3 class="box-title">Votre courbe de poids</h3>
                    </div>
                    <div class="box-body chart-responsive">
                        <d3-courbe-poids data="dataPoids"></d3-courbe-poids>
                    </div><!-- /.box-body -->
                </div>
			</div><!-- /.Graph -->
            <div class="row" data-access-level='accessLevels.admin'>
				<div class="col-xs-12">
                    <h4>Liste des notifications à traiter</h4><hr/>
					<div class="box">
						<div class="box-body table-responsive">

							<table id="notifications" class="display">

							</table>
							
						</div><!-- /.box-body -->
					</div><!-- /.box -->
				</div>
			</div>
		</section><!-- /.content -->
	</aside><!-- /.right-side -->

</div>



