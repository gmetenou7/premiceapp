</div>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
     
    <?php $this->load->view('parts/subheader');?>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
            <!-- begin:: Content -->

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="kt-section">
                            <h3 class="kt-section__title">Entrer en caisse</h3>
                            <div class="kt-section__info">Utilise les paramètres suivants pour faire une entrer de caisse</div>
                            <div class="kt-section__content kt-section__content--border">
                                <?php $this->load->view('parts/message');?>
                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions">
                                        <form id="new_entre_caisse_form">
                                        <div class="row">
                                            <div class="col-lg-4 ml-lg-auto">
                                                <span class="form-text text-muted">Client</span>
                                                <select class="form-control kt-select2 client" id="kt_select2_2" name="client">
                                                    <?php if(!empty($clients)){ ?>
                                                        <?php echo '<option></option>'; ?>
                                                        <?php foreach($clients as $values){?> 
                                                            <?php echo '<option value='.$values['matricule_cli'].'>'.$values['nom_cli'].'</option>'; ?>
                                                        <?php }?> 
                                                    <?php }?> 
                                                </select>
                                                <span id="client_error" class="text-danger"></span>
                                            </div>
                                            <div class="col-lg-4 ml-lg-auto">
                                                <span class="form-text text-muted">Type de document</span>
                                                <select class="form-control kt_select2 tdocument" id="kt_select2_1" name="tdocument">
                                                    <?php echo '<option></option>'; ?>
                                                    <?php if(!empty($docs)){ ?>
                                                        <?php foreach($docs as $value){?> 
                                                            <?php 
                                                                if(set_value('tdocument') == $value['code_doc']){
                                                                    echo '<option value="'.set_value('tdocument').'" selected>'.set_value('tdocument').' / '.$value['intiutle_doc'].' ('.$value['abrev_doc'].')</option>'; 
                                                                }else{
                                                                    echo '<option value='.$value['code_doc'].'>'.$value['code_doc'].' / '.$value['intiutle_doc'].' ('.$value['abrev_doc'].')</option>';
                                                                }   
                                                            ?>
                                                        <?php }?> 
                                                    <?php }?> 
                                                </select>
                                                <span id="tdocument_error" class="text-danger"></span>
                                            </div>
                                            <div class="col-lg-4 ml-lg-auto">
                                                <span class="form-text text-muted">Motif du versement</span>
                                                <input type="text" class="form-control" name="motif" id="motif" placeholder="motif du versement" />
                                                <span id="motif_error" class="text-danger"></span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-6 ml-lg-auto">
                                                <span class="form-text text-muted">Caissier</span>
                                                <select class="form-control caisse" id="caisse" name="caisse">
                                                    <?php if(!empty($caisses)){ ?>
                                                        <?php foreach($caisses as $value){?> 
                                                            <?php echo '<option value='.$value['code_caisse'].'>'.$value['nom_emp'].' ---> '.$value['libelle_caisse'].'</option>'; ?>
                                                        <?php }?> 
                                                    <?php }?>
                                                </select>
                                                <span id="caisse_error" class="text-danger"></span>
                                            </div>
                                            <div class="col-lg-6 ml-lg-auto">
                                                <span class="form-text text-muted">Commercial / Vendeur</span>
                                                <select class="form-control commercial" id="commercial" name="commercial">
                                                    <?php if(!empty($vendeur)){ ?>
                                                        <?php foreach($vendeur as $key=>$values){?> 
                                                            <?php echo '<option value='.$values['matricule'].'>'.$values['nom'].'</option>'; ?>
                                                        <?php }?> 
                                                    <?php }?> 
                                                </select>
                                                <span id="commercial_error" class="text-danger"></span>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-6 ml-lg-auto">
                                                <span class="form-text text-muted">Document</span>
                                                <select class="form-control kt-select2 document code" id="kt_select2_" name="document">
                                                    <?php if(!empty($code_doc)){?> 
                                                        <?php foreach($code_doc as $key => $value){?> 
                                                            <?php echo '<option value='.$key.'>'.$value.'</option>'; ?>
                                                        <?php } ?> 
                                                    <?php }?>
                                                </select>
                                                <span id="document_error" class="text-danger"></span>
                                            </div>
                                            <div class="col-lg-5 ml-lg-auto">
                                                <span class="form-text text-muted">Montant</span>
                                                <input type="text" name="montant" id="montant" class="form-control">
                                                <span id="montant_error" class="text-danger"></span>
                                            </div>
                                            <div class="col-lg-1 ml-lg-auto">
                                                <span class="form-text text-muted">...</span>
                                                <button type="submit" id="new_entrer_btn" class="btn btn-dark">OK</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <span id="message"></span>
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
                                        <th>Document Entrer en Caisse</th>
                                        <th colspan="2">
                                            <input class="form-control" type="search" name="recherche" id="recherche" placeholder="saisi le nom du client ici...">
                                            <span id="text"></span>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="kt-section__content kt-section__content--border">
                                <div class="table-responsive">
                                    <table class="table table-striped m-table">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Montant</th>
                                                <th>Client</th>
                                                <th>Initiateur</th>
                                                <th>Agence</th>
                                                <th>Caisse</th>
                                                <th>Date</th>
                                                <th>Dernière Opération</th>
                                            </tr>
                                        </thead>
                                        <tbody id="all_entrer_c">
                                            
                                        </tbody>
                                        <span id="pagination"></span>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Portlet-->

                <!-- Modal historique entrer en caisse-->
                <div class="modal detail_modal" id="detail_modal">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Détails</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body table-responsive">
                            <table class="table table-striped">
                            <thead>
                                <tr>
                                <th scope="col">Initiateur</th>
                                <th scope="col">Montant</th>
                                <th scope="col">Date Creer</th>
                                <th scope="col">Date Modifier</th>
                                <th scope="col">/</th>
                                <th scope="col">Motif</th>
                                </tr>
                            </thead>
                            <tbody id="infos">
                                
                            </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
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
        /**new entre de caisse debut*/
        $('#new_entre_caisse_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('form_entrer_caisse'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#new_entrer_btn').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){

                        if(data.document_error != ''){
                            $('#document_error').html(data.document_error);
                        }else{
                            $('#document_error').html('');
                        }
                        
                        if(data.tdocument_error != ''){
                            $('#tdocument_error').html(data.tdocument_error);
                        }else{
                            $('#tdocument_error').html('');
                        }

                        if(data.agence_error != ''){
                            $('#agence_error').html(data.agence_error);
                        }else{
                            $('#agence_error').html('');
                        }

                        if(data.commercial_error != ''){
                            $('#commercial_error').html(data.commercial_error);
                        }else{
                            $('#commercial_error').html('');
                        }

                        if(data.montant_error != ''){
                            $('#montant_error').html(data.montant_error);
                        }else{
                            $('#montant_error').html('');
                        }

                        if(data.client_error != ''){
                            $('#client_error').html(data.client_error);
                        }else{
                            $('#client_error').html('');
                        }

                        if(data.caisse_error != ''){
                            $('#caisse_error').html(data.caisse_error);
                        }else{
                            $('#caisse_error').html('');
                        }
                        
                        if(data.motif_error != ''){
                            $('#motif_error').html(data.motif_error);
                        }else{
                            $('#motif_error').html('');
                        }
                    }

                    if(data.success){
                        $('#commercial_error').html('');
                        $('#agence_error').html('');
                        $('#tdocument_error').html('');
                        $('#document_error').html('');
                        $('#montant_error').html('');
                        $('#client_error').html('');
                        $('#caisse_error').html('');
                        $('#motif_error').html('');
                        $('.code').html(data.code);
                        all_entrer(1);
                        $('#message').html(data.success);
                        //$('#new_entre_caisse_form')[0].reset();
                        //$('#accordpayement_error').html('');
                        //$('#new_sorti_caisse_form')[0].reset();
                    }

                    /*if(data.result){
                        
                    }*/
                   $('#new_entrer_btn').attr('disabled', false);
                }
            });
        });
        /**new entrer de caisse fin*/

        /**liste sortie de caisse debut */
        all_entrer(1);
        function all_entrer(page){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('all_entrer/');?>"+page,
                dataType: "json",
                success: function(data){
                    if(data.infos){
                        $("#all_entrer_c").html(data.infos); 
                        $("#pagination").html(data.pagination_link);
                    }
                }
            });
        }
        /**liste sortie de caisse fin */

        /**gerer le click sur la pagination article*/
        $(document).on("click", ".pagination li a", function(event){
            event.preventDefault();
            var page = $(this).data("ci-pagination-page");
            all_entrer(page);
        });
        /**selectionne tous les articles dans la base des données fin */
        
        /**faire la recherche d'un client, dans une entrer de caisse */
        $("#recherche").keyup(function (e) { 
            var recherche = $("#recherche").val();
            $("#text").html(recherche);
            if(recherche !=""){
                $.ajax({
                    method: "POST",
                    url: "<?php echo base_url('all_entrer');?>",
                    data: {recherche:recherche},
                    dataType: "json",
                    success: function(data){
                        if(data.infos){
                            $("#all_entrer_c").html(data.infos); 
                        }
                    }
                });
            }else{
                all_entrer(1);
            }
        });

        /****afficher l'historique des entrer en caisse*****/
        $(document).on("click", ".details", function(event){
            event.preventDefault();
            var code = $(this).attr('id');
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('detail_enter');?>",
                data: {code:code},
                dataType: "json",
                success: function(data){
                    $("#infos").html(data);
                    $("#detail_modal").modal('show');
                }
            }); 
        })
        
        
        /**supprimer une sorti de caisse debut */
        $(document).on("click", ".delete_entre_c", function(event){
            event.preventDefault();
            var code_e = $(this).attr('id');
            swal.fire({
                title:"êtes vous sûr?",
                text: 'vous êtes sur le point d\'annuler cette entrer de caisse...',
                type:"warning",
                showCancelButton:!0,
                confirmButtonText: `Oui`,
            }).then(function(e){
                if(e.value){
                    $.ajax({
                        method: "POST",
                        url: "<?php echo base_url('delete_e_c');?>",
                        data: {code_e:code_e},
                        dataType: "json",
                        success: function(data){
                            all_entrer(1);
                            $("#message").html(data);
                            $(".detail_modal").modal('hide');
                        }
                    });
                }else{
                    Swal.fire('Les modifications ne sont pas enregistrées', '', 'info')
                }
            })

        })
        /**supprimer une sorti de caisse fin */
        
       
        
    });
</script>
