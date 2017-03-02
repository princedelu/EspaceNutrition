<div class="modal fade" id="aTemoignageModal" tabindex="-1" role="dialog" aria-labelledby="aTemoignageModal" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form name="addTemoignageForm" ng-submit='add()'>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
					<h4 class="modal-title" id="myModalLabel" ng-hide="id">Ajout d'un témoignage</h4>
					<h4 class="modal-title" id="myModalLabel" ng-show="id">Modification d'un témoignage</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="prenom" class="labelMandatory">Prenom</label>
						<ng-form name="subForm1" ng-class="{ 'has-error' : subForm1.$invalid && !subForm1.$pristine }">
							<input type="text" class="form-control" data-ng-model="prenom" id="prenom" placeholder="Prenom" data-ng-required="true">
							<div ng-show="subForm1.$dirty && subForm1.$invalid">
								<span class="help-block" ng-show="subForm1.$error.required"> Prenom obligatoire</span>
							</div>
						</ng-form>
					</div>
					<div class="form-group">
						<label for="age" class="labelMandatory">Age</label>
						<ng-form name="subForm2" ng-class="{ 'has-error' : subForm2.$invalid && !subForm2.$pristine }">
							<input type="number" class="form-control" data-ng-model="age" id="age" placeholder="Age" data-min="0" data-max="999" data-ng-required="true">
							<div ng-show="subForm2.$dirty && subForm2.$invalid">
								<span class="help-block" ng-show="subForm2.$error.required"> Age obligatoire</span>
								<span class="help-block" ng-show="subForm2.$error.number"> Age invalide</span>
							</div>
						</ng-form>
					</div>
					<div class="form-group">
						<label for="objectif" class="labelMandatory">Objectif</label>
						<ng-form name="subForm3" ng-class="{ 'has-error' : subForm3.$invalid && !subForm3.$pristine }">
							<input type="text" class="form-control" data-ng-model="objectif" id="objectif" placeholder="Objectif" data-ng-required="true">
							<div ng-show="subForm3.$dirty && subForm3.$invalid">
								<span class="help-block" ng-show="subForm3.$error.required"> Objectif obligatoire</span>
							</div>
						</ng-form>
					</div>
					<div class="form-group">
						<label for="temoignage" class="labelMandatory">Témoignage</label>
						<ng-form name="subForm4" ng-class="{ 'has-error' : subForm4.$invalid && !subForm4.$pristine }">
							<textarea rows="8" class="form-control" data-ng-model="temoignage" id="temoignage" placeholder="Temoignage" data-ng-required="true">
							</textarea>
							<div ng-show="subForm4.$dirty && subForm4.$invalid">
								<span class="help-block" ng-show="subForm4.$error.required"> Témoignage obligatoire</span>
							</div>
						</ng-form>
					</div>
					<div class="form-group">
						<label for="Type">Valide</label>
						<div class="input-group">
							<label>
								<input type="radio" name="optionsTypes" data-ng-model="valide" id="optionsType1" value="1">
								Oui
							</label>
						</div>
						<div class="input-group">
							<label>
								<input type="radio" name="optionsTypes" data-ng-model="valide" id="optionsType2" value="0">
								Non
							</label>
						</div>
					</div>
				</div>
		
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					<button type="submit" class="btn btn-primary" ng-disabled="addTemoignageForm.$invalid" ng-hide="id">Enregistrer</button>
					<button type="submit" class="btn btn-primary" ng-disabled="addTemoignageForm.$invalid" ng-show="id">Modifier</button>
				</div>
			</form>
		</div>
	</div>
</div>