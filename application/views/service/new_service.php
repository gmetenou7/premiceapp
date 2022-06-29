
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
                            <h3 class="kt-portlet__head-title">Gestion Des Service</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-outline-brand" id="modal">Nouveau Service</button>
                            

                                <div class="form-group">
                                    <span class="form-text text-muted">
                                        <b>saisi le nom de l'agence pour voir la liste de ses services</b>
                                    </span>
                                    <input class="form-control" type="search" name="search" id="search" placeholder="saisi ici...">
                                    <span id="saisi"></span>
                                </div>



                                <!-- debut modal creation d'un service -->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal_service">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Creer Un Service</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="service_form">
                                                <div class="modal-body">
                                                    <div class="kt-portlet__body">
                                                        <!--debut messages-->
                                                        <span id="message"></span>
                                                        <!--fin messages-->

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Entreprise / Groupe</label>
                                                                    <select class="form-control" name="entreprise" id="entreprise">
                                                                        <option selected value="<?php echo session('users')['matricule']; ?>"><?php echo session('users')['nom']; ?></option>
                                                                    </select>
                                                                    <span class="form-text text-danger" id="entreprise_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Agence</label>
                                                                    <select class="form-control" name="agence" id="agence">
                                                                        
                                                                    </select>
                                                                    <span class="form-text text-danger" id="agence_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Nom</label>
                                                                    <input type="text" class="form-control" id="service" name="service" placeholder="nom du service">
                                                                    <span class="form-text text-danger" id="service_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" id="btn_new_service" class="btn btn-outline-brand">Enrégistrer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!--fin creation d'un service-->

                                <!--debut affiche les informations d'une agence particulière-->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="show_service_modal">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Details</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <span id="sigle_service"></span>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--fin affiche les informations d'une agence particulière-->



                                <!-- debut modal modification d'un service -->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="edit_modal_service">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modifier Un Service</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="update_service_form">
                                                <div class="modal-body">
                                                    <div class="kt-portlet__body">
                                                        <!--debut messages-->
                                                        <span id="message_update"></span>
                                                        <!--fin messages-->
                                                        <input type="hidden" name="matricule" id="matricule">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Entreprise / Groupe</label>
                                                                    <select class="form-control" name="entreprise1" id="entreprise1">
                                                                        <option selected value="<?php echo session('users')['matricule']; ?>"><?php echo session('users')['nom']; ?></option>
                                                                    </select>
                                                                    <span class="form-text text-danger" id="entreprise_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Agence: </label>
                                                                    <select class="form-control" name="agence1" id="agence1">
                                                                        
                                                                    </select>
                                                                    <span class="form-text text-danger" id="agence1_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Nom</label>
                                                                    <input type="text" class="form-control" id="service1" name="service1" placeholder="nom du service">
                                                                    <span class="form-text text-danger" id="service1_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" id="btn_update_service" class="btn btn-outline-brand">Modifier</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!--fin modification d'un service-->


                            </div>
                        </div>


                        <hr class="text-primary">
                        <div class="row" id="services">
                            
                        </div>


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
    $(document).ready(function () {

        /**selectionne les agence des l'entreprise encours */
        get_agence();
        function get_agence(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('agence'); ?>",
                dataType: "json",
                success: function (data) {
                    $("#agence").html(data);
                    $("#agence1").html(data);
                }
            });
        }

        /**affiche le fmodal formulaire */
        $(document).on('click', '#modal', function(){
            get_agence();
            $("#modal_service").modal('show')
        });

        /**creer un nouveau service */
        $('#service_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('service_new'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_new_service').attr('disabled', 'disabled');
                },
                success: function (data) {
                    if(data.error){
                        if(data.entreprise_error != ''){
                            $('#entreprise_error').html(data.entreprise_error);
                        }else{
                            $('#entreprise_error').html('');
                        }

                        if(data.agence_error != ''){
                            $('#agence_error').html(data.agence_error);
                        }else{
                            $('#agence_error').html('');
                        }

                        if(data.service_error != ''){
                            $('#service_error').html(data.service_error);
                        }else{
                            $('#service_error').html('');
                        }
                    }
                    if(data.success){
                        $('#entreprise_error').html('');
                        $('#agence_error').html('');
                        $('#service_error').html('');
                        $('#service_form')[0].reset();
                        $('#message').html(data.success);
                        services();
                    }
                   $('#btn_new_service').attr('disabled', false);
                }
            });
        });

        /**affiche la liste des services */
        services();
        function services(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('get_service'); ?>",
                dataType: "JSON",
                success: function (data){
                    $("#services").html(data);
                }
            });
        }

        /**afficher les details d'un service */
        $(document).on('click', '.view_service', function(){
            var mat_serv = $(this).attr("id");
            $.ajax({
                method:"POST",
                url: "<?php echo base_url('get_single_service'); ?>",
                data:{mat_serv:mat_serv},
                dataType: "json",
                success: function (data){
                    $("#sigle_service").html(data);
                    $("#show_service_modal").modal('show');
                }
            });
        });

        /**affiche le modal formulaire avec les informations pour modifier un service*/
        $(document).on('click', '.edit_service', function(){
            var mat_service = $(this).attr("id");
            $.ajax({
                method:"POST",
                url: "<?php echo base_url('get_single_service1'); ?>",
                data:{mat_service:mat_service},
                dataType: "json",
                success: function(data){
                    $("#matricule").val(data.matricule_serv);
                    $("#service1").val(data.nom_serv);
                    $("#agence1").val(data.mat_ag);
                }
            }); 
            //get_agence();
            $("#edit_modal_service").modal('show');
        });

        /**update un service dans la base des données */
        $('#update_service_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('service_update'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_update_service').attr('disabled', 'disabled');
                },
                success: function (data) {
                    if(data.error){
                        if(data.entreprise1_error != ''){
                            $('#entreprise1_error').html(data.entreprise1_error);
                        }else{
                            $('#entreprise1_error').html('');
                        }

                        if(data.agence1_error != ''){
                            $('#agence1_error').html(data.agence1_error);
                        }else{
                            $('#agence1_error').html('');
                        }

                        if(data.service_error != ''){
                            $('#service1_error').html(data.service1_error);
                        }else{
                            $('#service1_error').html('');
                        }
                    }
                    if(data.success){
                        $('#entreprise1_error').html('');
                        $('#agence1_error').html('');
                        $('#service1_error').html('');
                        //$('#update_service_form')[0].reset();
                        $('#message_update').html(data.success);
                        services();
                    }
                   $('#btn_update_service').attr('disabled', false);
                }
            });
        });



        /** affiche la liste des service par rapport a une agence*/
        $("#search").keyup(function (e) { 
            var input = $("#search").val();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('get_service'); ?>",
                data: {input:input},
                dataType: "JSON",
                success: function (data){
                    $("#saisi").html(input);
                    $("#services").html(data);
                }
            });
        });


        




    });
</script>