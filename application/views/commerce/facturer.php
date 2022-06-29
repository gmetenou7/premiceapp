</div>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    
    <?php $this->load->view('parts/subheader');?>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
            <!-- begin:: Content -->

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="kt-section">
                            <h3 class="kt-section__title">REGLEMENT TICKET</h3>
                            <div class="kt-section__info">Utilise les paramètres suivants pour faire un règlement ticket</div>
                                    
                            <div class="kt-widget__toolbar">
                                <button class="btn btn-icon btn-circle btn-label-facebook rtbtn">
                                    <i class="fa fa-eye"></i> 
                                </button> Historique
                            </div>

                            <form id="new_facture_form">

                                <div class="kt-section__content kt-section__content--border">
                                        <?php $this->load->view('parts/message'); ?>
                                    <div class="kt-portlet__foot">
                                        <div class="kt-form__actions">

                                            <div class="kt-portlet__foot">
                                                <div class="kt-form__actions">
                                                    <div class="row">
                                                        <div class="col-lg-4 ml-lg-auto">
                                                            <span class="form-text text-muted">Taxes</span>
                                                            <div class="form-group">
                                                                <div class="kt-checkbox-inline">
                                                                    <label class="kt-checkbox">
                                                                        <input type="checkbox" name="tva" id="tva" value="tva" class="tva"> TVA(19.25%)
                                                                        <span></span>
                                                                    </label>
                                                                    <span id="tva_error" class="text-danger"></span>
                                                                    <label class="kt-checkbox">
                                                                        <input type="checkbox" name="ir" id="ir" value="ir" class="ir"> IR(-2.2%)
                                                                        <span></span>
                                                                    </label>
                                                                    <span id="ir_error" class="text-danger"></span>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span class="form-text text-muted">Type de document</span>
                                                    <select class="form-control kt_select2 tdocument" id="kt_select2_1" name="tdocument">
                                                        <?php echo '<option></option>'; ?>
                                                        <?php if(!empty($docs)){ ?>
                                                            <?php foreach($docs as $value){?> 
                                                                <?php 
                                                                    if(set_value('tdocument')){
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
                                                <div class="col-md-6 ml-lg-auto">
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
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-lg-4 ml-lg-auto">
                                                    <span class="form-text text-muted">Depot</span>
                                                    <select class="form-control depot" id="depot" name="depot">
                                                       <?php if(!empty($depots)){ ?>
                                                            <?php foreach($depots as $value){?> 
                                                                <?php echo '<option value='.$value['mat_depot'].'>'.$value['nom_depot'].'</option>'; ?>
                                                            <?php }?> 
                                                        <?php }?> 
                                                    </select>
                                                    <span id="depot_error" class="text-danger"></span>
                                                </div>
                                                <div class="col-lg-4 ml-lg-auto">
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
                                                <div class="col-lg-4 ml-lg-auto">
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
                                        </div>
                                    </div>

                                    <div class="kt-portlet__foot">
                                        <div class="kt-form__actions">
                                            <div class="row">
                                                <div class="col-lg-6 ml-lg-auto">
                                                    <span class="form-text text-muted">Document</span>
                                                    <select class="form-control document kt-select2" id="kt_select2_3" name="document">
                                                        <optgroup label="Nouveau Document" id="ndoc">
                                                        </optgroup>
                                                        <optgroup label="Document Non Cloturer" id="exdoc">
                                                        </optgroup>       
                                                    </select>
                                                    <span id="document_error" class="text-danger"></span>
                                                </div>
                                                <div class="col-lg-5 ml-lg-auto">
                                                    <span class="form-text text-muted">Article</span>
                                                    <select class="form-control kt-select2 article" id="kt_select2_8" name="article">
                                                        <?php if(!empty($articles)){ ?>
                                                            <?php echo '<option></option>'; ?>
                                                            <?php foreach($articles as $key=>$values){?> 
                                                                <?php echo '<option value='.$values['matricule_art'].'>'.$values['code_bar'].' / '.$values['designation'].' / '.$values['reference'].'</option>'; ?>
                                                            <?php }?> 
                                                        <?php }?>
                                                    </select>
                                                    <span id="article_error" class="text-danger"></span>
                                                </div>
                                                <div class="col-lg-1 ml-lg-auto">
                                                    <span class="form-text text-muted">...</span>
                                                    <button type="submit" id="new_facture_btn" class="btn btn-dark">OK</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr> 
                                    <span id="message" class="text-danger"></span>                         
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--end::Portlet-->

                <!--begin::Portlet-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        <div class="kt-section">
                                
                            <div class="kt-section__content kt-section__content--border">
                               
                                <div class="table-responsive">
                                    <input type="hidden" name="tva_docu" id="tva_docu" class="tva_docu">
                                    <input type="hidden" name="ir_docu" id="ir_docu" class="ir_docu">
                                    <div id="art_doc">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Portlet-->


            <!-- end:: Content -->				

        </div>

    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->



<!-- affiche l'article a ajouter au panier debut-->
<div class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true" id="article_modal">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form id="op_facture_form">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Article</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="tva_doc" id="tva_doc" class="tva_doc">
                <input type="hidden" name="ir_doc" id="ir_doc" class="ir_doc">
                <input type="hidden" name="t_doc" id="t_doc" class="t_doc">
                <input type="hidden" name="dep" id="dep" class="dep">
                <input type="hidden" name="cli" id="cli" class="cli">
                <input type="hidden" name="caiss" id="caiss" class="caiss">
                <input type="hidden" name="vendeur" id="vendeur" class="vendeur">
                <input type="hidden" name="doc_m" id="doc_m" class="doc_m">
                <input type="hidden" name="art" id="art" class="art">
                

                <div class="table-responsive">
                    <table class="table table-striped m-table">
                        <thead>
                            <tr>
                                <th>Code à barre</th>
                                <th>Désignation</th>
                                <th>Référence</th>
                                <th>Prix hors taxe</th>
                                <th>Quantité Actuel</th>
                                <th>Quantité</th>
                                <th>Prix Total Hors Taxe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="bar"></th>
                                <td class="design"></td>
                                <td class="ref"></td>
                                <td>
                                    <input type="text" name="prix_h" id="prix_h" class="prix_h"> <br>
                                    <span class="text-danger" id="prix_h_error"></span>
                                </td>
                                <td class="qte_act"></td>
                                <td>
                                    <input type="text" name="quantes" id="quantes" placeholder="0" class="quantes"> <br>
                                    <span class="text-danger" id="quantes_error"></span>
                                </td>
                                <td class="totalhorstaxe text-danger"></td>
                            </tr>
                            <tr>
                                <td colspan="7">
                                    <textarea class="form-control" id="description_art" name="description_art" rows="5" placeholder="ajouter la description du produit ici..."></textarea>
                                </td>
                            </tr>
                            <tr class="hideinfos">
                                <td colspan="7">
                                        <span id="messageshow" class="text-danger"></span>
                                        <span class='tex-muted'>Confirme ton statut</span>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="login" id="login" placeholder="email/nom/matricule chef d'agence">
                                                <span class="text-danger" id="login_error"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="password" class="form-control" name="pass" id="pass" placeholder="mot de passe">
                                                <span class="text-danger" id="pass_error"></span>
                                            </div
                                        </div>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Annuler et Fermer</button>
                <button type="submit" id="btn_op_facture_form" class="btn btn-outline-brand">Enrégistrer</button>
            </div>
        </div>
        </form>
    </div>
</div>
<!-- affiche l'article a ajouter au panier debut-->

<!-- liste des règlement ticket debut -->
<div class="modal fade" tabindex="-1" role="dialog" id="rt">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Liste des Factures au comptant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span class="text-muted"><smal>saisi le nom du client ou son code pour trouver sa facture</smal></span>
                <input type="search" name="recherche" id="recherche" class="form-control" placeholder="saisi ici le nom du client ou son code pour trouver sa facture">
                <span id="text"></span>
                <hr>
                <div class="list-group" id="tr_cli">

                </div>
                <hr>
                <span id="pagination"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">FERMER</button>
            </div>
        </div>
    </div>
</div>
<!-- liste des règlement ticket fin -->

<!-- liste des articles du règlement ticket debut -->
<div class="modal fade" tabindex="-1" role="dialog" id="ART_rt">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de la facture</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="art_rt_cli">
                
            </div>
            <div class="modal-footer" id="btn">
                
            </div>
        </div>
    </div>
</div>
<!-- liste des articles du règlement ticket fin -->

<script>
    $(document).ready(function () {

        /**afficher le nouveau document et les documents non cloturer */
        getdocs();
        function getdocs(){
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('getdocs')?>",
                dataType: "json",
                success: function(data){
                    //$("#kt_select2_3").append(data);
                    if(data.ndocu){
                        $("#ndoc").append(data.ndocu);
                    }
                    if(data.exdocu){
                        $("#exdoc").append(data.exdocu);
                    }  
                }
            });
        }
        
        /**affiche le formulaire pour ajouter un produit dans le panier debut*/
        $('#new_facture_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('form_facture'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#new_facture_btn').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        if(data.tva_error != ''){
                            $('#tva_error').html(data.tva_error);
                        }else{
                            $('#tva_error').html('');
                        }

                        if(data.ir_error != ''){
                            $('#ir_error').html(data.ir_error);
                        }else{
                            $('#ir_error').html('');
                        }

                        if(data.depot_error != ''){
                            $('#depot_error').html(data.depot_error);
                        }else{
                            $('#depot_error').html('');
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

                        if(data.commercial_error != ''){
                            $('#commercial_error').html(data.commercial_error);
                        }else{
                            $('#commercial_error').html('');
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

                        if(data.tdocument_error != ''){
                            $('#tdocument_error').html(data.tdocument_error);
                        }else{
                            $('#tdocument_error').html('');
                        }
                        
                    }

                    if(data.success){
                        $('#message').html(data.success);
                        $('#art_doc').html(data.art_doc);
                        $('#tva_error').html('');
                        $('#ir_error').html('');
                        $('#tdocument_error').html('');
                        $('#depot_error').html('');
                        $('#client_error').html('');
                        $('#caisse_error').html('');
                        $('#commercial_error').html('');
                        $('#document_error').html('');
                        $('#article_error').html('');

                        //$('#accordpayement_error').html('');
                        //$('#new_facture_form')[0].reset();
                    }

                    if(data.form){
                        $('#tva_error').html('');
                        $('#ir_error').html('');
                        $('#tdocument_error').html('');
                        $('#depot_error').html('');
                        $('#client_error').html('');
                        $('#caisse_error').html('');
                        $('#commercial_error').html('');
                        $('#document_error').html('');
                        $('#article_error').html('');

                        $('#message').html(data.form);
                        $('#art_doc').html(data.art_doc);

                        $('.bar').html(data.article.code_bar);
                        $('.design').html(data.article.designation);
                        $('.ref').html(data.article.reference);
                        $('.qte_act').html(data.article.quantite);
                        $('.marge').html(data.article.pourcentage_marge);

                        $('.prix_h').val(data.article.prix_hors_taxe);
                        $('.art').val(data.article.matricule_art);
                        $('.tva_doc').val(data.autres.tva);
                        $('.ir_doc').val(data.autres.ir);
                        $('.t_doc').val(data.autres.tdoc);
                        $('.dep').val(data.autres.depot);
                        $('.cli').val(data.autres.client);
                        $('.caiss').val(data.autres.caisse);
                        $('.vendeur').val(data.autres.commercial);
                        $('.doc_m').val(data.autres.document);

                        /**ceci est utile lors de la suppression d'un article dans un document encours debut*/
                        $('.tva_docu').val(data.autres.tva);
                        $('.ir_docu').val(data.autres.ir);
                        /**ceci est utile lors de la suppression d'un article dans un document encours fin*/

                        $("#article_modal").modal("show");
                    }
                   $('#new_facture_btn').attr('disabled', false);
                }
            });
        });
        /**affiche le formulaire pour ajouter un produit dans le panier fin*/

        /**affiche le prix total hors taxe en fonction de la quantité debut */
        $(".quantes").keyup(function(){
            var qte = $(".quantes").val();
            var article = $(".art").val();
            var depot = $(".dep").val();
            var prixh = $(".prix_h").val();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('total_ht');?>",
                data: {qte:qte,article:article,depot:depot,prixh:prixh},
                dataType: "json",
                success: function(data){
                    $('.totalhorstaxe').html(data.total);
                }
            });
        });
        /**affiche le prix total hors taxe en fonction de la quantité fin */

        /**effectuer les opérations sur la facturation debut */
        $('#op_facture_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('op_facturetiket'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_op_facture_form').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        $('#message').html('');
                        $('#messageshow').html('');
                        if(data.quantes_error != ''){
                            $('#quantes_error').html(data.quantes_error);
                        }else{
                            $('#quantes_error').html('');
                        }
                        if(data.prix_h_error != ''){
                            $('#prix_h_error').html(data.prix_h_error);
                        }else{
                            $('#prix_h_error').html('');
                        }
                        if(data.login_error != ''){
                            $('#login_error').html(data.login_error);
                        }else{
                            $('#login_error').html('');
                        }
                        if(data.pass_error != ''){
                            $('#pass_error').html(data.pass_error);
                        }else{
                            $('#pass_error').html('');
                        }
                    }
                    if(data.success){
                        $('#quantes_error').html('');
                        $('#prix_h_error').html('');
                        $("#article_modal").modal('hide');
                       //$("#art_doc").html(data.art_doc);
                        $('#message').html(data.success);
                        $('#art_doc').html(data.art_doc);
                        $('#messageshow').html('');
                        $('#login_error').html('');
                        $('#pass_error').html('');
                    }
                    if(data.show){
                        //$("#hideinfos").html(data.login);
                        $('#login_error').html('');
                        $('#pass_error').html('');
                        $('#messageshow').html(data.show);
                    }
                    $('#btn_op_facture_form').attr('disabled', false);
                }
            });
        });
        /**effectuer les opérations sur la facturation fin */



        /**supprimer un article dans un document debut */
        $(document).on("click", ".delete_art_fac", function(event){
            event.preventDefault();
            var code_art = $(this).attr('id');
            var code_doc = $("#doc").val();
            var code_depot = $('.depot').val();

            var ir_doc = $(".ir_docu").val();
            var tva_doc = $(".tva_docu").val();
            
            //var code_depot = $("#depott").val();

            swal.fire({
                title:"Es-tu sûr?",
                text: 'vous êtes sur le point de supprimer un article dans un document...',
                type:"warning",
                showCancelButton:!0,
                confirmButtonText: `Oui`,
            }).then(function(e){
                if(e.value){
                    $.ajax({
                        method: "POST",
                        url: "<?php echo base_url('delete_art_doc');?>",
                        data: {code_art:code_art,code_doc:code_doc,code_depot:code_depot,ir_doc:ir_doc,tva_doc:tva_doc},
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

        /**edit document debut */
        $(document).on("click", ".editdoc", function(event){
            event.preventDefault();
            var code_doc = $(this).attr('id');
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('edit_docs');?>",
                data: {code_doc:code_doc},
                dataType: "json",
                success: function(data){
                    $("#message").html(data.success);
                    $("#kt_select2_1").val(data.datas.code_type_doc);
                    $("#kt_select2_2").val(data.datas.code_client_document);
                    $("#depot").val(data.datas.depot_doc);
                    $("#caisse").val(data.datas.code_caisse);
                    //$("#commercial").val(data.datas.code_employe);
                    $("#kt_select2_3").val(data.datas.code_document);
                    if(data.tva != ""){
                        $("#tva").attr('checked',data.tva);  
                    }else{
                        $("#tva").attr('checked',false);
                    }
                    if(data.ir != ""){
                        $("#ir").attr('checked',data.ir);  
                    }else{
                        $("#ir").attr('checked',false);
                    }
                    $('#art_doc').html(data.art_doc);
                    $("#rt").modal('hide');
                }
            });
        })
        /**edit document debut */



        /**cloturer un document debut */
        $(document).on("click", ".closedoc", function(event){
            event.preventDefault();
            var doc = $(this).attr('id');
            
            //var code_depot = $("#depott").val();

            swal.fire({
                title:"Vous êtes sur le point de cloturer ce document !",
                text: 'une fois cela fait, le document ne sera plus modifiable. êtes vous sur de vouloir le faire?',
                type:"warning",
                showCancelButton:!0,
                confirmButtonText: `Oui`,
            }).then(function(e){
                if(e.value){
                    $.ajax({
                        method: "POST",
                        url: "<?php echo base_url('clo_doc');?>",
                        data: {doc:doc},
                        dataType: "json",
                        success: function(data){
                            $("#message").html(data.success);
                        }
                    });
                }else{
                    Swal.fire('Les modifications ne sont pas enregistrées', '', 'info')
                }
            })

        })
        /**cloturer un document debut */
        
        
        /**affiche la liste des règlement ticket debut */
        $(".rtbtn").click(function (e) { 
            e.preventDefault();
            reglementticket(1);
        });
        function reglementticket(page){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('all_rt_cli/');?>"+page,
                dataType: "json",
                success: function (data) {
                    $("#tr_cli").html(data.infos);
                    $("#pagination").html(data.pagination_link);
                    $("#rt").modal('show');
                }
            });
        }
        /**gerer le click sur la pagination article*/
        $(document).on("click", ".pagination li a", function(event){
            event.preventDefault();
            var page = $(this).data("ci-pagination-page");
            reglementticket(page);
        });
        /**selectionne tous les articles dans la base des données fin */
        /**affiche la liste des règlement ticket fin */

        /**affiche la liste des articles d'un règlement ticket debut */
        $(document).on("click", ".art_rt", function(event){
            var code_rt = $(this).attr('id');
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('all_art_rt_cli');?>",
                data: {code_rt:code_rt},
                dataType: "json",
                success: function (data) {
                    $("#art_rt_cli").html(data.infos);
                    $("#btn").html(data.link);
                    $("#ART_rt").modal('show');
                }
            });
        });
        /**affiche la liste des article d'un règlement ticket fin */
        
        /**rechercher une facture debut*/
        $("#recherche").keyup(function(){
            var rechercher = $("#recherche").val();
            if(rechercher !== ""){
                $('#text').html(rechercher);
                $.ajax({
                    method: "POST",
                    url: "<?php echo base_url('all_rt_cli');?>",
                    data: {rechercher:rechercher},
                    dataType: "json",
                    beforeSend:function(){
                        $('#tr_cli').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                        $('.fa-spin').addClass('active');
                    },
                    success: function(data){
                        $("#tr_cli").html(data.infos);
                        $('.has-spinner').removeAttr("disabled");
                    }
                });
            }else{
                $('#text').html("");
                reglementticket(1);
            }
        });
        /**rechercher une facture fin*/

        
    });
</script>