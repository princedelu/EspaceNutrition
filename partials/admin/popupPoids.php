<!-- Popup poids -->
<div class="modal fade" id="bs-poids" tabindex="-1" role="dialog" aria-labelledby="poids" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form name="poidsForm" ng-submit='addPoids()'>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
					<h4 class="modal-title" id="myModalLabel" ng-hide="id">Ajout d'une mesure de poids</h4>
                    <h4 class="modal-title" id="myModalLabel" ng-show="id">Modification d'une mesure de poids</h4>
				</div>
				<div class="modal-body">
                    <div class="form-group" ng-show="mesures">
						<label for="dateMesure">Utilisateur</label>
                        <ng-form name="subForm4" ng-class="{ 'has-error' : subForm4.$invalid && !subForm4.$pristine }">
						    <select data-ng-model="usermesure" data-ng-options="user.email for user in users" id="usermesure" data-ng-required="true" ng-hide="id"></select>
                            <div ng-show="id">{{ emailMesure }}</div>
						</ng-form>
					</div> 
					<div class="form-group">
						<label for="dateMesure">Date de la mesure</label>
						<ng-form name="subForm1" ng-class="{ 'has-error' : subForm1.$invalid && !subForm1.$pristine }">
							<input type="text" class="form-control" data-ng-model="dateMesure" id="dateMesure" placeholder="jj-mm-aaaa" data-ng-required="true">
							<div ng-show="subForm1.$dirty && subForm1.$invalid">
								<span class="help-block" ng-show="subForm1.$error.required"> Date obligatoire</span>
							</div>
						</ng-form>
					</div> 
                    <div class="form-group">
						<label for="poidsMesure">Poids</label>
						<ng-form name="subForm2" ng-class="{ 'has-error' : subForm2.$invalid && !subForm2.$pristine }">
							<input type="text" class="form-control" name="poidsMesure" data-ng-model="poidsMesure" id="poidsMesure" data-ng-required="true" smart-float>
                            
							<div ng-show="subForm2.$dirty && subForm2.$invalid">
								<span class="help-block" ng-show="subForm2.$error.required"> Poids obligatoire</span>
                                <span class="help-block" ng-show="subForm2.poidsMesure.$error.float"> Le poids est un entier ou un chiffre à virgule</span>                                
							</div>
						</ng-form>
					</div>
                    <div class="form-group">
						<label for="commentaireMesure">Commentaires</label>
						<ng-form name="subForm3">
							<textarea class="form-control" data-ng-model="commentaireMesure" id="commentaireMesure"></textarea>
						</ng-form>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" data-ng-model="id" id='id' name="id">
					<span class="help-block" ng-show="doublon"> Une mesure existe déjà pour cette date</span>
                    <span class="help-block" ng-show="abonnementinactif"> Vous ne disposez pas d'un abonnement actif, veuillez contacter votre diététicienne</span>
                    <span class="help-block" ng-show="userTous"> Veuillez saisir un utilisateur</span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					<button type="submit" class="btn btn-primary" ng-disabled="poidsForm.$invalid" ng-hide="id">Enregistrer</button>
                    <button type="button" class="btn btn-warning" ng-show="id" ng-click="supprimerPoids(id);">Supprimer</button>
					<button type="submit" class="btn btn-primary" ng-disabled="poidsForm.$invalid" ng-show="id">Modifier</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- /Popup poids -->




