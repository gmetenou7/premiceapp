
</div>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    
    <?php $this->load->view('parts/subheader');?>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

            <!-- begin:: Content -->
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <!--begin::Row-->
        <div class="row">			
	        <div class="col-xl-12">
		        <!--begin::Portlet-->
		        <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Gérer les fournisseurs</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-outline-brand" data-toggle="modal" data-target=".bd-example-modal-xl">Nouveau Fournisseur</button>
                            
                                <!-- Large Modal nouveau fournisseur debut-->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Creer Un Fournisseur</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <!--begin::Form-->
                                            <form class="kt-form" id="fournisseur_form">
                                                <div class="modal-body">
                                                    <div class="kt-portlet__body">
                                                        <div class="form-group form-group-last">
                                                            <div class="alert alert-secondary" role="alert">
                                                                <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                                                                <div class="alert-text">
                                                                    tous les champs avec <span class="text-danger">*</span> sont obligatoire
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <span id="message"></span>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Nom<span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" id="nom" name="nom" placeholder="nom">
                                                                    <span class="form-text text-danger" id="nom_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Adresse</label>
                                                                    <input type="text" class="form-control" id="adresse" name="adresse" placeholder="adresse">
                                                                    <span class="form-text text-danger" id="adresse_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Email</label>
                                                                    <input type="email" class="form-control" name="email" id="email" placeholder="email">
                                                                    <span class="form-text text-danger" id="email_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Site internet</label>
                                                                    <input type="url" class="form-control" name="site" id="site" placeholder="site internet">
                                                                    <span class="form-text text-danger" id="site_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Telephone 1<span class="text-danger">*</span></label>
                                                                    <input type="tel" class="form-control" name="telephone1" id="telephone1" placeholder="telephone">
                                                                    <span class="form-text text-danger" id="telephone1_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Telephone 2</label>
                                                                    <input type="tel" class="form-control" name="telephone2" id="telephone2" placeholder="telephone">
                                                                    <span class="form-text text-danger" id="telephone2_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" id="btn_new_fournisseur" class="btn btn-outline-primary">Créer</button>
                                                </div>
                                            </form>
                                            <!--end::Form-->
                                        </div>
                                    </div>
                                </div>
                                 <!-- Large Modal nouveau fournisseur fin-->
                            </div>
                        </div>


                        <hr class="text-primary">
                        <div class="row" id="list_fournisseur">
                            <!--liste des fournisseurs-->
                        </div>

                        <!-- Large Modal detail d'une fournisseur debut-->
						<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="details_model">
							<div class="modal-dialog modal-xl">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Détails</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<span id="detail_s"></span>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
									</div>
								</div>
							</div>
						</div>
                         <!-- Large Modal detail d'un fournisseur fin-->



                        <!-- Large Modal edite fournisseur debut-->
                        <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="edit_model">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modifier Un Fournisseur</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <!--begin::Form-->
                                    <form class="kt-form" id="fournisseur_edit_form">
                                        <div class="modal-body">
                                            <div class="kt-portlet__body">
                                                <div class="form-group form-group-last">
                                                    <div class="alert alert-secondary" role="alert">
                                                        <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                                                        <div class="alert-text">
                                                            <span id="tests" class="text-danger"></span>
                                                            tous les champs avec <span class="text-danger">*</span> sont obligatoire
                                                        </div>
                                                    </div>
                                                </div>

                                                <span id="message_edite"></span>
                                                <input type="hidden" name="matricule" id="matricule" class="form-control">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nom<span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" id="nom1" name="nom1" placeholder="nom">
                                                            <span class="form-text text-danger" id="nom1_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Adresse</label>
                                                            <input type="text" class="form-control" id="adresse1" name="adresse1" placeholder="adresse">
                                                            <span class="form-text text-danger" id="adresse1_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input type="email" class="form-control" name="email1" id="email1" placeholder="email">
                                                            <span class="form-text text-danger" id="email1_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Site internet</label>
                                                            <input type="url" class="form-control" name="site1" id="site1" placeholder="site internet">
                                                            <span class="form-text text-danger" id="site1_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Telephone 1<span class="text-danger">*</span></label>
                                                            <input type="tel" class="form-control" name="telephone11" id="telephone11" placeholder="telephone">
                                                            <span class="form-text text-danger" id="telephone11_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Telephone 2</label>
                                                            <input type="tel" class="form-control" name="telephone21" id="telephone21" placeholder="telephone">
                                                            <span class="form-text text-danger" id="telephone21_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                                            <button type="submit" id="btn_edit_fournisseur" class="btn btn-outline-primary">Modifier</button>
                                        </div>
                                    </form>
                                    <!--end::Form-->
                                </div>
                            </div>
                        </div>
                        <!-- Large Modal edit fournisseur fin-->



			        </div>
		        </div>
		        <!--end::Portlet-->
	        </div>
        </div>	
    </div>
<!-- end:: Content -->				
            <!-- end:: Content -->
        </div>

    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->

