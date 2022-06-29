
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
                            <h3 class="kt-portlet__head-title">Gestion des dépots</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-outline-brand" data-toggle="modal" data-target=".bd-example-modal-xl">Nouveau Depot</button>
                                <br> <br>

                                <span class="text-muted">saisi ici le nom du depot, le code du dépot, le nom de l'agence</span>
                                <input type="search" placeholder="saisir ici..." name="rechercher" id="rechercher" class="form-control">
                                <span id="text"></span>
                            
                                <!-- Large Modal -->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Creer Un dépot</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <span id="message"></span>
                                            <form id="form_new_depot">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label">Agence (a cocher uniquement si on veux creer le depot d'une agence)</label>
                                                                <select name="agence" id="agence" class="form-control">
                                                                    
                                                                </select>
                                                                <span class="form-text text-danger" id="agence_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nom</label>
                                                                <input type="text" class="form-control" name="nom" id="nom">
                                                                <span class="form-text text-danger" id="nom_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label">Adresse</label>
                                                                <input type="text" class="form-control" name="adresse" id="adresse">
                                                                <span class="form-text text-danger" id="adresse_error"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Email</label>
                                                                <input type="text" class="form-control" name="email" id="email">
                                                                <span class="form-text text-danger" id="email_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">telephone</label>
                                                                <input type="text" class="form-control" name="telephone" id="telephone">
                                                                <span class="form-text text-danger" id="telephone_error"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-brand btn-outline-danger" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" id="new_depot_btn" class="btn btn-outline-brand">Enregistrer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                <!-- Modal detail d'un depot debut-->
                                <div class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true" id="details_modal">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Détails</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" id="details">
                                                
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-brand btn-outline-danger" data-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal detail d'un depot fin-->
                                

                                 <!-- edit modal d'un depot debut -->
                                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="edit_modal">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modifier Un dépot</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <span id="message1"></span>
                                            <form id="form_update_depot">
                                                <input type="hidden" name="matricule" id="matricule">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label">Agence (a cocher uniquement si on veux creer le depot d'une agence)</label>
                                                                <select name="agence1" id="agence1" class="form-control">
                                                                    
                                                                </select>
                                                                <span class="form-text text-danger" id="agence1_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nom</label>
                                                                <input type="text" class="form-control" name="nom1" id="nom1">
                                                                <span class="form-text text-danger" id="nom1_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-3">
                                                                <label class="form-label">Adresse</label>
                                                                <input type="text" class="form-control" name="adresse1" id="adresse1">
                                                                <span class="form-text text-danger" id="adresse1_error"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Email</label>
                                                                <input type="text" class="form-control" name="email1" id="email1">
                                                                <span class="form-text text-danger" id="email1_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">telephone</label>
                                                                <input type="text" class="form-control" name="telephone1" id="telephone1">
                                                                <span class="form-text text-danger" id="telephone1_error"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-brand btn-outline-danger" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" id="update_depot_btn" class="btn btn-outline-brand">Modifier</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- edit modal d'un depot fin -->






                            </div>
                        </div>


                        <hr class="text-primary">
                      
                        <!-- begin:: Content -->
                        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nom</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Adresse</th>
                                            <th scope="col">Telephone</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="depots">
                                        
                                    </tbody>
                                </table>
                                <div align="right" id="pagination_link"></div>
                            </div>	
                        </div>
                        <!-- end:: Content -->
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

    /**affiche la liste des agence pour faire le depot debut la focntion revoi le résultat d'une methode dans service*/
    depot(1);
    function depot(page){
        $.ajax({
            method: "GET",
            url: "<?php echo base_url('all_depot/');?>"+page,
            dataType: "JSON",
            success: function (data) {
                $("#depots").html(data.depots);
                $('#pagination_link').html(data.pagination_link);
            }
        });
    }
    
    /**gerer le click sur la pagination article*/
    $(document).on("click", ".pagination li a", function(event){
        event.preventDefault();
        var page = $(this).data("ci-pagination-page");
        depot(page);
    });
    /**selectionne tous les articles dans la base des données fin */


    /**affiche la liste des agence pour faire le depot fin la focntion revoi le résultat d'une methode dans service*/

    /**affiche la liste des agence pour faire le depot debut la focntion revoi le résultat d'une methode dans service*/
    agence();
    function agence(){
        $.ajax({
            method: "GET",
            url: "<?php echo base_url('agence');?>",
            dataType: "JSON",
            success: function (data) {
                $("#agence").html(data);
                $("#agence1").html(data);
            }
        });
    }
    /**affiche la liste des agence pour faire le depot fin la focntion revoi le résultat d'une methode dans service*/

    /**enregistre un nouveau depot dans la base des données debut */
    $('#form_new_depot').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            method: "POST",
            url: "<?php echo base_url('new_depot'); ?>",
            data: $(this).serialize(),
            dataType: "json",
            beforeSend:function(){
                $('#new_depot_btn').attr('disabled', 'disabled');
                $('#new_depot_btn').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                $('.fa-spin').addClass('active');
            },
            success: function(data){
                if(data.error){
                    $('#message').html('');

                    if(data.agence_error != ''){
                        $('#agence_error').html(data.agence_error);
                    }else{
                        $('#agence_error').html('');
                    }

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

                    if(data.email_error != ''){
                        $('#email_error').html(data.email_error);
                    }else{
                        $('#email_error').html('');
                    }

                    if(data.telephone_error != ''){
                        $('#telephone_error').html(data.telephone_error);
                    }else{
                        $('#telephone_error').html('');
                    }

                }

                if(data.success){
                    
                    $('#nom_error').html('');
                    $('#adresse_error').html('');
                    $('#email_error').html('');
                    $('#telephone_error').html('');
                    $('#agence_error').html('');

                    $('#form_new_depot')[0].reset();

                    $('#message').html(data.success);
                    depot(1);
                    
                }
                $('#new_depot_btn').attr('disabled', false);
                $('#new_depot_btn').html('Enrégistrer');
            }
        });
    });
    /**enregistre un nouveau depot dans la base des données fin*/

    /**voir les détails d'un depot debut */
    $(document).on('click', '.view', function(e){ 
        e.preventDefault();

        var mat_depot = $(this).attr('id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url('get_detail_d');?>",
            data: {mat_depot:mat_depot},
            dataType: "JSON",
            success: function (data) {
                $("#details").html(data);
                $("#details_modal").modal('show');  
            }
        });
    });
    /**voir les détails d'un depot fin */


    /**affiche les détails d'un depot pour modifier dans le formulaire de modification debut */
    $(document).on('click', '.edit', function(e){ 
        e.preventDefault();
        var mat_depot = $(this).attr('id');
        $.ajax({
            method: "POST",
            url: "<?php echo base_url('get_edit_depot');?>",
            data: {mat_depot:mat_depot},
            dataType: "JSON",
            success: function (data){
                $("#agence1").val(data.details.code_ag_d);
                $("#nom1").val(data.details.nom_depot);
                $("#adresse1").val(data.details.adresse_depot);
                $("#email1").val(data.details.email_depot);
                $("#telephone1").val(data.details.telephone_depot);
                $("#matricule").val(data.details.mat_depot);
                $("#edit_modal").modal('show');  
            }
        });
    });
    /**affiche les détails d'un depot pour modifier dans le formulaire de modification fin */


    /**modifier un depot debut */
    $('#form_update_depot').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            method: "POST",
            url: "<?php echo base_url('update_depot'); ?>",
            data: $(this).serialize(),
            dataType: "json",
            beforeSend:function(){
                $('#update_depot_btn').attr('disabled', 'disabled');
                $('#update_depot_btn').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                $('.fa-spin').addClass('active');
            },
            success: function(data){
                if(data.error){
                    $('#message1').html('');

                    if(data.agence1_error != ''){
                        $('#agence1_error').html(data.agence1_error);
                    }else{
                        $('#agence1_error').html('');
                    }

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

                    if(data.email1_error != ''){
                        $('#email1_error').html(data.email1_error);
                    }else{
                        $('#email1_error').html('');
                    }

                    if(data.telephone1_error != ''){
                        $('#telephone1_error').html(data.telephone1_error);
                    }else{
                        $('#telephone1_error').html('');
                    }

                }

                if(data.success){
                    $('#nom1_error').html('');
                    $('#adresse1_error').html('');
                    $('#email1_error').html('');
                    $('#telephone1_error').html('');
                    $('#agence1_error').html('');

                    $('#message1').html(data.success);
                    depot(1);
                }
                $('#update_depot_btn').attr('disabled', false);
                $('#update_depot_btn').html('Modifier');
            }
        });
    });
    /**modifier un depot fin */

    /**faire la rechercher debut */
    $("#rechercher").keyup(function (e) { 
        var recherche = $("#rechercher").val();
        if(recherche != ''){
            $("#text").html(recherche);
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('all_depot');?>",
                data: {recherche:recherche},
                dataType: "JSON",
                beforeSend:function(){
                    $('#depots').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                    $('.fa-spin').addClass('active');
                },
                success: function(data){
                    $("#depots").html(data.depots); 
                    $('.has-spinner').removeAttr("disabled");
                }
            });
        }else{
            depot(1);
        }
    });
    /**faire la rechercher fin */


</script>