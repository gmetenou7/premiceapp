
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
                            <h3 class="kt-portlet__head-title">
                                Entré en stock (Facture achat fournisseur / Facture retour fournisseur)
                            </h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">

                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <span class="text-black">Historique d'entrer et retour fournisseur</span>
                                <hr>
                                <form id="form_historique">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="">Type de Documents</label>
                                            <select class="form-control kt-select2 type_doc_1" id="kt_select2_1" name="type_doc_1">
                                                <?php if(!empty($docs)):?>
                                                    <?php foreach ($docs as $key => $value):?>
                                                        <?php echo '<option value="'.$value['code_doc'].'">'.$value['code_doc'].' / '.$value['intiutle_doc'].' ('.$value['abrev_doc'].')</option>'; ?>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <?php echo '<option selected disabled>aucun document trouvé</option>'; ?>
                                                <?php endif ?>
                                            </select>
                                            <span class="text-danger" id="type_doc_1_error"></span>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Période</label>
                                            <div class="form-group row">
                                                <div class="col-lg-12 col-md-9 col-sm-12">
                                                    <div class="input-daterange input-group" id="kt_datepicker_5">
                                                        <input type="text" class="form-control start" name="start" id="start" placeholder="date de debut" />
                                                        <span class="text-danger" id="start_error"></span>

                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                                        </div>

                                                        <input type="text" class="form-control end" name="end" id="end" placeholder="date de fin"/>
                                                        <span class="text-danger" id="end_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">...</label>
                                            <button class="form-control btn btn-success" type="submit" id="btn_historique">Afficher</button>
                                        </div>
                                        
                                    </div>
                                </form>
                            </div>
                        <form id="form_new_stock">
                            <div class="kt-section__content kt-section__content--border">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Type Document</label>
                                        <select class="form-control kt-select2 type_doc_2 type_doc_2" id="kt_select2_2" name="type_doc_2">
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
                                                        }if($value['nom_depot'] == 'DEPOT MAGASIN'){
                                                            echo '<option value="'.$value['mat_depot'].'" selected>'.$value['mat_depot'].' / '.$value['nom_depot'].'</option>';
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
                                        <label for="">Fournisseur</label>
                                        <select class="form-control kt-select2 fournisseur" id="kt_select2_4" name="fournisseur">
                                            <?php if(!empty($fournisseurs)):?>
                                                <?php foreach ($fournisseurs as $key => $value):?>
                                                    <?php 
                                                        if(set_value('fournisseur') == $value['matricule_four']){
                                                            echo '<option value="'.set_value('fournisseur').'" selected>'.$value['matricule_four'].' / '.$value['nom_four'].'</option>'; 
                                                        }else{
                                                            echo '<option value="'.$value['matricule_four'].'">'.$value['matricule_four'].' / '.$value['nom_four'].'</option>';
                                                        }
                                                    ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <?php echo '<option selected disabled>aucun fournisseur trouvé</option>'; ?>
                                            <?php endif ?>
                                        </select>
                                        <span class="text-danger" id="fournisseur_error"></span>
                                    </div>
                                </div>
                               <hr>
                                <div class="row">
                                    <div class="col-md-5">
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
                                                            echo '<option value="'.$value['code_document'].'">'.$value['code_document'].' / '.$value['nom_document'].' / '.date('d-m-Y H:i:s',strtotime($value['date_creation_doc'])).'</option>'; 
                                                        ?>
                                                    <?php endforeach; ?>
                                                <?php endif ?>
                                            </optgroup>
                                        </select>
                                        <span class="text-danger" id="document_error"></span>
                                    </div>
                                    <div class="col-md-6">
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
                                                <?php echo '<option selected disabled>aucun fournisseur trouvé</option>'; ?>
                                            <?php endif ?>
                                        </select>
                                        <span class="text-danger" id="article_error"></span>
                                    </div>

                                    
                                    <div class="col-md-1">
                                        <label for="">...</label>
                                        <button type="submit" class="btn btn-primary" name="btn_form_stock">ok</button>
                                    </div>
                                </div>

                            </div>
                        </form>

                            

                        </div>

                    <hr class="text-primary">

                        <!-- begin:: Content -->
                    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                        <span id="message" class="text-danger"></span>

                        <hr>
                        <!--<div class="kt-section__content kt-section__content--border">
                            <h5>Facture d'achat Founiseur No: </h5>
                            <h5>date: </h5>

                            <div class="table-responsive">
                                <table class="table table-striped m-table">
                                    <thead>
                                        <tr>
                                            <th>Code à barre</th>
                                            <th>Désignation</th>
                                            <th>Référence</th>
                                            <th>Prix de révient</th>
                                            <th>Quantité Actuel</th>
                                            <th>Nouvelle Quantité</th>
                                            <th>Pourcentage de marge</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">...</th>
                                            <td>...</td>
                                            <td>...</td>
                                            <td>...</td>
                                            <td>...</td>
                                            <td>...</td>
                                            <td>...</td>
                                            <td><button class="btn btn-outline-hover-danger"><i class="fa fa-times"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <a href="#" class="badge badge-brand badge-bold badge-upper badge-font-sm badge-warning">
                                <i class="fa fa-print"></i>
                                Imprimer
                            </a>   
                            &nbsp;
                            <a href="#" class="badge badge-brand badge-bold badge-upper badge-font-sm badge-success">
                                <i class="fa fa-print"></i>
                            Enregistrer
                            </a> 
                            &nbsp;
                            <a href="#" class="badge badge-brand badge-bold badge-upper badge-font-sm badge-danger">
                                <i class="fa fa-times"></i>
                                Annuler la facture fournisseur
                            </a>

                        </div>-->
                            
                    </div>
                        <!-- end:: Content -->

			        </div>
		        </div>
		        <!--end::Portlet-->

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Facture achat fournisseur ou Facture retour fournisseur</h3>
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

    <?php $this->load->view('parts/footer');?>

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
            <form id="op_stock_form">
                <div class="modal-body">
                    <input type="hidden" name="art" id="art" class="art">
                    <input type="hidden" name="t_doc" id="t_doc" class="t_doc">
                    <input type="hidden" name="dep" id="dep" class="dep">
                    <input type="hidden" name="four" id="four" class="four">
                    <input type="hidden" name="doc_m" id="doc_m" class="doc_m">
                    

                    <div class="table-responsive">
                        <table class="table table-striped m-table">
                            <thead>
                                <tr>
                                    <th>Désignation</th>
                                    <th>Code à barre</th>
                                    <th>Référence</th>
                                    <th>Prix de révient</th>
                                    <th>Quantité Actuel</th>
                                    <th>Nouvelle Quantité</th>
                                    <th>Pourcentage de marge (%)</th>		
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="design"></td>
                                    <th class="bar"></th>
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
                    <button type="submit" id="btn_op_stock_form" class="btn btn-outline-brand">Enrégistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--modal mettre un article en stock fin -->

<!--modal affiche l'historique des documents debut -->
<div class="modal" tabindex="-1" role="dialog" id="modal_historique_stock">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Historique des Documents</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped m-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Fournisseur</th>
                                <th>Users</th>
                                <th>Dépot</th>
                                <th>date creation</th>
                                <th>Dernière Modification</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="content_doc">
                            
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
<!--modal affiche l'historique des documents fin  -->

<!--modal affiche la liste des article dans un document debut -->
<div class="modal fade" tabindex="-1" role="dialog" id="listartdoc">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Liste des articles du Document: <span id="name_doc"></span></h5>
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
                        <tbody id="artsdoc">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-outline-success" target="_blank">Imprimer</a>
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<!--modal affiche la liste des article dans un document fin -->


<!-- end:: Page -->


<script>
    $(document).ready(function () {

        /**afficher le formilaire d'entrer/srtie ou autre du stock debut*/
        $('#form_new_stock').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('show_form_stock'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_form_stock').attr('disabled', 'disabled');
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

                        if(data.fournisseur_error != ''){
                            $('#fournisseur_error').html(data.fournisseur_error);
                        }else{
                            $('#fournisseur_error').html('');
                        }

                        if(data.article_error != ''){
                            $('#article_error').html(data.article_error);
                        }else{
                            $('#article_error').html('');
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
                        $('#fournisseur_error').html('');
                        $('#article_error').html('');
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

                        $("#art_doc").html(data.art_doc);

                        $('.new_quantes').val('');

                        $("#modal_new_stock").modal('show');
                    }
                    $('#btn_form_stock').attr('disabled', false);
                }
            });
        });
        /**afficher le formilaire d'entrer/srtie ou autre du stock fin*/

        /**effectuer les opérations sur le stock debut */
        $('#op_stock_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('op_form_stock'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_op_stock_form').attr('disabled', 'disabled');
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
                        $("#art_doc").html(data.art_doc);
                        $('#message').html(data.success);
                    }
                    $('#btn_op_stock_form').attr('disabled', false);
                }
            });
        });
        /**effectuer les opérations sur le stock fin */

        /**afficher la liste des documents en fonction du type de document debut*/
        $(".type_doc_2").change(function(){
           var type_doc  = $(".type_doc_2").val();
           window.location = '<?php echo base_url('stock/')?>'+type_doc;
        });
        /**afficher la liste des documents en fonction du type de document fin*/
        

        /**j'affiche les documents en fonction du trype de document choisi et la période debut*/
        $('#form_historique').on('submit', function(event){
            event.preventDefault();

            $.ajax({
                method: "POST",
                url: "<?php echo base_url('show_docs_typdoc'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_historique').attr('disabled', 'disabled');
                    $('#btn_historique').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data){
                    if(data.error){
                        $('#message').html('');
                        if(data.type_doc_1_error != ''){
                            $('#type_doc_1_error').html(data.type_doc_1_error);
                        }else{
                            $('#type_doc_1_error').html('');
                        }

                        if(data.start_error != ''){
                            $('#start_error').html(data.start_error);
                        }else{
                            $('#start_error').html('');
                        }

                        if(data.end_error != ''){
                            $('#end_error').html(data.end_error);
                        }else{
                            $('#end_error').html('');
                        }
                        
                    }

                    if(data.success){
                        $('#type_doc_1_error').html('');
                        $('#start_error').html('');
                        $('#end_error').html('');
                        $('#message').html(data.success);
                    }
                    if(data.content){
                        $('#content_doc').html(data.content);
                        $("#modal_historique_stock").modal('show');
                    }
                    $('#btn_historique').attr('disabled', false);
                    $('#btn_historique').html("Afficher");
                }
            });
        });
        /**j'affiche les documents en fonction du trype de document choisi et la période fin*/

        /**affiche la liste des article d'un document debut*/
        $(document).on('click', '.artdoc', function(){
           var code_doc = $(this).attr("id");
           $.ajax({
                method: "POST",
                url: "<?php echo base_url('artsdoc'); ?>",
                data: {code_doc:code_doc},
                dataType: "JSON",
                success:function(data){
                    $("#artsdoc").html(data); 
                    $("a").attr("href", "<?php echo base_url('print/')?>"+code_doc);
                    $("#listartdoc").modal("show");
                }
            });
        });

        /**affiche la liste des article d'un document fin*/

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


