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
               <div class="row mar-bot40 align-center">
                        
                    <div class="col-md-9">
                        <h1 class="presentation">Angélique Guehl</h1>
                        <h2 class="presentation">Diététicienne Nutritionniste diplômée</h2>
                        Secret médical numéro adéli
                    </div>
                    <div class="col-md-3"><img src="images/zen-diet.jpg" alt="Zen diet" width="200px"/></div>		
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
									<slider name="sliderControl" floor="0" ceiling="100" ng-model-low="minValueSlider" ng-model-high="maxValueSlider"></slider>

									<div ng-show="subForm5.$dirty && subForm5.$invalid">
										<span class="help-block" ng-show="subForm5.$error"> Merci de sélectionner les valeurs {{initValueMinWait}} et {{initValueMaxWait}}</span>
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
                        <div class="footer-col col-md-6">
                            <h3>Adresse</h3>
                            <p>79230 Aiffres</p>
                        </div>
                        <div class="footer-col col-md-6">
                            <h3>Web</h3>
                            <ul class="list-inline">
                                <li>
                                    <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-facebook"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-below">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
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

        
