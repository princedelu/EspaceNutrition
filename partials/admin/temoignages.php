<div  class="skin-blue">
	<?php
		include("header.php");
	?>

	<!-- Right side column. Contains the navbar and content of the page -->
	<aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				Témoignages du site
			</h1>
			<ol class="breadcrumb">
				<li><a href="/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li class="active"><a href="/temoignages"><i class="fa fa-book"></i>Témoignages du site</a></li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			
			<div class="row">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-body table-responsive">

							<table id="temoignages" class="display">

							</table>
							
						</div><!-- /.box-body -->
					</div><!-- /.box -->
				</div>
			</div>
		</section><!-- /.content -->
	</aside><!-- /.right-side -->
	
	<?php
		include("popupTemoignage.php");
	?>
</div><!-- ./wrapper -->


