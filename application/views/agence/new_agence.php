
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
                            <h3 class="kt-portlet__head-title">Gestion des agences</h3>
                            
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-outline-brand" id="btn_modal">Nouvel Agence</button>
                                <!-- debut Modal création d'une agence-->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true" id="new_agence_modal">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="name_heade">Creer Une Agence</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="agence_form">
                                                <div class="modal-body">
                                                    <div class="kt-portlet__body">
                                                        <!--debut message-->
                                                        <span id="message"></span>
                                                        <!--fin message-->

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Nom</label>
                                                                    <input type="text" class="form-control" id="nom" name="nom" placeholder="nom">
                                                                    <span class="form-text text-danger" id="nom_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Adresse</label>
                                                                    <input type="text" class="form-control" name="adresse" id="adresse" placeholder="adresse">
                                                                    <span class="form-text text-danger" id="adresse_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Activite</label>
                                                                    <input type="text" class="form-control" name="activite" id="activite" placeholder="que fait l'agence activité?">
                                                                    <span class="form-text text-danger" id="activite_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Pays</label>
                                                                    <select class="form-control" name="pays" id="pays">
                                                                        
                                                                    </select>
                                                                    <span class="form-text text-danger" id="pays_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Telephone 1</label>
                                                                    <input type="tel" class="form-control" name="tel1" id="tel1" placeholder="numero de telephone">
                                                                    <span class="form-text text-danger" id="tel1_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Telephone 2</label>
                                                                    <input type="tel" class="form-control" name="tel2" id="tel2" placeholder="numero de telephone">
                                                                    <span class="form-text text-danger" id="tel2_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Email</label>
                                                                    <input type="email" class="form-control" name="email" id="email" placeholder="adresse email">
                                                                    <span class="form-text text-danger" id="email_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Form-->
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" id="btn_save_agence" class="btn btn-outline-brand">Enregistrer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!--fin modal creation d'une agence-->

                                <!--debut modal modification d'une agence-->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="edit_agence_modal">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modifier Une Agence</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="edit_agence_form">
                                                <div class="modal-body">
                                                    <div class="kt-portlet__body">
                                                        <!--debut message-->
                                                        <span id="edit_message"></span>
                                                        <!--fin message-->
                                                            <input type="hidden" name="matricule" id="matricule">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Nom</label>
                                                                    <input type="text" class="form-control" id="nom1" name="nom1" placeholder="nom">
                                                                    <span class="form-text text-danger" id="nom1_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Adresse</label>
                                                                    <input type="text" class="form-control" name="adresse1" id="adresse1" placeholder="adresse">
                                                                    <span class="form-text text-danger" id="adresse1_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Activite</label>
                                                                    <input type="text" class="form-control" name="activite1" id="activite1" placeholder="que fait l'agence activité?">
                                                                    <span class="form-text text-danger" id="activite1_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Pays: </label>
                                                                    <select class="form-control" name="pays1" id="pays1">
                                                                    </select>
                                                                    <span class="form-text text-danger" id="pays1_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Telephone 1</label>
                                                                    <input type="tel" class="form-control" name="tele1" id="tele1" placeholder="numero de telephone">
                                                                    <span class="form-text text-danger" id="tele1_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Telephone 2</label>
                                                                    <input type="tel" class="form-control" name="tele2" id="tele2" placeholder="numero de telephone">
                                                                    <span class="form-text text-danger" id="tele2_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Email</label>
                                                                    <input type="email" class="form-control" name="email1" id="email1" placeholder="adresse email">
                                                                    <span class="form-text text-danger" id="email1_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Form-->
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" id="btn_edit_agence" class="btn btn-outline-brand">Modifier</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!--fin modal modification d'une agence-->





                            </div>
                        </div>


                        <hr class="text-primary">
                        <!--liste des agences-->
                        <div class="row" id="agence">
                            
                        </div>

                        <!-- debut modal pour afficher les details d'une agence -->
                        <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="show_agence_modal">
							<div class="modal-dialog modal-xl">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Details</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<span id="sigle_agence"></span>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
									</div>
								</div>
							</div>
						</div>
                        <!-- debut modal pour afficher les details d'une agence -->

                        
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


