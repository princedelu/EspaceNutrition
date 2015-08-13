<!-- Popup poids -->
<div class="modal fade" id="bs-courbePoids" tabindex="-1" role="dialog" aria-labelledby="courbePoids" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
            <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Fermer</span></button>
					<h4 class="modal-title" id="myModalLabel" >La courbe de poids de {{ usermesure.email }}</h4>
				</div>
			<div class="modal-body" data-access-level='accessLevels.admin'>
				<div class="boxEspace box-primary">
                    <div class="box-body chart-responsive">
                        <d3-courbe-poids data="dataPoids"></d3-courbe-poids>
                    </div><!-- /.box-body -->
                </div>
			</div>
		</div>
	</div>
</div>
<!-- /Popup poids -->




