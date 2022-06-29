
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
                            <h3 class="kt-portlet__head-title">Administré les Mouvements d'entrée et de sortie</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <form action="" id="new_mvt">
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
                                            <label for="">Dépot</label>
                                            <select class="form-control kt-select2 depot" id="kt_select2_3" name="depot">
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
                                            <span class="text-danger" id="depot_error"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="">Motif</label>
                                            <input type="text" name="motif" id="motif" placeholder="motif" class="form-control motif">
                                            <span class="text-danger" id="motif_error"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Raison</label>
                                            <textarea name="raison" id="raison" cols="30" rows="1" placeholder="explique la raison ici" class="form-control raison"></textarea>
                                            <span class="text-danger" id="raison_error"></span>
                                        </div>
                                        <div class="col-md-6">
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
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
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
                                        <div class="col-md-4">
                                            <hr>
                                            <button class="btn btn-primary" type="submit" id="btn_sub_form">ok</button>
                                        </div>
                                    </div>
                                </form>




                            </div>
                        </div>


                        <hr class="text-primary">
                      


                        <!-- begin:: Content -->
                        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

                        <span id="message" class="text-danger"></span>    
                        ...	

                        </div>
                        <!-- end:: Content -->



			        </div>
		        </div>
		        <!--end::Portlet-->

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Mouvement d'entrer et de sorti</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <!--<div class="kt-separator kt-separator--space-lg kt-separator--border-dashed"></div>-->
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
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="art_doc">
                                </tbody>
                            </table>
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
            <form id="op_mvt_form">
                <div class="modal-body">
                    <input type="hidden" name="art" id="art" class="art">
                    <input type="hidden" name="t_doc" id="t_doc" class="t_doc">
                    <input type="hidden" name="dep" id="dep" class="dep">
                    <input type="hidden" name="motif_event" id="motif_event" class="motif_event">
                    <input type="hidden" name="raison_event" id="raison_event" class="raison_event">
                    <input type="hidden" name="doc_m" id="doc_m" class="doc_m">
                    

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
                    <button type="submit" id="btn_op_mvt_form" class="btn btn-outline-primary">Enrégistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--modal mettre un article en stock fin -->

    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->

<script>
    $(document).ready(function (){
        /**afficher le formilaire d'entrer/srtie ou autre du stock debut*/
        $('#new_mvt').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('show_form_mvt'); ?>",
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

                        if(data.depot_error != ''){
                            $('#depot_error').html(data.depot_error);
                        }else{
                            $('#depot_error').html('');
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
                        $('#depot_error').html('');
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

                        $('.art').val(data.article.matricule_art);
                        $('.t_doc').val($('.type_doc_2').val());
                        $('.dep').val($('.depot').val());
                        $('.four').val($('.fournisseur').val());
                        $('.doc_m').val($('.document').val());
                        $('.motif_event').val($('.motif').val());
                        $('.raison_event').val($('.raison').val());
                        
                        $('.new_quantes').val('');

                        $("#art_doc").html(data.art_doc);

                        $("#modal_new_stock").modal('show');
                    }

                    if(data.message){
                        $('#message').html(data.message);
                    }

                    
                    $('#btn_sub_form').attr('disabled', false);
                }
            });
        });
        /**afficher le formilaire d'entrer/srtie ou autre du stock fin*/

        /**afficher la liste des documents en fonction du type de document debut*/
        $(".type_doc_2").change(function(){
           var type_doc  = $(".type_doc_2").val();
           window.location = '<?php echo base_url('mouvement/')?>'+type_doc;
        });
        /**afficher la liste des documents en fonction du type de document fin*/

        /**effectuer les opérations sur le stock debut */
        $('#op_mvt_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('op_mvt_stock'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_op_mvt_form').attr('disabled', 'disabled');
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
                        $('#new_quantes_error').html('');
                        $("#modal_new_stock").modal('hide');
                        $('#message').html(data.success);
                        $("#art_doc").html(data.art_doc);
                    }
                    $('#btn_op_mvt_form').attr('disabled', false);
                }
            });
        });
        /**effectuer les opérations sur le stock fin */

        /**supprimer un article dans un document debut */
        $(document).on("click", ".delete_art", function(event){
            event.preventDefault();
            var code_art = $(this).attr('id');
            var code_doc = $("#doc").val();
            //var code_depot = $("#depott").val();
            var code_depot = $('.depot').val();
            
            swal.fire({
                title:"Es-tu sûr?",
                text: 'vous êtes sur le point de supprimer un article dans un document',
                type:"warning",
                showCancelButton:!0,
                confirmButtonText: `Oui`,
            }).then(function(e){
                if(e.value){
                    $.ajax({
                        method: "POST",
                        url: "<?php echo base_url('delete_art');?>",
                        data: {code_art:code_art,code_doc:code_doc,code_depot:code_depot},
                        dataType: "json",
                        success: function(data){
                            $("#art_doc").html(data.art_doc);
                            $("#message").html(data.success);
                        }
                    });
                }else{
                    Swal.fire('Les modifications ne sont pas enregistrées', '', 'info')
                }
            })

        })
        /**supprimer un article dans un document debut */
    });
</script>