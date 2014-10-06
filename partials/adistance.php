		<!-- adistance Section -->
        <section class="success" id="adistance">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2>A distance</h2>
                        <hr class="star-light">
                        <h3>En cours de construction</h3>
                    </div>
                </div>
                 <div class="row ng-hide" >
                    <div class="col-md-4">
						<div class="panel panel-success">
						    <div class="panel-heading">
						        <h4 class="text-center">Consultation en ligne</h4>
						    </div>
						    <div class="panel-body text-center">
						        <p class="lead">
						            <strong>50€ / consultation</strong>
						        </p>
						    </div>
						    <ul class="list-group list-group-flush text-center">
						        <li class="list-group-item">
						            Prise en charge personnalisée
						            <span class="fa fa-check pull-right"></span>
						        </li>
						        <li class="list-group-item">
						            Rendez-vous par skype
						            <span class="fa fa-check pull-right"></span>
						        </li>
								<li class="list-group-item">
						            Suivi personnalisé à chaque rendez-vous
						            <span class="fa fa-check pull-right"></span>
						        </li>
								<li class="list-group-item">
						            Espace personnel sur le site
						            <span class="fa fa-times pull-right"></span>
						        </li>
						    </ul>
						    <div class="panel-footer">
						        <a class="btn btn-lg btn-block btn-success" ng-click="affichePopupPaiement(1)">Commander via paypal</a>
						    </div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="panel panel-info">
						    <div class="panel-heading">
						        <h4 class="text-center">Suivi en ligne</h4>
						    </div>
						    <div class="panel-body text-center">
						        <p class="lead">
						            <strong>80€ / mois</strong>
						        </p>
						    </div>
						    <ul class="list-group list-group-flush text-center">
						        <li class="list-group-item">
						            Espace personnel sur le site
						            <span class="fa fa-check pull-right"></span>
						        </li>
						        <li class="list-group-item">
						            Suivi et retours tous les 2 jours
						            <span class="fa fa-check pull-right"></span>
						        </li>
						        <li class="list-group-item">
						            Suivi des repas, poids, humeur
						            <span class="fa fa-check pull-right"></span>
						        </li>
						        <li class="list-group-item">
						            Sollicitation téléphonique
						            <span class="fa fa-times pull-right"></span>
						        </li>
						    </ul>
						    <div class="panel-footer">
						        <a class="btn btn-lg btn-block btn-info" ng-click="affichePopupPaiement(2)">Commander via paypal</a>
						    </div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="panel panel-primary">
						    <div class="panel-heading">
						        <h4 class="text-center">Consultation + suivi en ligne</h4>
						    </div>
						    <div class="panel-body text-center">
						        <p class="lead">
						            <strong>100€ / mois</strong>
						        </p>
						    </div>
						    <ul class="list-group list-group-flush text-center">
						        <li class="list-group-item">
						            Prise en charge personnalisée
						            <span class="fa fa-check pull-right"></span>
						        </li>
						        <li class="list-group-item">
						            1 consultation en ligne par mois
						            <span class="fa fa-check pull-right"></span>
						        </li>
						        <li class="list-group-item">
						            Espace personnel sur le site
						            <span class="fa fa-check pull-right"></span>
						        </li>
						        <li class="list-group-item">
						            Suivi et retours tous les 2 jours
						            <span class="fa fa-check pull-right"></span>
						        </li>
						    </ul>
						    <div class="panel-footer">
						        <a class="btn btn-lg btn-block btn-primary" ng-click="affichePopupPaiement(3)">Commander via paypal</a>
						    </div>
						</div>
					</div>
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
