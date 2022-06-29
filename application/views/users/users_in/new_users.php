
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
                            <h3 class="kt-portlet__head-title">Gestion Des Utilisateurs</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions">
                                        <button type="button" class="btn btn-outline-brand" id="btn_modal">Nouvel Utilisateur</button>
                                        <button type="button" class="btn btn-outline-success" id="filtermodal">FILTRER</button>
                                    </div>
                                </div>
                                <hr>
                                <!-- 
                                    barre de recherche permettant d'entrer juste le nom de 
                                    l'agence ou du service pour connaitre quel employé est dans 
                                    quel agence ou service
                                 -->
                                 <div class="form-group">
                                    <span class="form-text text-muted">
                                        <b>chercher avec le nom de l'employé</b>
                                    </span>
                                    <input class="form-control" type="search" name="search" id="search" placeholder="saisi ici...">
                                    <span id="saisi"></span>
                                </div>

                                <!-- Large Modal -->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="new_user_modal">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Creer Un Utilisateur</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="user_form">
                                                <div class="modal-body">
                                                    <!--debut message-->
                                                        <span id="message"></span>
                                                    <!--fin message-->
                                                    <div class="kt-portlet__body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Entreprise / Groupe</label>
                                                                    <select class="form-control" name="entreprise" id="entreprise">
                                                                        <option selected value="<?php echo !empty(session('users')['matricule'])?session('users')['matricule']:''; ?>"><?php echo !empty(session('users')['nom'])?session('users')['nom']:''; ?></option>
                                                                    </select>
                                                                    <span class="form-text text-danger" id="entreprise_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Agence </label>
                                                                        <select class="form-control" name="agence" id="agence">
                                                                        
                                                                        </select>
                                                                    <span class="form-text text-danger" id="agence_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Service</label>
                                                                    <select class="form-control" name="service" id="service">
                                                                        
                                                                    </select>
                                                                    <span class="form-text text-danger" id="service_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Nom<span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="nom" id="nom" placeholder="nom de l'employé">
                                                                    <span class="form-text text-danger" id="nom_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Adresse</label>
                                                                    <input type="text" class="form-control" name="adresse" id="adresse" placeholder="adresse">
                                                                    <span class="form-text text-muted">comment vous retrouvez au besion?</span>
                                                                    <span class="form-text text-danger" id="adresse_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Email</label>
                                                                    <input type="email" class="form-control" name="email" id="email" placeholder="email">
                                                                    <span class="form-text text-muted">nous assurons la protection de votre email</span>
                                                                    <span class="form-text text-danger" id="email_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Numero de telephone 1<span class="text-danger">*</span></label>
                                                                    <input type="tel" class="form-control" name="telephone1" id="telephone1" placeholder="telephone">
                                                                    <span class="form-text text-danger" id="telephone1_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Numero de telephone 2</label>
                                                                    <input type="tel" class="form-control" name="telephone2" id="telephone2" placeholder="telephone">
                                                                    <span class="form-text text-danger" id="telephone2_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Date de naissance</label>
                                                                    <input type="date" class="form-control" name="date_naiss" id="date_naiss" placeholder="date de naissance">
                                                                    <span class="form-text text-muted">une date correcte nous permet de partager ce merveilleux jour avec vous</span>
                                                                    <span class="form-text text-danger" id="date_naiss_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Fonction<span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="fonction" id="fonction" placeholder="fonction">
                                                                    <span class="form-text text-muted">quel est votre rôle dans l'entreprise?</span>
                                                                    <span class="form-text text-danger" id="fonction_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Mot de passe provisioir <b><span id="pass"></span></b> <span class="text-danger">*</span></label>
                                                                    <input type="hidden" class="form-control" name="password" id="password" placeholder="fonction">
                                                                    <span class="form-text text-muted">le mot de passe provisoir est celui généré par le système en attendant que l'utilisateur lui même le modifie?</span>
                                                                    <span class="form-text text-danger" id="password_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                vous devez absolument remplir les champs ayant <span class="text-danger">*</span>
                                                            </div>
                                                        </div>
                                                    </div>	
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" id="btn_save_user" class="btn btn-outline-brand">Enregistrer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                <!-- Large Modal voir les details d'un employé-->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="detail_modal">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Détails</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <span id="detail"></span>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Large Modal changer le service et ou l'agence d'un employé-->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="mute_modal">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    Modifier l'agence et ou le service de: 
                                                    <b><span id="nom11"></span></b>
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="form_mute">
                                                <div class="modal-body">
                                                    <span id="message_mute"></span>
                                                    <div class="row">
                                                        <input type="hidden" class="form-control" name="matricule11" id="matricule11">
                                                        <span class="form-text text-danger" id="matricule11_error"></span>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Agence 
                                                                </label>
                                                                    <select class="form-control" name="agence1" id="agence1">
                                                                    
                                                                    </select>
                                                                    <span class="form-text text-danger" id="agence1_error"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Service
                                                                </label>
                                                                <select class="form-control" name="service1" id="service1">
                                                                    
                                                                </select>
                                                                <span class="form-text text-danger" id="service1_error"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" id="btn_mute" class="btn btn-outline-brand">Modifier</button>
                                                </div>
                                            </form>
                                            <hr>
                                            <div class="alert alert-secondary" role="alert">
                                                <div class="alert-text"><h3>Historique de mutation</h3></div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-striped m-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Agence</th>
                                                            <th>Service</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="historique">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <!-- Large Modal modifier un employe-->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="edit_user_modal">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modifier Un Utilisateur</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="update_user_form">
                                                <div class="modal-body">
                                                    <!--debut message-->
                                                        <span id="update_message"></span>
                                                    <!--fin message-->
                                                    <div class="kt-portlet__body">
                                                    <input type="hidden" name="matricule" id="matricule">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Nom<span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="nom1" id="nom1" placeholder="nom de l'employé">
                                                                    <span class="form-text text-danger" id="nom1_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Adresse</label>
                                                                    <input type="text" class="form-control" name="adresse1" id="adresse1" placeholder="adresse">
                                                                    <span class="form-text text-muted">comment vous retrouvez au besion?</span>
                                                                    <span class="form-text text-danger" id="adresse1_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Email</label>
                                                                    <input type="email" class="form-control" name="email1" id="email1" placeholder="email">
                                                                    <span class="form-text text-muted">nous assurons la protection de votre email</span>
                                                                    <span class="form-text text-danger" id="email1_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Numero de telephone 1<span class="text-danger">*</span></label>
                                                                    <input type="tel" class="form-control" name="telephone11" id="telephone11" placeholder="telephone">
                                                                    <span class="form-text text-danger" id="telephone11_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Numero de telephone 2</label>
                                                                    <input type="tel" class="form-control" name="telephone21" id="telephone21" placeholder="telephone">
                                                                    <span class="form-text text-danger" id="telephone21_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Date de naissance</label>
                                                                    <input type="date" class="form-control" name="date_naiss1" id="date_naiss1" placeholder="date de naissance">
                                                                    <span class="form-text text-muted">une date correcte nous permet de partager ce merveilleux jour avec vous</span>
                                                                    <span class="form-text text-danger" id="date_naiss1_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Fonction<span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="fonction1" id="fonction1" placeholder="fonction">
                                                                    <span class="form-text text-muted">quel est votre rôle dans l'entreprise?</span>
                                                                    <span class="form-text text-danger" id="fonction1_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                vous devez absolument remplir les champs ayant <span class="text-danger">*</span>
                                                            </div>
                                                        </div>
                                                    </div>	
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" id="btn_update_save_user" class="btn btn-outline-brand">Modifier</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>




                            </div>
                        </div>


                        <hr class="text-primary">
                        <div class="row" id="list_employe">
                            
                        </div>
			        </div>
		        </div>
		        <!--end::Portlet-->
	        </div>
        </div>	
    </div>

    <!-- Large Modal -->
    <div class="modal bd-example-modal-xl modalfilter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filtrer les employés</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= base_url('filteruser'); ?>" method="post" id="form_filter">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <span>Choisi l'agence</span>
                                <select name="agencefilter" id="agencefilter" class="form-control" >

                                </select>
                                <span class="text-danger" id="agencefilter_error"></span>
                            </div>
                            <div class="col-md-6">
                                <span>Choisi le service</span>
                                <select name="servicefilter" id="servicefilter" class="form-control">

                                </select>
                                <span class="text-danger" id="servicefilter_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-outline-brand" id="filterbtn">Filtrer</button>
                    </div>
                </form>
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

        /**afficher le modal du filtre */
        $("#filtermodal").click(function (e) { 
            e.preventDefault();
            $(".modalfilter").modal('show');
        });

        /**pour creer un nouvel utilisateur */
        $(document).ready(function () {
            $(document).on('click', '#btn_modal', function(){
                agence();
                service();
                pass_provi();
                $("#new_user_modal").modal('show');
            });
        });

        /**liste des agences */
        agence();
        function agence(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('useragence'); ?>",
                dataType: "JSON",
                success: function(data){
                    $("#agence").html(data);
                    $("#agence1").html(data);
                    $("#agencefilter").html(data);
                }
            });
        }

        /**liste des service */
        service();
        function service(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('userservice'); ?>",
                dataType: "JSON",
                success: function(data) {
                    $("#service").html(data);
                    $("#service1").html(data);
                    $("#servicefilter").html(data);
                }
            });
        }

    /*affiche la liste des services d'une agance donnée debut*/
        $("#agence").change(function (e){ 
            service();
            /*var matricule_ag = $("#agence").val();
            if(matricule_ag != ""){
                $.ajax({
                    method: "POST",
                    url: "<?php //echo base_url('get_service_agence'); ?>",
                    data: {matricule_ag:matricule_ag},
                    dataType: "JSON",
                    success: function (data){
                        $("#service").html(data);
                    }
                });
            }else{
               service(); 
            }*/
            
        });
        $("#agence1").change(function (e){
            service(); 
            /*var matricule_ag = $("#agence1").val();
            if(matricule_ag != ""){
                $.ajax({
                    method: "POST",
                    url: "<?php //echo base_url('get_service_agence'); ?>",
                    data: {matricule_ag:matricule_ag},
                    dataType: "JSON",
                    success: function (data){
                        $("#service1").html(data);
                    }
                });
            }else{
               service(); 
            }*/
            
        });
    /*affiche la liste des services d'une agance donnée fin*/

        /**affiche le mot de passe provisoir */
        function pass_provi(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('passProvi'); ?>",
                dataType: "JSON",
                success: function(data) {
                    $("#pass").html(data);
                    $("#password").val(data);
                }
            });
        }

        /**inserer l'employé dans la base des données */
        $('#user_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('employe'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_save_user').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        $('#message').html('');
                        if(data.entreprise_error != ''){
                            $('#entreprise_error').html(data.entreprise_error);
                        }else{
                            $('#entreprise_error').html('');
                        }

                        if(data.agences_error != ''){
                            $('#agences_error').html(data.agences_error);
                        }else{
                            $('#agence_error').html('');
                        }

                        if(data.service_error != ''){
                            $('#service_error').html(data.service_error);
                        }else{
                            $('#service_error').html('');
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

                        if(data.telephone1_error != ''){
                            $('#telephone1_error').html(data.telephone1_error);
                        }else{
                            $('#telephone1_error').html('');
                        }

                        if(data.telephone2_error != ''){
                            $('#telephone2_error').html(data.telephone2_error);
                        }else{
                            $('#telephone2_error').html('');
                        }

                        if(data.date_naiss_error != ''){
                            $('#date_naiss_error').html(data.date_naiss_error);
                        }else{
                            $('#date_naiss_error').html('');
                        }

                        if(data.fonction_error != ''){
                            $('#fonction_error').html(data.fonction_error);
                        }else{
                            $('#fonction_error').html('');
                        } 

                        
                    }
                    if(data.success){
                        $('#entreprise_error').html('');
                        $('#agence_error').html('');
                        $('#service_error').html('');
                        $('#nom_error').html('');
                        $('#adresse_error').html('');
                        $('#email_error').html('');
                        $('#telephone1_error').html('');
                        $('#telephone2_error').html('');
                        $('#date_naiss_error').html('');
                        $('#fonction_error').html('');

                        /**actualise la liste de l'employé */
                        employe();
                        pass_provi();
                        service(); 
                        $('#user_form')[0].reset();
                        $('#message').html(data.success);
                    }
                    $('#btn_save_user').attr('disabled', false);

                }
            });
        });

        /**filtrer les employés */
        $('#form_filter').on('submit', function(event){
            event.preventDefault();
            
            $.ajax({
                method: $(this).attr('method'), 
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#filterbtn').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        if(data.servicefilter_error != ''){
                            $('#servicefilter_error').html(data.servicefilter_error);
                        }else{
                            $('#servicefilter_error').html('');
                        }

                        if(data.agencefilter_error != ''){
                            $('#agencefilter_error').html(data.agencefilter_error);
                        }else{
                            $('#agencefilter_error').html('');
                        } 
                    }
                    if(data.success){
                        $('#servicefilter_error').html('');
                        $('#agencefilter_error').html('');
                        $("#list_employe").html(data.success);
                    }
                    $('#filterbtn').attr('disabled', false);

                }
            });
        });

        /**liste des employe(utilisateurs) */
        employe();
        function employe(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('all_employe'); ?>",
                dataType: "JSON",
                beforeSend:function(){
                    $('#list_employe').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data) {
                    $("#list_employe").html(data);
                    $('.has-spinner').removeAttr("disabled");
                }
            });
        }

        /**désactivé le compte d'un employé */
        $(document).on('click', '.desactive', function(){
            var mat = $(this).attr("id");
            Swal.fire({
                title: 'êtes vous sur?',
                text: "de vouloir désactivé ce compte?",
                type: 'warning',
                showCancelButton:!0,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Annuler',
                confirmButtonText: 'oui'
            }).then((result) => {
                if (result.value){
                    $.ajax({
                        method:"POST",
                        url: "<?php echo base_url('desactive_employe'); ?>",
                        data:{mat:mat},
                        dataType: "json",
                        success: function(data){
                            if(data.success == "ok"){
                                /**actualise la liste de l'employé */
                                employe();
                            }  
                        }
                    });
                }
            }) 
        });

         /**activé le compte d'un employé */
        $(document).on('click', '.active', function(){
            var mat = $(this).attr("id");
            Swal.fire({
                title: 'êtes vous sur?',
                text: "de vouloir activé ce compte?",
                type:"warning",
                showCancelButton:!0,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Annuler',
                confirmButtonText: 'oui'
            }).then(function(e){
                if (e.value) {
                    $.ajax({
                        method:"POST",
                        url: "<?php echo base_url('active_employe'); ?>",
                        data:{mat:mat},
                        dataType: "json",
                        success: function(data){
                            if(data.success == "ok"){
                                /**actualise la liste de l'employé */
                                employe();
                            }  
                        }
                    });
                }
            }) 
        });


        /**voir les détails d'un employé */
        $(document).on('click', '.details', function(){
            var mat = $(this).attr("id");
            $.ajax({
                method:"POST",
                url: "<?php echo base_url('detail_employe'); ?>",
                data:{mat:mat},
                dataType: "json",
                success:function(data){
                    $("#detail").html(data);
                }
            });
            $("#detail_modal").modal('show');
        });


        /**afficher les détails d'un employé(utilisateur) dans un formulaire pour modification*/
        $(document).on('click', '.edit', function(){
            var mat = $(this).attr("id");
            $.ajax({
                method:"POST",
                url: "<?php echo base_url('edit_employe'); ?>",
                data:{mat:mat},
                dataType: "JSON",
                success:function(data){
                    $("#nom1").val(data.employe.nom_emp);
                    $("#adresse1").val(data.employe.adresse_emp);
                    $("#email1").val(data.employe.email_emp);
                    $("#telephone11").val(data.tel1_emp);
                    $("#telephone21").val(data.tel2_emp);
                    $("#date_naiss1").val(data.employe.date_naiss_emp);
                    $("#fonction1").val(data.employe.fonction_emp);
                    $("#matricule").val(data.employe.matricule_emp);
                }
            });
            $("#edit_user_modal").modal('show');
        });

        /**affiche les information dans le formulaire permettant de changer l'agence et ou le service de l'employe*/
        $(document).on('click', '.mute', function(){
            var mat = $(this).attr("id");
            
            $.ajax({
                method:"POST",
                url: "<?php echo base_url('edit_employe'); ?>",
                data:{mat:mat},
                dataType: "JSON",
                success:function(data){
                    $("#nom11").html(data.employe.nom_emp);
                    $("#matricule11").val(data.employe.matricule_emp);
                    $("#agence1").val(data.employe.matricule_ag);
                    $("#service1").val(data.employe.matricule_serv);
                    $('.historique').html(data.historique);
                }
            });
            $("#mute_modal").modal('show');
        });

        /**update les informations d'un utilisateur */
        $('#update_user_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('update_employe'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_update_save_user').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        $('#update_message').html('');

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

                        if(data.telephone11_error != ''){
                            $('#telephone11_error').html(data.telephone11_error);
                        }else{
                            $('#telephone11_error').html('');
                        }

                        if(data.telephone21_error != ''){
                            $('#telephone21_error').html(data.telephone21_error);
                        }else{
                            $('#telephone21_error').html('');
                        }

                        if(data.date_naiss1_error != ''){
                            $('#date_naiss1_error').html(data.date_naiss1_error);
                        }else{
                            $('#date_naiss1_error').html('');
                        }

                        if(data.fonction1_error != ''){
                            $('#fonction1_error').html(data.fonction1_error);
                        }else{
                            $('#fonction1_error').html('');
                        }
                    }
                    if(data.success){
                        $('#nom1_error').html('');
                        $('#adresse1_error').html('');
                        $('#email1_error').html('');
                        $('#telephone11_error').html('');
                        $('#telephone21_error').html('');
                        $('#date_naiss1_error').html('');
                        $('#fonction1_error').html('');

                        /**actualise la liste de l'employé */
                        employe();
                        
                        $('#update_message').html(data.success);
                    }
                    $('#btn_update_save_user').attr('disabled', false);

                }
            });
        });

        /**recherche instantané de la liste des utilisateurs(employé) d'une agence ou service*/
        $("#search").keyup(function (e) { 
            var input = $("#search").val();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('all_employe'); ?>",
                data: {input:input},
                dataType: "JSON",
                beforeSend:function(){
                    $('#list_employe').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data){
                    $("#saisi").html(input);
                    $("#list_employe").html(data);
                    
                    $('.has-spinner').removeAttr("disabled");
                }
            });
        });

        /**modifier le service et ou l'agence d'un employé (muté)*/
        $('#form_mute').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('mute_employe'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_mute').attr('disabled', 'disabled');
                },
                 success: function (data){
                    if(data.error){
                        $('#message_mute').html('');

                        if(data.matricule11_error != ''){
                            $('#matricule11_error').html(data.matricule11_error);
                        }else{
                            $('#matricule11_error').html('');
                        }


                        if(data.agence1_error != ''){
                            $('#agence1_error').html(data.agence1_error);
                        }else{
                            $('#agence1_error').html('');
                        }


                        if(data.service1_error != ''){
                            $('#service1_error').html(data.service1_error);
                        }else{
                            $('#service1_error').html('');
                        }
                        
                    }
                    if(data.success){
                        $('#service1_error').html('');
                        $('#agence1_error').html('');
                        $('#matricule11_error').html('');
                        

                        /**actualise la liste de l'employé */
                        employe();
                        $('#message_mute').html(data.success);

                        setTimeout(function(){
                            window.location.href = "<?php echo base_url('logout'); ?>";
                        }, 5000);
                    }
                    $('#btn_mute').attr('disabled', false);

                }
            });
        });






    });
</script>
