<!-- Popup abonnement -->
<div class="modal fade" id="bs-abonnement" tabindex="-1" role="dialog" aria-labelledby="abonnement" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form name="abonnementForm" ng-submit='add()'>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
					<h4 class="modal-title" id="myModalLabel" ng-hide="id">Ajout d'un abonnement</h4>
					<h4 class="modal-title" id="myModalLabel" ng-show="id">Modification d'un abonnement</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="email">Email</label>
						<ng-form name="subForm2" ng-class="{ 'has-error' : subForm2.$invalid && !subForm2.$pristine }">
							<input type="email" class="form-control" data-ng-model="email" id="emailAbonnement" placeholder="Email" data-ng-required="true">
							<div ng-show="subForm2.$dirty && subForm2.$invalid">
								<span class="help-block" ng-show="subForm2.$error.required"> Email obligatoire</span>
								<span class="help-block" ng-show="subForm2.$error.email"> Email invalide</span>
							</div>
						</ng-form>
					</div>
					<div class="form-group">
						<label for="datedebut">Date de début</label>
						<ng-form name="subForm3" ng-class="{ 'has-error' : subForm3.$invalid && !subForm3.$pristine }">
							<input type="text" class="form-control" data-ng-model="datedebut" id="datedebut" placeholder="jj-mm-aaaa" data-ng-required="true">
							<div ng-show="subForm3.$dirty && subForm3.$invalid">
								<span class="help-block" ng-show="subForm3.$error.required"> Date de début à donner</span>
							</div>
						</ng-form>
					</div>
					<div class="form-group">
						<label for="datefin">Date de fin</label>
						<ng-form name="subForm4" ng-class="{ 'has-error' : subForm4.$invalid && !subForm4.$pristine }">
							<input type="text" class="form-control" data-ng-model="datefin" name="datefin" id="datefin" placeholder="jj-mm-aaaa" data-ng-required="true">
							<div ng-show="subForm4.$dirty && subForm4.$invalid">
								<span class="help-block" ng-show="subForm4.$error.required"> Date de fin obligatoire</span>
							</div>
						</ng-form>
					</div>
					<div class="form-group">
						<label for="Type">Type</label>
						<div class="input-group">
							<label>
								<input type="radio" name="optionsTypes" data-ng-model="type" id="optionsType1" value="1">
								Suivi poids
							</label>
						</div>
						<div class="input-group">
							<label>
								<input type="radio" name="optionsTypes" data-ng-model="type" id="optionsType2" value="2">
								Suivi poids + humeur
							</label>
						</div>
                        <div class="input-group">
							<label>
								<input type="radio" name="optionsTypes" data-ng-model="type" id="optionsType3" value="3">
								Suivi poids + humeur + repas
							</label>
						</div>
					</div>
				</div>
				
				<div class="modal-footer">
					<input type="hidden" data-ng-model="id" id='id' name="id">
					<span class="help-block" ng-show="doublon"> Un abonnement pour cet utilisateur existe déjà sur la période indiquée</span>
					<span class="help-block" ng-show="errorDate"> La date de fin doit être postérieure à la date de début</span>
                    <span class="help-block" ng-show="pbuser"> L'utilisateur n'existe pas</span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					<button type="submit" class="btn btn-primary" ng-disabled="abonnementForm.$invalid" ng-hide="id">Enregistrer</button>
					<button type="submit" class="btn btn-primary" ng-disabled="abonnementForm.$invalid" ng-show="id">Modifier</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- /Popup abonnement -->




