<!-- Navigation -->
        <nav class="navbar navbar-default navbar-fixed-top navbar-shrink">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header page-scroll">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#page-top">Espace nutrition</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="page-scroll">
                            <a href="#page-top">Accueil</a>
                        </li>
                        <li class="page-scroll">
                            <a href="#prestations">Prestations</a>
                        </li>
                        <li class="page-scroll">
                            <a href="#adistance">A distance</a>
                        </li>
                        <li class="page-scroll">
                            <a href="/login">Votre espace</a>
                        </li>
                        <li class="page-scroll">
                            <a href="#contact">Contact</a>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>

        <!-- Header -->
        <header>
            <div class="container">
	            <div class="row">
		            <div class="col-sm-12">
                	 
                        <h3>Angélique Guehl</h3>
                        <h2>Diététicienne Nutritionniste diplômée</h2>
                        <h3>Consultation sur rendez-vous</h3>
                        <h4>Bilan nutritionnel et suivi diététique</h4>
                        <h4>Analyse corporelle par bio-impédancemétrie</h4>
                        <div class="col-md-8 col-md-offset-2">
                            <div class="col-sm-6" align="left">
                                <p><ul class="list-group">
                                    <li class="list-group-item">Perte de poids</li>
                                    <li class="list-group-item">Rééquilibrage alimentaire</li>
                                    <li class="list-group-item">Grossesse</li>
                                    <li class="list-group-item">Sportifs</li>
                                    <li class="list-group-item">Végétarisme</li>
                                    <li class="list-group-item">Intolérance au gluten/lactose</li>
                                    <li class="list-group-item">Sevrage tabagique et alcoolique</li></ul>
                                </p>
                            </div>
                            <div class="col-sm-6" align="left">
                                <p><ul class="list-group">
                                    <li class="list-group-item">Diabète</li>
                                    <li class="list-group-item">Obésité enfant et adulte</li>
                                    <li class="list-group-item">Allergies alimentaires</li>
                                    <li class="list-group-item">Maladies cardio-vasculaires</li>
                                    <li class="list-group-item">Dénutrition (oncologie, personnes agées...)</li>
                                    <li class="list-group-item">Préparation à la chirurgie bariatrique</li>
                                    <li class="list-group-item">Troubles du comportement alimentaire</li></ul>
                                </p>
                            </div>
                        </div>                   
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="box">							
                            <div class="icon">
                                <div class="image"><i class="fa fa-map-marker"></i></div>
                                <div class="infoheader">
                                    <h3 class="title"><br/><strong><a class="public" href="https://www.google.fr/maps/place/51+Rue+Saint-Jean,+79000+Niort/@46.3235346,-0.4633182,17z/data=!3m1!4b1!4m2!3m1!1s0x4807302d3112d579:0xd051e97ec720f68c" target="_blank">51 rue Saint Jean 79000 Niort</a></strong></h3>
                                </div>
                            </div>
                            <div class="space"></div>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="box">							
                            <div class="icon">
								<div class="image"><i class="fa fa-mobile"></i></div>
                                <div class="infoheader">
                                    <h3 class="title"><br/><strong>06 68 00 79 15</strong></h3>
                                </div>
                            </div>
                            <div class="space"></div>
                        </div> 
                    </div>
                        
                    <div class="col-md-4">
                        <div class="box">							
                            <div class="icon">
                                <div class="image"><i class="fa fa-envelope"></i></div>
                                <div class="infoheader">
                                    <h3 class="title"><br/><strong><a class="public" href="mailto:contact@espace-nutrition.fr">contact@espace-nutrition.fr</a></strong></h3>
                                    
                                </div>
                            </div>
                            <div class="space"></div>
                        </div> 
                    </div>
                </div>            
            </div>
        </header>

    	<?php
			include("prestations.php");
			include("adistance.php");
		?>        

        <!-- Contact Section -->
        <section id="contact">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2>Contact</h2>
                        <hr class="star-primary">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <form name="sendMessageForm" ng-submit='sendMessage()'>
							<div class="form-group">
								<label for="nom" class="labelMandatory">Nom</label>
								<ng-form name="subForm1" ng-class="{ 'has-error' : subForm1.$invalid && !subForm1.$pristine }">
									<input type="text" class="form-control" data-ng-model="nom" id="nom" placeholder="Nom" data-ng-required="true">
									<div ng-show="subForm1.$dirty && subForm1.$invalid">
										<span class="help-block" ng-show="subForm1.$error.required"> Nom obligatoire</span>
									</div>
								</ng-form>
							</div>
                            <div class="form-group">
								<label for="email" class="labelMandatory">Email</label>
								<ng-form name="subForm2" ng-class="{ 'has-error' : subForm2.$invalid && !subForm2.$pristine }">
									<input type="email" class="form-control" data-ng-model="email" id="emailUtilisateur" placeholder="Email" data-ng-required="true">
									<div ng-show="subForm2.$dirty && subForm2.$invalid">
										<span class="help-block" ng-show="subForm2.$error.required"> Email obligatoire</span>
										<span class="help-block" ng-show="subForm2.$error.email"> Email invalide</span>
									</div>
								</ng-form>
							</div>
                            <div class="form-group">
								<label for="nom">Numéro de téléphone</label>
								<ng-form name="subForm3" ng-class="{ 'has-error' : subForm3.$invalid && !subForm3.$pristine }">
									<input type="text" class="form-control" data-ng-model="telephone" id="telephone" placeholder="Numéro de téléphone">
								</ng-form>
							</div>
							<div class="form-group">
								<label for="message" class="labelMandatory">Message</label>
								<ng-form name="subForm4" ng-class="{ 'has-error' : subForm4.$invalid && !subForm4.$pristine }">
									<textarea rows="8" class="form-control" data-ng-model="message" id="message" placeholder="Message" data-ng-required="true">
