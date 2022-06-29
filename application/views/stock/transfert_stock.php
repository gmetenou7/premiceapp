
</div>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    
    <?php $this->load->view('parts/subheader');?>

    --------------------------------------------------------------------------------------
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <!--begin::Row-->
            <div class="row">	
                <div class="col-xl-12">
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Faire les transferts et réception de transfert de stock
                                </h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <!--begin::Section-->
                            <div class="kt-section">
                               
                                <!--end::formulaire des paramètre pour commencer le transfert-->
                                <form action="" id="new_transfert">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="">Type Document</label>
                                            <select class="form-control kt-select2 type_doc_2" id="kt_select2_2" name="type_doc_2">
                                                <?php if(!empty($docs)){?>
                                                    <?php echo '<option></option>'; ?>
                                                    <?php foreach ($docs as $key => $value):?>
                                                        <?php 
                                                            if(set_value('type_doc_2') == $value['code_doc'] ||  $this->uri->segment(2) == $value['code_doc']){
                                                                $val = set_value('type_doc_2')?set_value('type_doc_2'):$this->uri->segment(2);
                                                                echo '<option value="'.$val.'" selected>'.$value['code_doc'].' / '.$value['intiutle_doc'].' ('.$value['abrev_doc'].')</option>'; 
                                                            }else{
                                                                echo '<option value="'.$value['code_doc'].'">'.$value['code_doc'].' / '.$value['intiutle_doc'].' ('.$value['abrev_doc'].')</option>';
                                                            }
                                                        ?>
                                                    <?php endforeach; ?>
                                                <?php }else{ ?>
                                                    <?php echo '<option selected disabled>aucun document trouvé</option>'; ?>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger" id="type_doc_2_error"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="">Dépot initiateur</label>
                                            <select class="form-control kt-select2 depot_i" id="kt_select2_9" name="depot_i">
                                                <?php if(!empty($depots)):?>
                                                    <?php foreach ($depots as $key => $value):?>
                                                        <?php 
                                                            if(set_value('depot') == $value['mat_depot']){
                                                                echo '<option value="'.set_value('depot').'" selected>'.$value['mat_depot'].' / '.$value['nom_depot'].'</option>'; 
                                                            }else{
                                                                echo '<option value="'.$value['mat_depot'].'">'.$value['mat_depot'].' / '.$value['nom_depot'].'</option>';
                                                            }
                                                        ?>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <?php echo '<option selected disabled>aucun dépot trouvé</option>'; ?>
                                                <?php endif ?>
                                            </select>
                                            <span class="text-danger" id="depot_i_error"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="">Dépot recepteur</label>
                                            <select class="form-control kt-select2 depot_r" id="kt_select2_3" name="depot_r">
                                                <?php if(!empty($depots)):?>
                                                    <?php foreach ($depots as $key => $value):?>
                                                        <?php 
                                                            if(set_value('depot') == $value['mat_depot']){
                                                                echo '<option value="'.set_value('depot').'" selected>'.$value['mat_depot'].' / '.$value['nom_depot'].'</option>'; 
                                                            }else{
                                                                echo '<option value="'.$value['mat_depot'].'">'.$value['mat_depot'].' / '.$value['nom_depot'].'</option>';
                                                            }
                                                        ?>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <?php echo '<option selected disabled>aucun dépot trouvé</option>'; ?>
                                                <?php endif ?>
                                            </select>
                                            <span class="text-danger" id="depot_r_error"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="">Motif</label>
                                            <input type="text" name="motif" id="motif" placeholder="motif" class="form-control motif">
                                            <span class="text-danger" id="motif_error"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="">Ajouter l'article dans un document</label>
                                            
                                            <select class="form-control kt-select2 document" id="kt_select2_7" name="document">
                                                <optgroup label="Nouveau Document">
                                                    <?php if(!empty($code_doc)):?>
                                                        <?php foreach ($code_doc as $key => $value):?>
                                                            <?php 
                                                                if(set_value('document') == $key){
                                                                    echo '<option value="'.set_value('document').'" selected>'.$key.' / '.$value.'</option>'; 
                                                                }else{
                                                                    echo '<option value="'.$key.'">'.$key.' / '.$value.'</option>'; 
                                                                }
                                                            ?>
                                                        <?php endforeach; ?>
                                                    <?php endif ?>
                                                </optgroup>
                                                <optgroup label="Document existant">
                                                    <?php if(!empty($get_docs)):?>
                                                        <?php foreach ($get_docs as $key => $value):?>
                                                            <?php 
                                                                echo '<option value="'.$value['code_document'].'">'.$value['code_document'].' / '.$value['nom_document'].'</option>'; 
                                                            ?>
                                                        <?php endforeach; ?>
                                                    <?php endif ?>
                                                </optgroup>
                                            </select>
                                            <span class="text-danger" id="document_error"></span>
                                        </div>
                                        <div class="col-md-5">
                                            <label for="">choisir l'article: Code à Barre / Désignation / Référence</label>
                                            <select class="form-control kt-select2 article" id="kt_select2_8" name="article" id="article">
                                                <?php if(!empty($articles)):?>
                                                    <?php echo '<option></option>'; ?>
                                                    <?php foreach ($articles as $key => $value):?>
                                                        <?php 
                                                            if(set_value('article') == $value['matricule_art']){
                                                                echo '<option value="'.set_value('article').'" selected>'.$value['matricule_art'].' / '.$value['code_bar'].' / '.$value['designation'].' / '.$value['reference'].'</option>'; 
                                                            }else{
                                                                echo '<option value="'.$value['matricule_art'].'">'.$value['matricule_art'].' / '.$value['code_bar'].' / '.$value['designation'].' / '.$value['reference'].'</option>'; 
                                                            }
                                                        ?>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <?php echo '<option selected disabled>aucun article trouvé</option>'; ?>
                                                <?php endif ?>
                                            </select>
                                            <span class="text-danger" id="article_error"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <label for="">Raison</label>
                                            <textarea name="raison" id="raison" cols="30" rows="1" placeholder="explique la raison ici" class="form-control raison"></textarea>
                                            <span class="text-danger" id="raison_error"></span>
                                        </div>
                                        <div class="col-md-2">
                                            <hr>
                                            <button class="btn btn-primary" type="submit" id="btn_sub_form">ok</button>
                                        </div>
                                    </div>
                                </form>
                                <!--end::formulaire des paramètre pour commencer le transfert-->
                                <br> <hr>
                                <span id="message" class="text-danger"></span>
                            </div>
                            <!--end::Section-->
                        </div>
                    </div>
                </div>

                <!--begin::Portlet affiche les documents de transferte encour et recu-->
                <div class="kt-portlet kt-portlet--tabs">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Les Transferts</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                        <button type="button" class="btn btn-outline-brand" id="btn_transfert_attente">Transferts en attente</button>
                        <button type="button" class="btn btn-outline-brand" id="btn_transfert_recu">Transferts Receptionnés</button>
                        </div>
                    </div>
                    <div class="kt-portlet__body">

                        <div class="modal fade" id="modal_transfert_attente">
							<div class="modal-dialog modal-xl modal-dialog-scrollable">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Les Transferts en attente</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
                                        <table class="table table-striped m-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nom Document</th>
                                                    <th>Depot Initiateur</th>
                                                    <th>Depot Recepteur</th>
                                                    <th>Effectué par</th>
                                                    <th>Date</th>
                                                    <th>Dernière Modif</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="transfert_attente">
                                                
                                            </tbody>
                                        </table>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
									</div>
								</div>
							</div>
						</div>

                        <div class="modal fade" id="modal_transfert_recu">
							<div class="modal-dialog modal-xl modal-dialog-scrollable">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Les Transferts en recu</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
                                        <table class="table table-striped m-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nom Document</th>
                                                    <th>Depot Initiateur</th>
                                                    <th>Depot Recepteur</th>
                                                    <th>Effectué par</th>
                                                    <th>Statut</th>
                                                    <th>Date</th>
                                                    <th>Dernière Modif</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="transfert_recu">

                                            </tbody>
                                        </table>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
									</div>
								</div>
							</div>
						</div>
     
                    </div>
                </div>
                <!--end::Portlet affiche les documents de transfert encour et recu-->



            </div>
            <!--end::Row-->	
        </div>
        <!-- end:: Content -->				
    </div>
    --------------------------------------------------------------------------------------

    <!--modal mettre un article en stock debut -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modal_new_stock">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Stock</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="op_transfert_form">
                    <div class="modal-body">
                        <input type="hidden" name="t_doc" id="t_doc" class="t_doc">
                        <input type="hidden" name="dep_i" id="dep_i" class="dep_i">
                        <input type="hidden" name="dep_r" id="dep_r" class="dep_r">
                        <input type="hidden" name="motif_event" id="motif_event" class="motif_event">
                        <input type="hidden" name="doc_m" id="doc_m" class="doc_m">
                        <input type="hidden" name="art" id="art" class="art">
                        <input type="hidden" name="raison_event" id="raison_event" class="raison_event">
                        <div class="table-responsive">
                            <table class="table table-striped m-table">
                                <thead>
                                    <tr>
                                        <th>Code à barre</th>
                                        <th>Désignation</th>
                                        <th>Référence</th>
                                        <th>Prix de révient</th>
                                        <th>Quantité Actuel</th>
                                        <th>Quantité</th>
                                        <th>Pourcentage de marge</th>       
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="bar"></th>
                                        <td class="design"></td>
                                        <td class="ref"></td>
                                        <td class="prix_r"></td>
                                        <td class="qte_act"></td>
                                        <td>
                                            <input type="text" name="new_quantes" id="new_quantes" placeholder="0" class="new_quantes"> <br>
                                            <span class="text-danger" id="new_quantes_error"></span>
                                        </td>
                                        <td class="marge"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btn_op_transfert_form" class="btn btn-outline-primary">Enrégistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--modal mettre un article en stock fin -->
    
    <!--afficher les articles d'un document debut-->
    <div class="modal fade" id="article_doc_modal">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Articles Du document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped m-table">
                            <thead>
                                <tr>
                                    <th>Code à bar</th>
                                    <th>Nom</th>
                                    <th>Référence</th>
                                    <th>Quatite</th>
                                    <th>Users</th>
                                    <th>date creation</th>
                                    <th>Dernière Modification</th>
                                </tr>
                            </thead>
                            <tbody id="articles_doc">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <!--afficher les articles d'un document fin-->

    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->

<script>
    $(document).ready(function () {
        /**afficher la liste des documents en fonction du type de document debut*/
        $(".type_doc_2").change(function(){
           var type_doc  = $(".type_doc_2").val();
           window.location = '<?php echo base_url('transfert/')?>'+type_doc;
           transfert_attente();
           transfert_recu();
        });
        /**afficher la liste des documents en fonction du type de document fin*/


         /**afficher le formilaire d'entrer/srtie ou autre du stock debut*/
        $('#new_transfert').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('show_form_transfert'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_sub_form').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        $('#message').html('');
                        if(data.type_doc_2_error != ''){
                            $('#type_doc_2_error').html(data.type_doc_2_error);
                        }else{
                            $('#type_doc_2_error').html('');
                        }

                        if(data.depot_i_error != ''){
                            $('#depot_i_error').html(data.depot_i_error);
                        }else{
                            $('#depot_i_error').html('');
                        }

                        if(data.depot_r_error != ''){
                            $('#depot_r_error').html(data.depot_r_error);
                        }else{
                            $('#depot_r_error').html('');
                        }

                        if(data.motif_error != ''){
                            $('#motif_error').html(data.motif_error);
                        }else{
                            $('#motif_error').html('');
                        }

                        if(data.article_error != ''){
                            $('#article_error').html(data.article_error);
                        }else{
                            $('#article_error').html('');
                        }

                        if(data.raison_error != ''){
                            $('#raison_error').html(data.raison_error);
                        }else{
                            $('#raison_error').html('');
                        }

                        if(data.document_error != ''){
                            $('#document_error').html(data.document_error);
                        }else{
                            $('#document_error').html('');
                        }
                        
                    }

                    if(data.success){
                        $('#type_doc_2_error').html('');
                        $('#depot_i_error').html('');
                        $('#depot_r_error').html('');
                        $('#motif_error').html('');
                        $('#article_error').html('');
                        $('#raison_error').html('');
                        $('#document_error').html('');
                        $('#message').html(data.success);


                        $('.bar').html(data.article.code_bar);
                        $('.design').html(data.article.designation);
                        $('.ref').html(data.article.reference);
                        $('.prix_r').html(data.article.prix_revient);
                        $('.qte_act').html(data.article.quantite);
                        $('.marge').html(data.article.pourcentage_marge);

                        $('.t_doc').val($('.type_doc_2').val());
                        $('.dep_i').val($('.depot_i').val());
                        $('.dep_r').val($('.depot_r').val());
                        $('.motif_event').val($('.motif').val());
                        $('.doc_m').val($('.document').val());
                        $('.art').val(data.article.matricule_art);
                        $('.raison_event').val($('.raison').val());
                        
                        $('.new_quantes').val('');

                        $("#modal_new_stock").modal('show');

                        transfert_attente();
                        transfert_recu();
                    }
                    if(data.info){
                        $('#type_doc_2_error').html('');
                        $('#depot_i_error').html('');
                        $('#depot_r_error').html('');
                        $('#motif_error').html('');
                        $('#article_error').html('');
                        $('#raison_error').html('');
                        $('#document_error').html('');
                        $('#message').html(data.info);
                    }
                    $('#btn_sub_form').attr('disabled', false);
                }
            });
        });
        /**afficher le formilaire d'entrer/srtie ou autre du stock fin*/

        /**effectuer les opérations sur le stock debut */
        $('#op_transfert_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('op_transfert_stock'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_op_transfert_form').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        $('#message').html('');
                        if(data.new_quantes_error != ''){
                            $('#new_quantes_error').html(data.new_quantes_error);
                        }else{
                            $('#new_quantes_error').html('');
                        }
                    }
                    if(data.success){
                        transfert_attente();
                        $('#new_quantes_error').html('');
                        $("#modal_new_stock").modal('hide');
                        $('#message').html(data.success);

                        transfert_attente();
                        transfert_recu();
                    }
                    $('#btn_op_transfert_form').attr('disabled', false);
                }
            });
        });
        /**effectuer les opérations sur le stock fin */

        /**affiche la liste des transfert de stock et des réception de stock dans le modal debut */
        $(document).on("click", "#btn_transfert_attente", function(event){
            event.preventDefault();
            transfert_attente();
            $("#modal_transfert_attente").modal('show');
        });

        $(document).on("click", "#btn_transfert_recu", function(event){
            event.preventDefault();
            transfert_attente();
            $("#modal_transfert_recu").modal('show');
        });
        /**affiche la liste des transfert de stock et des réception de stock dans le modal fin */

        

        /**affiche la liste des documents de transfert en attente debut */
        transfert_attente();
        function transfert_attente(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('transfert_attente'); ?>",
                dataType: "json",
                success: function(data){
                   $("#transfert_attente").html(data); 
                }
            });
        }
        /**affiche la liste des documents de transfert en attente fin */

        /**affiche la liste des transferts réceptionné debut*/
        transfert_recu();
        function transfert_recu(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('transfert_recu'); ?>",
                dataType: "json",
                success: function(data){
                   $("#transfert_recu").html(data); 
                }
            });
        }
        /**affiche la liste des transferts réceptionné debut*/

        /**voir la liste des articles d'un document debut*/
        $(document).on("click", ".showartdoc", function(event){
            event.preventDefault();

            var code_doc = $(this).attr('id');
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('art_doc_view');?>",
                data: {code_doc:code_doc},
                dataType: "json",
                success: function (data){
                   $("#articles_doc").html(data); 
                    $("#article_doc_modal").modal('show');
                    transfert_attente();
                    transfert_recu();
                }
            });
        });
        /**voir la liste des articles d'un document fin*/

        /**approuver un document de transfert debut */
        $(document).on("click", ".aprouvedoc", function(event){
            event.preventDefault();
            var code_doc = $(this).attr('id');
        
            swal.fire({
                title:"Es-tu sûr?",
                text:"Vous ne pourrez pas revenir en arrière!",
                type:"warning",
                showCancelButton:!0,
                confirmButtonText:"Oui!"
            }).then(function(e){
                if(e.value){
                    $.ajax({
                        method: "POST",
                        url: "<?php echo base_url('confirmtransfert');?>",
                        data: {code_doc:code_doc},
                        dataType: "json",
                        success: function(data){
                            Swal.fire("Parfait!", data, 'success')
                            transfert_attente();
                            transfert_recu();
                        }
                    });
                }else{
                    Swal.fire('Les modifications ne sont pas enregistrées', '', 'info') 
                }
            })



        });
        /**approuver un document de transfert fin */

        /**annule un transfert de stock debut */
        $(document).on("click", ".canceltransfert", function(event){
            event.preventDefault();
            var code_doc = $(this).attr('id');
            swal.fire({
                title:"Es-tu sûr?",
                text: 'Voulez-vous enregistrer les modifications ?',
                type:"warning",
                showCancelButton:!0,
                confirmButtonText: `Oui`,
            }).then(function(e){
                if(e.value){
                    $.ajax({
                        method: "POST",
                        url: "<?php echo base_url('annultransfert');?>",
                        data: {code_doc:code_doc},
                        dataType: "json",
                        success: function(data){
                            Swal.fire("Parfait!", data, 'success')
                            transfert_attente();
                            transfert_recu();
                        }
                    });
                }else{
                    Swal.fire('Les modifications ne sont pas enregistrées', '', 'info')
                }
            })

        })

        
        /**annule un transfert de stock fin */



    });
</script>