<script>
    $(document).ready(function(){

        /**affiche le modal*/
        $("#btn_modal").click(function (e) { 
            e.preventDefault();
            pays();
            $("#new_agence_modal").modal('show');
        });
        
        /**selectionne la liste des pays */
        pays();
        function pays(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('get_pays'); ?>",
                dataType: "json",
                success: function (data) {
                    $("#pays").html(data);
                    $("#pays1").html(data);
                }
            });
        }

        /**inscrer l'agence dans la base des donnéé */
        $('#agence_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('agence_new'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_save_agence').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
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

                        if(data.activite_error != ''){
                            $('#activite_error').html(data.activite_error);
                        }else{
                            $('#activite_error').html('');
                        }

                        if(data.pays_error != ''){
                            $('#pays_error').html(data.pays_error);
                        }else{
                            $('#pays_error').html('');
                        }

                        if(data.tel1_error != ''){
                            $('#tel1_error').html(data.tel1_error);
                        }else{
                            $('#tel1_error').html('');
                        }

                        if(data.tel2_error != ''){
                            $('#tel2_error').html(data.tel2_error);
                        }else{
                            $('#tel2_error').html('');
                        }

                        if(data.email_error != ''){
                            $('#email_error').html(data.email_error);
                        }else{
                            $('#email_error').html('');
                        }
                    }
                    if(data.success){
                        $('#nom_error').html('');
                        $('#adresse_error').html('');
                        $('#activite_error').html('');
                        $('#pays_error').html('');
                        $('#tel1_error').html('');
                        $('#tel2_error').html('');
                        $('#email_error').html('');
                        $('#agence_form')[0].reset();
                        $('#message').html(data.success);

                        /**affiche l'agence */
                        get_agence();
                    }
                    
                    $('#btn_save_agence').attr('disabled', false);

                }
            });
        });


        /**afficher les agences */
        get_agence();
        function get_agence(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('get_agence'); ?>",
                dataType: "json",
                success: function (data) {
                    $("#agence").html(data);
                }
            });
        }

        /**affiche les information sur une agence donnée */
        $(document).on('click', '.view_ag', function(){
            var mat_ag = $(this).attr("id");
            sigle_agence(mat_ag);
        });

        /**function qui selection une agence en particulier */
        function sigle_agence(mat_ag){
            $.ajax({
                method:"POST",
                url: "<?php echo base_url('get_single_agence'); ?>",
                data:{mat_ag:mat_ag},
                dataType: "json",
                success: function (data) {
                    $("#sigle_agence").html(data);
                    $("#show_agence_modal").modal('show');
                }
            });  
        }

        /**afficher les informations dans le formulaire avant modification */
        $(document).on('click', '.edit_ag', function(){
            var mat_ag = $(this).attr("id");
            $.ajax({
                method:"POST",
                url: "<?php echo base_url('get_single_agence1'); ?>",
                data:{mat_ag:mat_ag},
                dataType: "json",
                success: function(data){
                    $("#matricule").val(data.matricule_ag);
                    $("#nom1").val(data.nom_ag);
                    $('#adresse1').val(data.adress_ag);
                    $('#activite1').val(data.activite_ag);
                    $('#pays1').val(data.pays_ag);
                    $('#tele1').val(data.telephone1_ag);
                    $('#tele2').val(data.telephone2_ag);
                    $('#email1').val(data.email_ag);
                }
            }); 
            
            $("#edit_agence_modal").modal('show');
        });


        /**modifier les inforamations d'une agence */
        $('#edit_agence_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('update_agence'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_edit_agence').attr('disabled', 'disabled');
                },
                success: function (data) {
                    if(data.error){
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

                        if(data.activite1_error != ''){
                            $('#activite1_error').html(data.activite1_error);
                        }else{
                            $('#activite1_error').html('');
                        }

                        if(data.pays1_error != ''){
                            $('#pays1_error').html(data.pays1_error);
                        }else{
                            $('#pays1_error').html('');
                        }

                        if(data.tele1_error != ''){
                            $('#tele1_error').html(data.tele1_error);
                        }else{
                            $('#tele1_error').html('');
                        }

                        if(data.tele2_error != ''){
                            $('#tele2_error').html(data.tele2_error);
                        }else{
                            $('#tele2_error').html('');
                        }

                        if(data.email1_error != ''){
                            $('#email1_error').html(data.email1_error);
                        }else{
                            $('#email1_error').html('');
                        }
                    }
                    if(data.success){
                        $('#nom1_error').html('');
                        $('#adresse1_error').html('');
                        $('#activite1_error').html('');
                        $('#pays1_error').html('');
                        $('#tele1_error').html('');
                        $('#tele2_error').html('');
                        $('#email1_error').html('');
                        //$('#edit_agence_form')[0].reset();
                        $('#edit_message').html(data.success);

                        /**affiche l'agence */
                        get_agence();
                    }
                    
                    $('#btn_edit_agence').attr('disabled', false);

                }
            });
        });
        
    });
</script>