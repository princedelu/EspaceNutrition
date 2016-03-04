<div  class="skin-blue">
	<?php
		include("header.php");
	?>

	<!-- Right side column. Contains the navbar and content of the page -->
	<aside class="right-side">                
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				Articles du blog
			</h1>
			<ol class="breadcrumb">
				<li><a href="/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li class="active"><a href="/articles"><i class="fa fa-paragraph"></i>Articles du blog</a></li>
			</ol>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="row">
                <div class="col-md-4 col-md-offset-8 text-right">
					<button class="btn btn-link app-btn-add" ng-click="createLoad();">
						<i class="fa fa-plus"></i>
					</button>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-body table-responsive">

							<table id="articles" class="display">

							</table>
							
						</div><!-- /.box-body -->
					</div><!-- /.box -->
				</div>
			</div>
			<div class="row" ng-show="formArticle">
				<form name="articleForm" ng-submit='add()'>
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel" ng-hide="id">Ajout d'un article</h4>
						<h4 class="modal-title" id="myModalLabel" ng-show="id">Modification d'un article</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="titre">Titre</label>
							<ng-form name="subForm2" ng-class="{ 'has-error' : subForm2.$invalid && !subForm2.$pristine }">
								<input type="text" class="form-control" data-ng-model="titre" id="titreArticle" placeholder="Titre" data-ng-required="true">
								<div ng-show="subForm2.$dirty && subForm2.$invalid">
									<span class="help-block" ng-show="subForm2.$error.required"> Titre obligatoire</span>
								</div>
							</ng-form>
						</div>
						<div class="form-group">
							<label for="auteur">Auteur</label>
							<ng-form name="subForm3" ng-class="{ 'has-error' : subForm3.$invalid && !subForm3.$pristine }">
								<input type="text" class="form-control" data-ng-model="auteur" id="auteurArticle" placeholder="Auteur" data-ng-required="true">
								<div ng-show="subForm3.$dirty && subForm3.$invalid">
									<span class="help-block" ng-show="subForm3.$error.required"> Auteur obligatoire</span>
								</div>
							</ng-form>
						</div>
						<div class="form-group">
							<label for="partie1">Partie 1</label>
							<ng-form name="subForm4" ng-class="{ 'has-error' : subForm4.$invalid && !subForm4.$pristine }">
								<textarea type="text" class="form-control" data-ng-model="partie1" id="partie1Article" placeholder="Partie1" data-ng-required="true"></textarea>
								<div ng-show="subForm4.$dirty && subForm4.$invalid">
									<span class="help-block" ng-show="subForm3.$error.required"> Partie1 obligatoire</span>
								</div>
							</ng-form>
						</div>
						
						<div class="form-group">
							<label for="partie2">Partie 2</label>
								<textarea ui-tinymce="{trusted: true}"
								  ng-model="partie2"></textarea>
						</div>
						<div class="form-group">
							<label for="date">Date</label>
							<ng-form name="subForm5" ng-class="{ 'has-error' : subForm5.$invalid && !subForm5.$pristine }">
								<input type="text" class="form-control" data-ng-model="date" id="dateArticle" placeholder="jj-mm-aaaa" data-ng-required="true">
								<div ng-show="subForm5.$dirty && subForm5.$invalid">
									<span class="help-block" ng-show="subForm5.$error.required"> Date obligatoire</span>
								</div>
							</ng-form>
						</div>
						<div class="form-group">
							<label for="categorie">Catégorie</label>
							<ng-form name="subForm6" ng-class="{ 'has-error' : subForm6.$invalid && !subForm6.$pristine }">
								<select class="form-control" data-ng-model="id_categorie" data-ng-options="categorie.libelle_long for categorie in categories" id="categorieArticle" data-ng-required="true"></select>
								<div ng-show="subForm6.$dirty && subForm6.$invalid">
									<span class="help-block" ng-show="subForm6.$error.required"> Catégorie obligatoire</span>
								</div>
							</ng-form>
						</div>
						
					</div>
					
					<div class="modal-footer">
						<input type="hidden" data-ng-model="id" id='id' name="id">
						<button type="button" class="btn btn-default" ng-click="createClose();">Fermer</button>
						<button type="submit" class="btn btn-primary" ng-disabled="utilisateurForm.$invalid" ng-hide="id">Enregistrer</button>
						<button type="submit" class="btn btn-primary" ng-disabled="utilisateurForm.$invalid" ng-show="id">Modifier</button>
					</div>
				</form>
			</div>
		</section><!-- /.content -->
	</aside><!-- /.right-side -->
</div><!-- ./wrapper -->


