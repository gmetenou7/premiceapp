</div>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    
    <?php $this->load->view('parts/subheader');?>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
            <!-- begin:: Content -->

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="kt-section">
                            <h3 class="kt-section__title">Ratacher une facture</h3>
                            <div class="kt-section__info">Utilise les paramètres suivants pour ratacher</div>
                            <div class="kt-section__content kt-section__content--border">
                                <?php $this->load->view('parts/message');?>
                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions">
                                        <form id="ratachement_form">
                                            <div class="row">
                                                <div class="col-lg-5 ml-lg-auto">
                                                    <span class="form-text text-muted">Client</span>
                                                    <select class="form-control kt-select2 client" id="kt_select2_1" name="client">
                                                        <?php if(!empty($clients)){ ?>
                                                            <?php echo '<option></option>'; ?>
                                                            <?php foreach($clients as $values){?> 
                                                                <?php echo '<option value='.$values['matricule_cli'].'>'.$values['nom_cli'].'</option>'; ?>
                                                            <?php }?> 
                                                        <?php }?> 
                                                    </select>
                                                    <span id="client_error" class="text-danger"></span>
                                                </div>
                                                <div class="col-lg-5 ml-lg-auto">
                                                    <span class="form-text text-muted">Facture</span>
                                                    <select class="form-control facture kt_select2 facture" id="kt_select2_3" name="facture" >
                                                        
                                                    </select>
                                                    <span id="facture_error" class="text-danger"></span>
                                                </div>
                                                <div class="col-lg-2 ml-lg-auto">
                                                    <span class="form-text text-muted">...</span>
                                                    <button type="submit" id="btn_new_ratache" class="btn btn-dark">OK</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <b class="text-danger" id="message"></b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Portlet-->

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="kt-section">
                                <table class="table table-striped m-table">
                                    <thead>
                                        <tr>
                                            <th colspan="3">Historique Facture ratacher</th>
                                        </tr>
                                    </thead>
                                </table>
                            <div class="kt-section__content kt-section__content--border">
                                
                                <div class="table-responsive">
                                    <input type="search" name="recherche" id="recherche" class="form-control" placeholder="saisi le nom du client ici...">
                                    <span id="text"></span>
                                    <hr>
                                    <table class="table table-striped m-table">
                                        <thead>
                                            <tr>
                                                <th>Client</th>
                                                <th>Caisse</th>
                                                <th>Détails</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ratachdoc"> 
                                           
                                        </tbody>
                                        <span id="pagination"></span>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Portlet-->



                <!--details modal-->
                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true" id="detailsmodal">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Historique de ratachement effectué sur ce document</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                   <span id="infos"></span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-brand btn-outline-danger" data-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- end:: Content -->				

        </div>

    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->
<script>
    $(document).ready(function () {

        /**affiche les dettes du client */
        $(".client").change(function (e) { 
            e.preventDefault();
            var code_cli = $(".client").val();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('reglementcli_cli');?>",
                data: {code_cli:code_cli},
                dataType: "json",
                success:function (data){
                    if(data.success){
                        $(".facture").html(data.success);
                    }
                }
            });
        });

        /**new ratachament debut*/
        $('#ratachement_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('op_ratache_fac'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_new_ratache').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        if(data.client_error != ''){
                            $('#client_error').html(data.client_error);
                        }else{
                            $('#client_error').html('');
                        }
                        
                        if(data.facture_error != ''){
                            $('#facture_error').html(data.facture_error);
                        }else{
                            $('#facture_error').html('');
                        }
                    }
                    if(data.success){
                        $('#client_error').html('');
                        $('#facture_error').html('');
                        historiquedoc(1);
                        $('#message').html(data.success);
                    }
                   $('#btn_new_ratache').attr('disabled', false);
                }
            });
        });
        /**new sorti de caisse fin*/
        
        /**affiche l'historique de ratachement des dettes*/
        historiquedoc(1);
        function historiquedoc(page){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('storyratachedoc/'); ?>"+page,
                dataType: "json",
                success: function (data){
                    if(data.success){
                        $('#ratachdoc').html(data.success);
                        $("#pagination").html(data.pagination_link);
                    }
                }
            });
        }
        /**gerer le click sur la pagination article*/
        $(document).on("click", ".pagination li a", function(event){
            event.preventDefault();
            var page = $(this).data("ci-pagination-page");
            historiquedoc(page);
        });
        /**selectionne tous les articles dans la base des données fin */
        
        /**rechercher un client sur un ratachement */
        $("#recherche").keyup(function (e) { 
            var recherche = $("#recherche").val();
            $("#text").html(recherche);

            if(recherche !=""){
                $.ajax({
                    method: "POST",
                    url: "<?php echo base_url('storyratachedoc'); ?>",
                    data: {recherche:recherche},
                    dataType: "json",
                    success: function (data){
                        if(data.success){
                            $('#ratachdoc').html(data.success);
                            $("#pagination").html("");
                        }
                    }
                });
            }else{
                historiquedoc(1);
                $("#text").html('');
            }
        });

        /****affiche les details de ratachement d'un document*/
        $(document).on("click", ".detailsdoc", function(event){
            event.preventDefault();
            var code = $(this).attr('id');
            $.ajax({
                method: "POST",
                data: {code:code},
                url: "<?php echo base_url('storyratache'); ?>",
                dataType: "json",
                beforeSend:function(){
                    $(".detailsdoc").addClass('spinner-grow');
                },
                success: function (data){
                    if(data.success){
                        $('#infos').html(data.success);
                        $("#detailsmodal").modal('show');
                        $(".detailsdoc").removeClass('spinner-grow');
                    }
                    
                }
            });
            
        })



        /**supprimer un ratachement de caisse debut */
        $(document).on("click", ".delete_hist_ratach", function(event){
            event.preventDefault();
            var code_rat = $(this).attr('id');
            swal.fire({
                title:"êtes vous sûr?",
                text: 'vous êtes sur le point d\'annuler ce ratachement...',
                type:"warning",
                showCancelButton:!0,
                confirmButtonText: `Oui`,
            }).then(function(e){
                if(e.value){
                    $.ajax({
                        method: "POST",
                        url: "<?php echo base_url('delete_ratach_doc');?>",
                        data: {code_rat:code_rat},
                        dataType: "json",
                        success: function(data){
                            if(data.success){
                                historiquedoc(1);
                                $("#message").html(data.success);
                                $("#detailsmodal").modal('hide');
                            }
                            if(data.error){
                                $("#message").html(data.error);   
                            }
                            
                        }
                    });
                }else{
                    Swal.fire('Les modifications ne sont pas enregistrées', '', 'info')
                }
            })

        })
        /**supprimer un ratachement de caisse fin */

        
        
    });
</script>
