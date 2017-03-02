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
							<input type="hidden" data-ng-model="champControl" id='champControl' name="champControl">
							<span class="label label-success" ng-show="success"> {{success}} </span>
							<br/><br/>
							<button type="submit" class="btn btn-primary" ng-disabled="sendMessageForm.$invalid">Envoyer</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>