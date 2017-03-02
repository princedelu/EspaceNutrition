	<!-- Contact Section -->
        <section id="temoignage" class="success">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2>Témoignages</h2>
                        <hr class="star-light">
                    </div>
                </div>
				<div class="row">
                    <div class="col-md-8 col-md-offset-2">
						<div class="comment-list">
						  <!-- First Comment -->
						  <article class="row" ng-repeat="temoignage in temoignages">
							<div class="col-md-2 col-sm-2 col-xs-4">
							  <figure class="thumbnail">
								<img class="img-responsive comment-bookimg" src="/images/livredor.png" />
								<figcaption class="text-center"><b>{{temoignage.prenom}}</b><br/> <i>{{temoignage.age}} ans</i></figcaption>
							  </figure>
							</div>
							<div class="col-md-10 col-sm-10 col-xs-8">
							  <div class="panel panel-default arrow left">
								<div class="panel-body">
								  <div class="col-md-8 text-left">
									<div class="comment-user"><i class="fa fa-user"></i> <b><u>Objectif :</u> {{temoignage.objectif}}</b></div>
								  </div>
								  <div class="col-md-4 text-right">
									<time class="comment-date" datetime="16-12-2014 01:05"><i class="fa fa-clock-o"></i> {{temoignage.date}}</time>
								  </div>
								  <div class="col-md-12 comment-post">
									<p>
									  {{temoignage.temoignage}}
									</p>
								  </div>
								</div>
							  </div>
							</div>
						  </article>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2 col-md-offset-2">
						<button type="button" class="btn btn-default" ng-show="links.first.print" ng-click="first();"><i class="fa fa-fast-backward"></i> Les plus récents</button>
					</div>
					<div class="col-md-2">
						<button type="button" class="btn btn-default" ng-show="links.previous.print" ng-click="previous();"><i class="fa fa-step-backward"></i> Précédent</button>
					</div>
					<div class="col-md-2">
						<button type="button" class="btn btn-default" ng-show="links.next.print" ng-click="next();">Suivant <i class="fa fa-step-forward"></i></button>
					</div>
					<div class="col-md-2">
						<button type="button" class="btn btn-default" ng-show="links.last.print" ng-click="last();">Les plus anciens <i class="fa fa-fast-forward"></i></button>
					</div>
				</div>
				<br/>
				<div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
						<a class="btn btn-lg btn-block btn-primary" ng-click="affichePopupAddTemoignagne()">Ajouter un témoignage</a>
					</div>
				</div>
			</div>
		</section>
		
		<div class="modal fade" id="aTemoignageModal" tabindex="-1" role="dialog" aria-labelledby="aTemoignageModal" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form name="addTemoignageForm" ng-submit='addTemoignage()'>
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
							<h4 class="modal-title" id="myModalLabel">Ajouter un témoignage</h4>
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
						</div>
				
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
							<button type="submit" class="btn btn-primary" ng-disabled="addTemoignageForm.$invalid">Envoyer</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<div class="modal fade" id="aTemoignageSuccessModal" tabindex="-1" role="dialog" aria-labelledby="aTemoignageSuccessModal" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
						<h4 class="modal-title" id="myModalLabel">Prise en compte de votre témoignage</h4>
					</div>
					<div class="modal-body">
						Votre témoignage a été pris en compte par le site. Avant d'apparaitre sur le site, Angélique Guehl le validera.
					</div>
			
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
					</div>
				</div>
			</div>
		</div>