<!-- Popup repas -->
<div class="modal fade" id="bs-repas" tabindex="-1" role="dialog" aria-labelledby="poids" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form name="repasForm" ng-submit='addRepas()'>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
					<h4 class="modal-title" id="myModalLabel" ng-hide="id">Ajout d'un repas</h4>
                    <h4 class="modal-title" id="myModalLabel" ng-show="id">Modification d'un repas</h4>
				</div>
				<div class="modal-body">
                    <div class="form-group" ng-show="mesures">
						<label for="dateMesure">Utilisateur</label>
                        <ng-form name="subForm1" ng-class="{ 'has-error' : subForm1.$invalid && !subForm1.$pristine }">
						    <select data-ng-model="usermesure" data-ng-options="user.email for user in users" id="usermesure" data-ng-required="true" ng-hide="id"></select>
                            <div ng-show="id">{{ emailMesure }}</div>
						</ng-form>
					</div> 
					<div class="form-group">
						<label for="dateRepasMesure">Date du repas</label>
						<ng-form name="subForm2" ng-class="{ 'has-error' : subForm2.$invalid && !subForm2.$pristine }">
							<input type="text" class="form-control" data-ng-model="dateRepasMesure" id="dateRepasMesure" placeholder="jj-mm-aaaa" data-ng-required="true">
							<div ng-show="subForm2.$dirty && subForm2.$invalid">
								<span class="help-block" ng-show="subForm2.$error.required"> Date du repas obligatoire</span>
							</div>
						</ng-form>
					</div> 
                    <div class="form-group">
						<label for="heureRepasMesure">Heure du repas</label>
						<ng-form name="subForm3" ng-class="{ 'has-error' : subForm3.$invalid && !subForm3.$pristine }">
							<input id="heureRepasMesure" type="text" class="form-control" data-ng-model="heureRepasMesure" id="heureRepasMesure" data-ng-required="true">
                            <span class="add-on"><i class="icon-time"></i></span>
                            
							<div ng-show="subForm3.$dirty && subForm3.$invalid">
								<span class="help-block" ng-show="subForm3.$error.required"> Heure du repas obligatoire</span>               
							</div>
						</ng-form>
					</div>
                    <div class="form-group">
						<label for="repasMesure">Repas (Décrivez votre repas)</label>
						<ng-form name="subForm4">
							<textarea class="form-control" data-ng-model="repasMesure" id="repasMesure" data-ng-required="true"></textarea>
                            <div ng-show="subForm4.$dirty && subForm4.$invalid">
								<span class="help-block" ng-show="subForm4.$error.required"> Repas obligatoire</span>               
							</div>
						</ng-form>
					</div>
                    <div class="form-group">
						<label for="commentaireRepasMesure">Commentaires divers / Humeur / Sensation</label>
						<ng-form name="subForm4">
							<textarea class="form-control" data-ng-model="commentaireRepasMesure" id="commentaireRepasMesure"></textarea>
						</ng-form>
					</div>
                    <div class="form-group">
						<label for="commentaireRepasDietMesure">Commentaires de la diététicienne</label>
						<ng-form name="subForm5">
							<textarea class="form-control" data-ng-model="commentaireRepasDietMesure" id="commentaireRepasDietMesure" ng-show="mesures"></textarea>
                            <div ng-show="mesmesures">{{ commentaireRepasDietMesure }}</div>
						</ng-form>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" data-ng-model="id" id='id' name="id">
					<span class="help-block" ng-show="doublon"> Une mesure existe déjà pour cette date</span>
                    <span class="help-block" ng-show="abonnementinactif"> Vous ne disposez pas d'un abonnement actif, veuillez contacter votre diététicienne</span>
                    <span class="help-block" ng-show="userTous"> Veuillez saisir un utilisateur</span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					<button type="submit" class="btn btn-primary" ng-disabled="repasForm.$invalid" ng-hide="id">Enregistrer</button>
					<button type="submit" class="btn btn-primary" ng-disabled="repasForm.$invalid" ng-show="id">Modifier</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- /Popup poids -->




