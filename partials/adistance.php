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
                    <div class="col-md-4">
						<div class="panel panel-success">
						    <div class="panel-heading">
						        <h4 class="text-center">Consultation en ligne</h4>
						    </div>
						    <div class="panel-body text-center">
						        <p class="lead">
						            <strong>50€ / consultation</strong>
                                    <br/>&nbsp;
						        </p>
						    </div>
						    <ul class="list-group list-group-flush text-center">
						        <li class="list-group-item">
						            Durée d'1 heure environ
						        </li>
						        <li class="list-group-item">
						            Sur rendez-vous, par téléphone ou webcam (Skype)
						        </li>
								<li class="list-group-item">
						            Même prestation qu'au cabinet
						        </li>
								<li class="list-group-item">
						            La consultation initiale permet de faire un bilan nutritionnel
						        </li>
                                <li class="list-group-item">
						            Les consultations de suivi permettent de ré-ajuster l'accompagnement, de répondre à vos questions
						        </li>
                                <li class="list-group-item">
						            Honaires flexibles selon vos disponibilités
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
						        <h4 class="text-center">Coaching diététique mensuel</h4>
						    </div>
						    <div class="panel-body text-center">
						        <p class="lead">
						            <strong>80€ / mois</strong><br/>
                                    <a href="#aDistanceModal2" data-toggle="modal">
                                        Plus d'informations </i>
                                    </a>
						        </p>
						    </div>
						    <ul class="list-group list-group-flush text-center">
                                <li class="list-group-item">
						            4 semaines d'accompagnement
						        </li>
                                <li class="list-group-item">
						            Espace personnel sur le site
						        </li>
						        <li class="list-group-item">
						            Carnet alimentaire analysé et commenté sous 48 h
						        </li>
						        <li class="list-group-item">
						            Suivi de votre courbe de poids
						        </li>
						        <li class="list-group-item">
						            Consultation téléphonique de 30 minutes pour faire le point ensemble et répondre à vos questions
						        </li>
						        <li class="list-group-item">
						            Coaching et motivation au quotidien
						        </li>
                                <li class="list-group-item">
						            Accompagnement nutritionnel et émotionnel
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
						        <h4 class="text-center">Forfait coaching diététique (3 mois)</h4>
						    </div>
						    <div class="panel-body text-center">
						        <p class="lead">
						            <strong>200€</strong><br/>
                                    <a href="#aDistanceModal3" data-toggle="modal">
                                        Plus d'informations </i>
                                    </a>
						        </p>
						    </div>
						    <ul class="list-group list-group-flush text-center">
						        <li class="list-group-item">
						            12 semaines d'accompagnement
						        </li>
                                <li class="list-group-item">
						            Espace personnel sur le site
						        </li>
						        <li class="list-group-item">
						            Carnet alimentaire analysé et commenté sous 48 h
						        </li>
						        <li class="list-group-item">
						            Suivi de votre courbe de poids
						        </li>
						        <li class="list-group-item">
						            Consultation téléphonique de 30 minutes pour faire le point ensemble et répondre à vos questions
						        </li>
						        <li class="list-group-item">
						            Coaching et motivation au quotidien
						        </li>
                                <li class="list-group-item">
						            Accompagnement nutritionnel et émotionnel
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
        <div class="modal fade" id="aDistanceModal2" tabindex="-1" role="dialog" aria-labelledby="aDistanceModal2" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
						<h4 class="modal-title" id="myModalLabel">Plus d'informations sur le coaching diététique mensuel</h4>
					</div>
					<div class="modal-body">
						Blabla
					</div>
			
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					</div>
				</div>
			</div>
		</div>
        <div class="modal fade" id="aDistanceModal3" tabindex="-1" role="dialog" aria-labelledby="aDistanceModal3" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
						<h4 class="modal-title" id="myModalLabel">Plus d'informations sur le forfait coaching diététique (3 mois)</h4>
					</div>
					<div class="modal-body">
						Blabla
					</div>
			
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					</div>
				</div>
			</div>
		</div>
