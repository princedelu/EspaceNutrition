
<!-- Popup profil -->
<div class="modal fade" id="bs-profil" tabindex="-1" role="dialog" aria-labelledby="profil" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form name="profilForm" ng-submit='updateProfil()'>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
					<h4 class="modal-title" id="myModalLabel">Votre profil</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="email">Email</label>
						<ng-form name="subForm2" ng-class="{ 'has-error' : subForm2.$invalid && !subForm2.$pristine }">
							<input type="email" class="form-control" data-ng-model="email" id="emailUtilisateur" placeholder="Email" data-ng-required="true" disabled>
							<div ng-show="subForm2.$dirty && subForm2.$invalid">
								<span class="help-block" ng-show="subForm2.$error.required"> Email obligatoire</span>
								<span class="help-block" ng-show="subForm2.$error.email"> Email invalide</span>
							</div>
						</ng-form>
					</div>
					<div class="form-group">
						<label for="nom">Mot de passe</label>
						<ng-form name="subForm6" ng-class="{ 'has-error' : subForm6.$invalid && !subForm6.$pristine }">
							<input type="password" class="form-control" data-ng-model="password" id="passwordUtilisateur" placeholder="Mot de passe">
							<div ng-show="subForm6.$dirty && subForm6.$invalid">
								<span class="help-block" ng-show="subForm6.$error.required"> Mot de passe obligatoire</span>
							</div>
						</ng-form>
					</div>
					<div class="form-group">
						<label for="nom">Confirmation mot de passe</label>
						<ng-form name="subForm7" ng-class="{ 'has-error' : subForm7.$invalid && !subForm7.$pristine }">
							<input type="password" class="form-control" data-ng-model="passwordConfirm" id="passwordConfirmUtilisateur" placeholder="Mot de passe">
							<div ng-show="subForm7.$dirty && subForm7.$invalid">
								<span class="help-block" ng-show="subForm7.$error.required"> Confirmation du mot de passe obligatoire</span>
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
							<input type="text" class="form-control" data-ng-model="datenaissance" id="dateNaissanceProfil" placeholder="jj-mm-aaaa" data-ng-required="true">
							<div ng-show="subForm5.$dirty && subForm5.$invalid">
								<span class="help-block" ng-show="subForm5.$error.required"> Date de naissance obligatoire</span>
							</div>
						</ng-form>
					</div>
				</div>

				<div class="modal-footer">
					<input type="hidden" data-ng-model="id" id='id' name="id">
					<span class="help-block" ng-show="error"> {{error}}</span>
					<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					<button type="submit" class="btn btn-primary" ng-disabled="profilForm.$invalid">Modifier</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- /Popup profil -->	

