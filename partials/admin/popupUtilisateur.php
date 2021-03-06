<!-- Popup utilisateur -->
<div class="modal fade" id="bs-ajoututilisateur" tabindex="-1" role="dialog" aria-labelledby="ajoututilisateur" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form name="utilisateurForm" ng-submit='add()'>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
					<h4 class="modal-title" id="myModalLabel" ng-hide="id">Ajout d'un utilisateur</h4>
					<h4 class="modal-title" id="myModalLabel" ng-show="id">Modification d'un utilisateur</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="email">Email</label>
						<ng-form name="subForm2" ng-class="{ 'has-error' : subForm2.$invalid && !subForm2.$pristine }">
							<input type="email" class="form-control" data-ng-model="email" id="emailUtilisateur" placeholder="Email" data-ng-required="true">
							<div ng-show="subForm2.$dirty && subForm2.$invalid">
								<span class="help-block" ng-show="subForm2.$error.required"> Email obligatoire</span>
								<span class="help-block" ng-show="subForm2.$error.email"> Email invalide</span>
							</div>
						</ng-form>
					</div>
					<div class="form-group">
						<label for="nom">Nom</label>
						<ng-form name="subForm3" ng-class="{ 'has-error' : subForm3.$invalid && !subForm3.$pristine }">
							<input type="text" class="form-control" data-ng-model="nom" id="nomUtilisateur" placeholder="Nom" data-ng-required="true">
							<div ng-show="subForm3.$dirty && subForm3.$invalid">
								<span class="help-block" ng-show="subForm3.$error.required"> Nom obligatoire</span>
							</div>
						</ng-form>
					</div>
					<div class="form-group">
						<label for="prenom">Prénom</label>
						<ng-form name="subForm4" ng-class="{ 'has-error' : subForm4.$invalid && !subForm4.$pristine }">
							<input type="text" class="form-control" data-ng-model="prenom" id="prenomUtilisateur" placeholder="Prénom" data-ng-required="true">
							<div ng-show="subForm4.$dirty && subForm4.$invalid">
								<span class="help-block" ng-show="subForm4.$error.required"> Prénom obligatoire</span>
							</div>
						</ng-form>
					</div>
					<div class="form-group">
						<label for="datenaissance">Date de naissance</label>
						<ng-form name="subForm5" ng-class="{ 'has-error' : subForm5.$invalid && !subForm5.$pristine }">
							<input type="text" class="form-control" data-ng-model="datenaissance" id="dateNaissanceUtilisateur" placeholder="jj-mm-aaaa" data-ng-required="true">
							<div ng-show="subForm5.$dirty && subForm5.$invalid">
								<span class="help-block" ng-show="subForm5.$error.required"> Date de naissance obligatoire</span>
							</div>
						</ng-form>
					</div>
					<div class="form-group">
						<label for="role">Role</label>
						<div class="input-group">
							<label>
								<input type="radio" name="optionsRoles" data-ng-model="role" id="optionsRole1" value="1">
								Utilisateur
							</label>
						</div>
						<div class="input-group">
							<label>
								<input type="radio" name="optionsRoles" data-ng-model="role" id="optionsRole2" value="2">
								Admin
							</label>
						</div>
					</div>
					<div class="form-group" ng-show="id">
						<label for="actif">Actif</label>
						<ng-form name="subForm6" ng-class="{ 'has-error' : subForm6.$invalid && !subForm6.$pristine }">
							<div class="input-group">
								<label>
									<input type="radio" name="optionsActif" data-ng-model="actif" id="optionsActif1" value="0">
									Non actif
								</label>
							</div>
							<div class="input-group">
								<label>
									<input type="radio" name="optionsActif" data-ng-model="actif" id="optionsActif2" value="1">
									Actif
								</label>
							</div>
							<div ng-show="subForm6.$dirty && subForm6.$invalid">
								<span class="help-block" ng-show="subForm6.$error.required"> Activité obligatoire</span>
							</div>
						</ng-form>
					</div>
				</div>
				
				<div class="modal-footer">
					<input type="hidden" data-ng-model="id" id='id' name="id">
					<span class="help-block" ng-show="doublon"> Cet email est déjà existant</span>
					<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					<button type="submit" class="btn btn-primary" ng-disabled="utilisateurForm.$invalid" ng-hide="id">Enregistrer</button>
					<button type="submit" class="btn btn-primary" ng-disabled="utilisateurForm.$invalid" ng-show="id">Modifier</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- /Popup utilisateur -->