</textarea>
									<div ng-show="subForm4.$dirty && subForm4.$invalid">
										<span class="help-block" ng-show="subForm4.$error.required"> Message obligatoire</span>
									</div>
								</ng-form>
							</div>
							<div class="form-group">
								<label for="controle" class="labelMandatory">Champ de contrôle anti spam</label>
								<ng-form name="subForm5" ng-class="{ 'has-error' : subForm5.$invalid && !subForm5.$pristine }">
									
									<div name="sliderControl" slider class="slider" min="0" max="100" step="1">
									  <span></span>
									</div>

									<div ng-show="subForm5.$dirty && subForm5.$invalid">
										<span class="help-block" ng-show="subForm5.$error"> La valeur actuelle est : {{sliderValue}}<br/>Merci de sélectionner la valeur {{initValueWait}} </span>
									</div>
								</ng-form>
							</div>
							<input type="hidden" data-ng-model="champControl" id='champControl' name="champControl">
							<span class="label label-success" ng-show="success"> {{success}} </span>
							<br/><br/>
							<button type="submit" class="btn btn-primary" ng-disabled="sendMessageForm.$invalid">Envoyer</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="text-center">
            <div class="footer-above">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="box">							
                                <div class="icon">
                                    <div class="image"><i class="fa fa-map-marker"></i></div>
                                    <div class="infoheader">
                                        <h3 class="title"><br/><strong><a class="public" href="https://www.google.fr/maps/place/51+Rue+Saint-Jean,+79000+Niort/@46.3235346,-0.4633182,17z/data=!3m1!4b1!4m2!3m1!1s0x4807302d3112d579:0xd051e97ec720f68c" target="_blank">51 rue Saint Jean 79000 Niort</a></strong></h3>
                                    </div>
                                </div>
                                <div class="space"></div>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="box">							
                                <div class="icon">
								    <div class="image"><i class="fa fa-mobile"></i></div>
                                    <div class="infoheader">
                                        <h3 class="title"><br/><strong>06 68 00 79 15</strong></h3>
                                    </div>
                                </div>
                                <div class="space"></div>
                            </div> 
                        </div>
                            
                        <div class="col-md-4">
                            <div class="box">							
                                <div class="icon">
                                    <div class="image"><i class="fa fa-envelope"></i></div>
                                    <div class="infoheader">
                                        <h3 class="title"><br/><strong><a class="public" href="mailto:contact@espace-nutrition.fr">contact@espace-nutrition.fr</a></strong></h3>
                                        
                                    </div>
                                </div>
                                <div class="space"></div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-below">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">
                            Numéro adéli : 79 95 0063 2
                        </div>
                        <div class="col-lg-4">
                            Numéro de SIRET : 804 655 371 00016
                        </div>
                        <div class="col-lg-4">
                            Copyright &copy; http://www.espace-nutrition.fr 2014
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
        <div class="scroll-top page-scroll visible-xs visble-sm">
            <a class="btn btn-primary" href="#page-top">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
        
        

        
