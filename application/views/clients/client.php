
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
                            <h3 class="kt-portlet__head-title">Gestion Des clients</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-outline-brand" data-toggle="modal" data-target=".bd-example-modal-xl">Nouveau Client</button>
                                <hr>
                                <span class="form-text text-muted text-primary">
                                    <b>1: </b>saisi ici le nom du client ou scan son code à bare pour l'afficher <br>
                                    <b>2: </b>saisi le nom d'un employé pour voir la liste des ses clients
                                </span>
                                <input type="search" name="search" id="search" class="form-control" placeholder="saisi ici...">
                                <span id="saisi"></span>
                            </div>
                        </div>


                        <hr class="text-primary">
                        <div class="row" id="list_client">
                           <!-- liste des clients -->
                        </div>
                        <span id="paginations"></span>


			        </div>
		        </div>
		        <!--end::Portlet-->
	        </div>
        </div>	
    </div>


    <!-- Large Modal pour la création d'un client debut-->
    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Creer Un Client</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!--begin::Form-->
                    <div class="modal-body">
                        <div class="kt-portlet__body">
                            <div class="form-group form-group-last">
                                <div class="alert alert-secondary" role="alert">
                                    <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                                    <div class="alert-text">
                                        les champs avec <span class="text-danger">*</span> sont obligatoire
                                    </div>
                                </div>
                            </div>

                            <!--message de retour debut-->
                            <span id="message"></span>
                            <!--message de retour fin-->

                            <form class="kt-form" id="client_form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nom<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nom" id="nom" placeholder="nom du client">
                                            <span class="form-text text-danger" id="nom_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Adresse</label>
                                            <input type="text" class="form-control" id="adresse" nom="adresse" placeholder="quartier du client">
                                            <span class="form-text text-danger" id="adresse_error"></span>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Téléphone 1<span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" name="telephone1" id="telephone1" placeholder="téléphone">
                                            <span class="form-text text-danger" id="telephone1_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Téléphone 2</label>
                                            <input type="tel" class="form-control" name="telephone2" id="telephone2" placeholder="téléphone">
                                            <span class="form-text text-danger" id="telephone2_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" name="email" id="email"  placeholder="l'email du client">
                                            <span class="form-text text-danger" id="email_error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Date de naissance</label>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Jour</label>
                                                        <select name="jour" id="jour" class="form-control">
                                                            <option value=""></option>
                                                            <?php if(!empty($jour)){?>
                                                                <?php foreach ($jour as $key => $value) {?>
                                                                    <option value="<?php echo $value;?>"><?php echo $value;?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                        <span class="form-text text-danger" id="jour_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Mois</label>
                                                        <select name="mois" id="mois" class="form-control">
                                                            <option value=""></option>
                                                            <?php if(!empty($mois)){?>
                                                                <?php foreach ($mois as $key => $value) {?>
                                                                    <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                        <span class="form-text text-danger" id="mois_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Année</label>
                                                        <select name="annee" id="annee" class="form-control">
                                                            <option value=""></option>
                                                            <?php if(!empty($annee)){?>
                                                                <?php foreach ($annee as $key => $value) {?>
                                                                    <option value="<?php echo $value;?>"><?php echo $value;?></option>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </select>
                                                        <span class="form-text text-danger" id="annee_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                                    <button type="submit" id="btn_new_client" class="btn btn-outline-primary">Creer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <!--end::Form-->
            </div>
        </div>
    </div>
    <!-- Large Modal pour la création d'un client fin-->

    <!-- voir les informations d'un client debut-->
    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="details_modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Détails</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span id="detail_client"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
        <!-- voir les informations d'un client fin-->

    <!-- Large Modal pour la modification d'un client debut-->
    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true" id="modal_edit">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier Un Client</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!--begin::Form-->
                <form class="kt-form" id="update_client_form">
                <div class="modal-body">
                    <div class="kt-portlet__body">
                        <div class="form-group form-group-last">
                            <div class="alert alert-secondary" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                                <div class="alert-text">
                                    les champs avec <span class="text-danger">*</span> sont obligatoire
                                </div>
                            </div>
                        </div>

                            <!--message de retour debut-->
                        <span id="message_update"></span>
                        <!--message de retour fin-->
                        <input type="hidden" name="matricule" id="matricule" class="form-control">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nom1" id="nom1" placeholder="nom du client">
                                    <span class="form-text text-danger" id="nom1_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Adresse</label>
                                    <input type="text" class="form-control" id="adresse1" nom="adresse1" placeholder="quartier du client">
                                    <span class="form-text text-danger" id="adresse1_error"></span>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Téléphone 1<span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="telephone11" id="telephone11" placeholder="téléphone">
                                    <span class="form-text text-danger" id="telephone11_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Téléphone 2</label>
                                    <input type="tel" class="form-control" name="telephone21" id="telephone21" placeholder="téléphone">
                                    <span class="form-text text-danger" id="telephone21_error"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email1" id="email1"  placeholder="l'email du client">
                                    <span class="form-text text-danger" id="email1_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                    <button type="submit" id="btn_update_client" class="btn btn-outline-primary">Modifier</button>
                </div>
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
    <!-- Large Modal pour la modification d'un client fin-->

    <!-- Large Modal -->
    <div class="modal" id="reattributmodal">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reattribuer Un Client A un Employé</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>en reattribuant ce client à cet employé, toutes les transactions lié à ce client lui reviendrons</p>
                    <form action="<?php echo base_url('optreatribution');?>" method="post" id="formreattribut">
                        <span id="messager"></span>
                        <hr>
                        <input type="hidden" name="matclient" id="matclient">
                        <select class="form-control kt-select2" id="kt_select2_3" name="employe" style="width:370px">
                        </select>
                        <span class="text-danger" id="employe_error"></span>
                        <button type="submit" class="btn btn-outline-brand" id="reattributbtn">Réattribuer</button>
                    </form>
                    <hr>
                    <span class="text-muted text-danger">Historique de réattribution de ce client</span>
                    <hr>
                    <table class="table">
                        <thead class="thead-dark">
                            <th>Employé précedant</th>
                            <th>date</th>
                        </thead>
                        <tbody id="hreatribution">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

<!-- end:: Content -->				
            <!-- end:: Content -->
        </div>

    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->


<!-- script ajax -->
<script type="text/javascript">

    /**créer un nouveau client*/
    $('#client_form').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            method: "POST",
            url: "<?php echo base_url('new_client'); ?>",
            data: $(this).serialize(),
            dataType: "json",
            beforeSend:function(){
                $('#btn_new_client').attr('disabled', 'disabled');
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

                    if(data.jour_error!= ''){
                        $('#jour_error').html(data.jour_error);
                    }else{
                        $('#jour_error').html('');
                    }

                    if(data.mois_error!= ''){
                        $('#mois_error').html(data.mois_error);
                    }else{
                        $('#mois_error').html('');
                    }

                    if(data.annee_error!= ''){
                        $('#annee_error').html(data.annee_error);
                    }else{
                        $('#annee_error').html('');
                    }
                }
                if(data.success){
                    $('#jour_error').html('');
                    $('#mois_error').html('');
                    $('#annee_error').html('');
                    $('#nom_error').html('');
                    $('#adresse_error').html('');
                    $('#telephone1_error').html('');
                    $('#telephone2_error').html('');
                    $('#email_error').html('');
                    $('#client_form')[0].reset();
                    $('#message').html(data.success);
                    all_client(1);
                }
                $('#btn_new_client').attr('disabled', false);
            }
        });
    });

    /**affiche la liste des tous les clients*/
    all_client(1);
    function all_client(page){
        $.ajax({
            method: "GET",
            url: "<?php echo base_url('get_all_client/'); ?>"+page,
            dataType: "json",
            success: function (data){
                if(data.success){
                    $('#list_client').html(data.success);
                    $('#paginations').html(data.pagination);
                }
            }
        });   
    }
    /**actionner les paginations */
    $(document).on("click", ".pagination li a", function(event){
        event.preventDefault();
        var page = $(this).data("ci-pagination-page");
        all_client(page);
    });

    /**afficher les informations d'un client paticulier*/
    $(document).on('click', '.view', function(){
        var mat_client = $(this).attr("id");
        $.ajax({
            method: "POST",
            url: "<?php echo base_url('get_single_client'); ?>",
            data: {mat_client:mat_client},
            dataType: "json",
            success: function (data) {
               $('#detail_client').html(data);
               $("#details_modal").modal('show');
            }
        });
    });

    /**affiche les informations d'un client particulier dans le formulaire de modification*/
     $(document).on('click', '.edit', function(){
        var mat_client = $(this).attr("id");
        $.ajax({
            method: "POST",
            url: "<?php echo base_url('getsingleclient'); ?>",
            data: {mat_client:mat_client},
            dataType: "json",
            success: function (data){
                $('#matricule').val(data.info.matricule_cli);
                $('#nom1').val(data.info.nom_cli);
                $('#adresse1').val(data.info.adresse_cli);
                $('#telephone11').val(data.telephone1);
                $('#telephone21').val(data.telephone2);
                $('#email1').val(data.info.email_cli);
            }
        });
        $("#modal_edit").modal('show');

    });

    /**modifier un nouveau client*/
    $('#update_client_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('update_client'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_update_client').attr('disabled', 'disabled');
                },
                success: function (data) {
                    if(data.error){
                        $('#message_update').html('');
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
                    }
                    if(data.success){
                        $('#nom1_error').html('');
                        $('#adresse1_error').html('');
                        $('#telephone11_error').html('');
                        $('#telephone21_error').html('');
                        $('#email1_error').html('');
                        //$('#client_form')[0].reset();

                        all_client();

                        $('#message_update').html(data.success);
                    }
                   $('#btn_update_client').attr('disabled', false);
                }
            });
        });


    /**recherche instantanné*/
    $("#search").keyup(function(){
        var input = $("#search").val();
        if(input != ''){
            $("#saisi").html(input);
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('get_all_client'); ?>",
                data:{input:input},
                dataType: "json",
                success: function (data) {
                    if(data.success){
                        $('#list_client').html(data.success);
                    }
                }
            });
        }else{
            all_client(1);
            $("#saisi").html('');
        } 
    });

    /**affiche le modal pour la réattribution */
    $(document).on('click', '.reattribut', function(){
        var mat_client = $(this).attr("id");
        $.ajax({
            method: "POST",
            url: "<?php echo base_url('getsingleclient'); ?>",
            data: {mat_client:mat_client},
            dataType: "json",
            success: function (data){
                hreatribution(mat_client);
                $("#matclient").val(data.info.matricule_cli);
                $("#kt_select2_3").val(data.info.mat_emp);
                $("#reattributmodal").modal('show');
            }
        });
    });

    /**liste des employes pour reattribution */
    employe();
    function employe(){
        $.ajax({
            type: "GET",
            url: "<?php echo base_url('employeattrib');?>",
            dataType: "json",
            success: function (data) {
                $("#kt_select2_3").append(data);
            }
        });
    }

    /**op reattribution de client */
    $('#formreattribut').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            method: $(this).attr('method'),
            url:  $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend:function(){
                $('#reattributbtn').attr('disabled', 'disabled');
            },
            success: function (data) {
                if(data.error){
                    $('#messager').html('');
                    if(data.employe_error != ''){
                        $('#employe_error').html(data.employe_error);
                    }else{
                        $('#employe_error').html('');
                    }
                }
                if(data.success){
                    $('#employe_error').html('');
                    $('#messager').html(data.success);
                }
                $('#reattributbtn').attr('disabled', false);
            }
        });
    });

    /**historique de reatribution */
    function hreatribution(matclient){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('hreattrib'); ?>",
            data:{matclient:matclient},
            dataType: "json",
            success: function(data){
                $("#hreatribution").html(data.success);
            }
        });
    }

</script>