<script type="text/javascript">
        /*nouveau fournisseur*/
        $('#fournisseur_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('new_fournisseur'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_new_fournisseur').attr('disabled', 'disabled');
                    $('#btn_new_fournisseur').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Encours...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data) {
                    if(data.error){
                        $('#message').html('');
                        if(data.nom_error != ''){
                            $('#nom_error').html(data.nom_error);
                        }else{
                            $('#nom_error').html('');
                        }

                        if(data.adresse_error != ''){
                            $('#adresse_error').html(data.adresse_error);
                        }else{
                            $('#adresse_error').html('');
                        }

                        if(data.telephone1_error!= ''){
                            $('#telephone1_error').html(data.telephone1_error);
                        }else{
                            $('#telephone1_error').html('');
                        }

                        if(data.telephone2_error!= ''){
                            $('#telephone2_error').html(data.telephone2_error);
                        }else{
                            $('#telephone2_error').html('');
                        }

                        if(data.email_error!= ''){
                            $('#email_error').html(data.email_error);
                        }else{
                            $('#email_error').html('');
                        }

                        if(data.site_error!= ''){
                            $('#site_error').html(data.site_error);
                        }else{
                            $('#site_error').html('');
                        }
                        
                    }
                    if(data.success){
                        $('#nom_error').html('');
                        $('#adresse_error').html('');
                        $('#telephone1_error').html('');
                        $('#telephone2_error').html('');
                        $('#email_error').html('');
                        $('#site_error').html('');
                        $('#fournisseur_form')[0].reset();

                        all_fournisseur();

                        $('#message').html(data.success);
                    }
                    $('#btn_new_fournisseur').attr('disabled', false);
                    $('#btn_new_fournisseur').html('Créer');
                }
            });
        });

        /**affiche la liste des fournisseurs*/
        all_fournisseur();
        function all_fournisseur(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('get_all_fournisseur'); ?>",
                dataType: "json",
                beforeSend:function(){
                    $('#list_fournisseur').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data) {
                    $('#list_fournisseur').html(data);
                    $('.has-spinner').removeAttr("disabled");
                }
            });   
        }
        
        /**affiche les informations d'un fournisseur*/
        $(document).on('click', '.view', function () {
            var matricule = $(this).attr('id');
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('get_details'); ?>",
                data: {matricule:matricule},
                dataType: "JSON",
                success:function(data){
                    $("#detail_s").html(data);
                }
            });
            $('#details_model').modal('show');
        });
        
        /**modifier les informations d'un fournisseur */
        $(document).on('click', '.edite', function (){
            var matricule = $(this).attr('id');
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('get_detail'); ?>",
                data: {matricule:matricule},
                dataType: "JSON",
                success:function(data){
                    $("#tests").html(data.test);
                    $("#matricule").val(data.details.matricule_four);
                    $("#nom1").val(data.details.nom_four);
                    $("#adresse1").val(data.details.adresse_four);
                    $("#email1").val(data.details.email_four);
                    $("#telephone11").val(data.telephone1);
                    $("#telephone21").val(data.telephone2);
                    $("#site1").val(data.details.site_interne_four);
                }
            });
            $('#edit_model').modal('show');
        });
        
        /**update le fournisseur */
        $('#fournisseur_edit_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('update_fournisseur'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_edit_fournisseur').attr('disabled', 'disabled');
                    $('#btn_edit_fournisseur').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Encours...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data) {
                    if(data.error){
                        $('#message_edite').html('');
                        if(data.nom1_error != ''){
                            $('#nom1_error').html(data.nom1_error);
                        }else{
                            $('#nom1_error').html('');
                        }

                        if(data.adresse1_error != ''){
                            $('#adresse1_error').html(data.adresse1_error);
                        }else{
                            $('#adresse1_error').html('');
                        }

                        if(data.telephone11_error!= ''){
                            $('#telephone11_error').html(data.telephone11_error);
                        }else{
                            $('#telephone11_error').html('');
                        }

                        if(data.telephone21_error!= ''){
                            $('#telephone21_error').html(data.telephone21_error);
                        }else{
                            $('#telephone21_error').html('');
                        }

                        if(data.email1_error!= ''){
                            $('#email1_error').html(data.email1_error);
                        }else{
                            $('#email1_error').html('');
                        }

                        if(data.site1_error!= ''){
                            $('#site1_error').html(data.site1_error);
                        }else{
                            $('#site1_error').html('');
                        }
                        
                    }
                    if(data.success){
                        $('#nom1_error').html('');
                        $('#adresse1_error').html('');
                        $('#telephone11_error').html('');
                        $('#telephone21_error').html('');
                        $('#email1_error').html('');
                        $('#site_error1').html('');
                        $('#fournisseur_form')[0].reset();

                        all_fournisseur();

                        $('#message_edite').html(data.success);
                    }
                   $('#btn_edit_fournisseur').attr('disabled', false);
                   $('#btn_edit_fournisseur').html('Modifier');
                }
            });
        });


</script>