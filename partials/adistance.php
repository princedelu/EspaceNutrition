		<!-- adistance Section -->
        <section class="success" id="adistance">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2>A distance</h2>
                        <hr class="star-light">
                    </div>
                </div>
                <div class="row" >
                    <center>En construction</center>
                </div>
            </div>
        </section>

		<!-- Popup go paiement -->
		<div class="modal fade" id="bs-paiement" tabindex="-1" role="dialog" aria-labelledby="paiement" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form name="paiementForm" ng-submit='redirectionPaiement()'>
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
							<h4 class="modal-title" id="myModalLabel">Explication</h4>
						</div>
						<div class="modal-body">
							Merci de remplir le formulaire suivant pour nous indiquer vos informations.<br/>
							Une fois, les champs remplis, cliquer sur payer et vous serez alors rediriger sur le site Paypal pour effectuer le paiement.<br/>
							<div class="form-group">
								<label for="nom">Nom</label>
								<ng-form name="subForm1" ng-class="{ 'has-error' : subForm1.$invalid && !subForm1.$pristine }">
									<input type="text" class="form-control" data-ng-model="nom" id="nomUtilisateur" placeholder="Nom" data-ng-required="true">
									<div ng-show="subForm1.$dirty && subForm1.$invalid">
										<span class="help-block" ng-show="subForm1.$error.required"> Nom obligatoire</span>
									</div>
								</ng-form>
							</div>
							<div class="form-group">
								<label for="prenom">Prénom</label>
								<ng-form name="subForm2" ng-class="{ 'has-error' : subForm2.$invalid && !subForm2.$pristine }">
									<input type="text" class="form-control" data-ng-model="prenom" id="prenomUtilisateur" placeholder="Prénom" data-ng-required="true">
									<div ng-show="subForm2.$dirty && subForm2.$invalid">
										<span class="help-block" ng-show="subForm2.$error.required"> Prénom obligatoire</span>
									</div>
								</ng-form>
							</div>
							<div class="form-group">
								<label for="email">Email</label>
								<ng-form name="subForm3" ng-class="{ 'has-error' : subForm3.$invalid && !subForm3.$pristine }">
									<input type="email" class="form-control" data-ng-model="email" id="emailUtilisateur" placeholder="Email" data-ng-required="true">
									<div ng-show="subForm3.$dirty && subForm3.$invalid">
										<span class="help-block" ng-show="subForm3.$error.required"> Email obligatoire</span>
										<span class="help-block" ng-show="subForm3$error.email"> Email invalide</span>
									</div>
								</ng-form>
							</div>
						</div>
			
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
							<button type="submit" class="btn btn-primary" ng-disabled="paiementForm.$invalid">Payer</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- /Popup go paiement -->

		<!-- Popup paiement success -->
		<div class="modal fade" id="bs-paiementSuccess" tabindex="-1" role="dialog" aria-labelledby="paiementSuccess" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
						<h4 class="modal-title" id="myModalLabel">Merci</h4>
					</div>
					<div class="modal-body">
						Merci d'avoir procéder au paiement. Nous vous recontacterons rapidement.
					</div>
			
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /Popup Paiement Success -->
        <div class="modal fade" id="aDistanceModal" tabindex="-1" role="dialog" aria-labelledby="aDistanceModal" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
						<h4 class="modal-title" id="myModalLabel">Plus d'informations sur le coaching diététique</h4>
					</div>
					<div class="modal-body">
						<ol>
                            <li>Une fois votre paiement recu, vous recevrez une email confirmant la création de votre espace et vous invitant à cliquer sur un lien</li>
                            <li>Ce lien vous redirige vers votre espace personnel en vous proposant de créer votre mot de passe</li>
                            <li>Chaque jour, notez vos repas, vos émotions, vos sensations alimentaires</li>
                            <li>Une fois par semaine minimum, inscrivez votre poids pour créer votre courbe</li>
                            <li>Une fois par semaine, j'analyse et commente votre carnet alimentaire</li>
                            <li>Une fois par mois, nous ferons un point ensemble par téléphone (30 minutes)</li>
                        </ol>
					</div>
			
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					</div>
				</div>
			</div>
		</div>
