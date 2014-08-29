		<!-- Honoraires Section -->
        <section class="success" id="honoraires">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2>Honoraires</h2>
                        <hr class="star-light">
                    </div>
                </div>
                 <div class="row">
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
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
						<h4 class="modal-title" id="myModalLabel">Explication</h4>
					</div>
					<div class="modal-body">
						Blabla
					</div>
			
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
						<button class="btn btn-primary" ng-click="redirectionPaiement()">Payer</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /Popup utilisateur -->
