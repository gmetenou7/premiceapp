
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
                                    <h3 class="kt-portlet__head-title">Inventaire De Stock</h3>
                                </div>
                            </div>
                            <div class="kt-portlet__body">
                                <div class="kt-section">
                                    <div class="kt-section__content kt-section__content--border">
                                        <!--end::formulaire des paramètre pour commencer l'inventaire-->
                                        <form action="<?php echo base_url('inventaire');?>" method="POST">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="">Dépot</label>
                                                    <select class="form-control kt-select2 depot_doc" id="kt_select2_1" name="depot_doc">
                                                        <?php if(!empty($depots)):?>
                                                            <?php echo '<option></option>'; ?>
                                                            <?php foreach ($depots as $key => $value):?>
                                                                <?php 
                                                                    if(set_value('depot_doc') == $value['mat_depot']){
                                                                        echo '<option value="'.set_value('depot_doc').'" selected>'.$value['mat_depot'].' / '.$value['nom_depot'].'</option>'; 
                                                                    }else{
                                                                        echo '<option value="'.$value['mat_depot'].'">'.$value['mat_depot'].' / '.$value['nom_depot'].'</option>';
                                                                    }
                                                                ?>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <?php echo '<option selected disabled>aucun dépot trouvé</option>'; ?>
                                                        <?php endif ?>
                                                    </select>
                                                    <?php echo form_error('depot_doc', '<div class="text-danger">', '</div>'); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="">Famille</label>
                                                    <select class="form-control kt-select2 famille_doc" id="kt_select2_2" name="famille_doc">
                                                        <?php if(!empty($famille)):?>
                                                            <?php echo '<option></option>'; ?>
                                                            <?php foreach ($famille as $key => $value):?>
                                                                <?php 
                                                                    if(set_value('famille_doc') == $value['matricule_fam']){
                                                                        echo '<option value="'.set_value('famille_doc').'" selected>'.$value['matricule_fam'].' / '.$value['nom_fam'].'</option>'; 
                                                                    }else{
                                                                        echo '<option value="'.$value['matricule_fam'].'">'.$value['matricule_fam'].' / '.$value['nom_fam'].'</option>';
                                                                    }
                                                                ?>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <?php echo '<option selected disabled>aucune famille trouvé</option>'; ?>
                                                        <?php endif ?>
                                                    </select>
                                                    <?php echo form_error('famille_doc', '<div class="text-danger">', '</div>'); ?>
                                                </div>
                                                <div class="col-md-1">
                                                    <hr>
                                                    <button class="btn btn-primary" type="submit" name="btn_doc_form" id="btn_doc_form">ok</button>
                                                </div>
                                            </div>
                                        </form>
                                        <!--end::formulaire des paramètre pour commencer l'inventaire-->
                                    </div>

                                    <div class="kt-section__content kt-section__content--border">
                                        <!--end::formulaire des paramètre pour commencer l'inventaire-->
                                        <form action="" id="new_inventaire">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="">Type Document</label>
                                                    <select class="form-control kt-select2 type_doc_2" id="kt_select2_3" name="type_doc_2">
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
                                                    <select class="form-control kt-select2 depot" id="kt_select2_4" name="depot">
                                                        <?php if(!empty($depots)):?>
                                                            <?php echo '<option></option>'; ?>
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
                                                    <label for="">Famille</label>
                                                    <select class="form-control kt-select2 famille" id="kt_select2_7" name="famille">
                                                        <?php if(!empty($famille)):?>
                                                            <?php echo '<option></option>'; ?>
                                                            <?php foreach ($famille as $key => $value):?>
                                                                <?php 
                                                                    if(set_value('famille') == $value['matricule_fam']){
                                                                        echo '<option value="'.set_value('famille').'" selected>'.$value['matricule_fam'].' / '.$value['nom_fam'].'</option>'; 
                                                                    }else{
                                                                        echo '<option value="'.$value['matricule_fam'].'">'.$value['matricule_fam'].' / '.$value['nom_fam'].'</option>';
                                                                    }
                                                                ?>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <?php echo '<option selected disabled>aucun dépot trouvé</option>'; ?>
                                                        <?php endif ?>
                                                    </select>
                                                    <span class="text-danger" id="famille_error"></span>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="">Ajouter l'article dans un document</label>
                                                    <select class="form-control kt-select2 document" id="kt_select2_8" name="document">
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
                                                <div class="col-md-6">
                                                    <label for="">choisir l'article: Code à Barre / Désignation / Référence</label>
                                                    <select class="form-control kt-select2 article" id="kt_select2_9" name="article" id="article">
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
                                            <div class="col-md-1">
                                                <hr>
                                                <button class="btn btn-success" type="submit" id="btn_sub_form">ok</button>
                                            </div>
                                        </form>
                                        <!--end::formulaire des paramètre pour commencer l'inventaire-->
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
                            <!--end::Portlet-->
                        </div>
                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">Document D'inventaire</h3>
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
                    <!-- end:: Content -->
            </div>
        </div>

        <!--modal mettre un article en stock debut -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_inventaire">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="op_inventaire_form">
                <div class="modal-body">
                    <input type="hidden" name="t_doc" id="t_doc" class="t_doc">
                    <input type="hidden" name="dep" id="dep" class="dep">
                    <input type="hidden" name="fam" id="fam" class="fam">
                    <input type="hidden" name="doc_m" id="doc_m" class="doc_m">
                    <input type="hidden" name="art" id="art" class="art">
                    
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
                                    <th>en plus(+)</th>
                                    <th>en moin(-)</th>
                                    <th>Pourcentage de marge</th>		
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="bar"></th>
                                    <td class="design"></td>
                                    <td class="ref"></td>
                                    <td class="prix_r"></td>
                                    <td class="qte_art"></td>
                                    <td>
                                        <input type="text" name="new_quantes" id="new_quantes" placeholder="0" class="new_quantes"> <br>
                                        <span class="text-danger" id="new_quantes_error"></span>
                                    </td>
                                    <td class="plus"></td>
                                    <td class="moins"></td>
                                    <td class="marge"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn_op_inventaire_form" class="btn btn-outline-primary">Enrégistrer</button>
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
    $(document).ready(function () {
        /**afficher la liste des documents en fonction du type de document debut*/
        $(".type_doc_2").change(function(){
           var type_doc  = $(".type_doc_2").val();
           window.location = '<?php echo base_url('inventaire/')?>'+type_doc;
        });
        /**afficher la liste des documents en fonction du type de document fin*/


         /**afficher le formilaire d'entrer/sortie ou autre du stock debut*/
        $('#new_inventaire').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('inventaire_doc'); ?>",
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

                        if(data.famille_error != ''){
                            $('#famille_error').html(data.famille_error);
                        }else{
                            $('#famille_error').html('');
                        }

                        if(data.document_error != ''){
                            $('#document_error').html(data.document_error);
                        }else{
                            $('#document_error').html('');
                        }

                        if(data.article_error != ''){
                            $('#article_error').html(data.article_error);
                        }else{
                            $('#article_error').html('');
                        }
                        
                    }
                    if(data.success){
                        $('#message').html(data.success); 

                        $('#type_doc_2_error').html('');
                        $('#depot_error').html('');
                        $('#famille_error').html('');
                        $('#document_error').html('');
                        $('#article_error').html('');

                        $("#art_doc").html(data.art_doc);

                        $('.bar').html(data.article.code_bar);
                        $('.design').html(data.article.designation);
                        $('.ref').html(data.article.reference);
                        $('.prix_r').html(data.article.prix_revient);
                        $('.qte_art').html(data.article.quantite);
                        $('.marge').html(data.article.pourcentage_marge);

                        
                        $('.art').val(data.article.matricule_art);
                        $('.t_doc').val($('.type_doc_2').val());
                        $('.dep').val($('.depot').val());
                        $('.fam').val($('.famille').val());
                        $('.doc_m').val($('.document').val());
                        
                        $('.new_quantes').val('');
                        $('.plus').html('');
                        $('.moins').html('');

                        
                        
                        $("#modal_inventaire").modal('show');
                    }
                    if(data.message){
                        $('#message').html(data.message); 
                    }

                    $('#btn_sub_form').attr('disabled', false);
                }
            });
        });
        /**afficher le formilaire d'entrer/srtie ou autre du stock fin*/

        /**affiche si la quantité est en plus ou en moin */
        $(".new_quantes").keyup(function(){
            var qte = $(".new_quantes").val();
            var article = $(".art").val();
            var depot = $(".dep").val();

            $.ajax({
                method: "POST",
                url: "<?php echo base_url('plus_moins');?>",
                data: {qte:qte,article:article,depot:depot},
                dataType: "json",
                success: function(data){
                    $('.plus').html(data.plus);
                    $('.moins').html(data.moins);
                }
            });
        });

        /**operation sur le document d'inventaire */
         /**effectuer les opérations sur le stock debut */
        $('#op_inventaire_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('op_inventaire'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_op_inventaire_form').attr('disabled', 'disabled');
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

                        $("#art_doc").html(data.art_doc);

                        $("#modal_inventaire").modal('hide');
                        $('#message').html(data.success);
                    }
                    $('#btn_op_inventaire_form').attr('disabled', false);
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
