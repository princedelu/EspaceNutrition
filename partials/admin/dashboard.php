
		
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
            <div class="row">
                <div class="col-lg-4">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>
                                Repas & humeur
                            </h3>
                            <p>
                                Dernier repas saisi : le 23/08/2014
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-cutlery"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            Ajouter un repas <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div><!-- ./col -->
                <div class="col-lg-4">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>
                                Poids
                            </h3>
                            <p>
								Dernière mesure : 67 kg
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-area-chart"></i>
                        </div>
                        <a href="#" class="small-box-footer">
							Ajouter une nouvelle mesure<i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div><!-- ./col -->
                <div class="col-lg-4">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>
                                Activités physiques
                            </h3>
                            <p>
                                Dernière activité : le 23/08/2014
                            </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-bicycle"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            Ajouter une activité physique <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div><!-- ./col -->
            </div><!-- /.row -->
			<!-- Graph -->
            <div class="row">
				<div class="boxEspace box-primary">
                    <div class="box-header">
						<i class="fa fa-bar-chart-o"></i>
                        <h3 class="box-title">Votre courbe de poids et d'humeur</h3>
                    </div>
                    <div class="box-body chart-responsive">
                        <d3-courbe-poids></d3-courbe-poids>
                    </div><!-- /.box-body -->
                </div>
			</div><!-- /.Graph -->
		</section><!-- /.content -->
	</aside><!-- /.right-side -->

</div>



