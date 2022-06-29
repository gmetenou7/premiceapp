</div>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    
    <?php $this->load->view('parts/subheader');?>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
            <!-- begin:: Content -->

            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__body">
                    <div class="kt-section">
                        <h3 class="kt-section__title">BON RETOUR</h3>
                        <div class="kt-section__info">utilise les paramètres suivants pour faire un bon de retour</div>

                        <div class="kt-widget__toolbar">
                            <button class="btn btn-icon btn-circle btn-label-facebook brbtn">
                                <i class="fa fa-eye"></i> 
                            </button> Historique
                        </div>

                        <div class="kt-section__content kt-section__content--border">
                            <?php $this->load->view('parts/message');?>
                            <div class="kt-portlet__foot">
                                <div class="kt-form__actions">
                                    <form method="post" action="<?php echo base_url('all_art_formbr'); ?>" id="idformsubmitbr">
                                        <div class="row">
                                            <div class="col-lg-6 ml-lg-auto">
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
                                                <b class="text-danger" id="tdocument_error"></b>
                                                <?php //echo form_error('tdocument', '<div class="text-danger">', '</div>'); ?>
                                            </div>

                                            <div class="col-lg-6 ml-lg-auto">
                                                <span class="form-text text-muted">Document</span>
                                                <select class="form-control document" id="document" name="document">
                                                    <?php if(!empty($code_doc)){ ?>
                                                        <?php foreach($code_doc as $key=>$values){?> 
                                                            <?php echo '<option value='.$key.'>'.$values.'</option>'; ?>
                                                        <?php }?> 
                                                    <?php }?>
                                                </select>
                                                <b class="text-danger" id="document_error"></b>
                                                <?php //echo form_error('document', '<div class="text-danger">', '</div>'); ?>
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-4 ml-lg-auto">
                                                <span class="form-text text-muted">Commercial / Vendeur</span>
                                                <select class="form-control commercial" id="commercial" name="commercial">
                                                    <?php if(!empty($vendeur)){ ?>
                                                        <?php foreach($vendeur as $key=>$values){?> 
                                                            <?php echo '<option value='.$values['matricule'].'>'.$values['nom'].'</option>'; ?>
                                                        <?php }?> 
                                                    <?php }?> 
                                                </select>
                                                <b class="text-danger" id="commercial_error"></b>
                                                <?php //echo form_error('commercial', '<div class="text-danger">', '</div>'); ?>
                                            </div>

                                            <div class="col-lg-7 ml-lg-auto">
                                                <span class="form-text text-muted">Facture</span>
                                                <select class="form-control kt_select2 facture" id="kt_select2_3" name="facture">
                                                    <?php echo '<option></option>'; ?>
                                                    <?php if(!empty($facture)){ ?>
                                                        <?php foreach($facture as $value){?> 
                                                            <?php 
                                                                if(set_value('facture') == $value['code_document']){
                                                                    echo '<option value="'.set_value('facture').'" selected>'.$value['code_document'].' ---> '.$value['nom_cli'].' ---> '.$value['pt_ttc_document'].'</option>'; 
                                                                }else{
                                                                    echo '<option value='.$value['code_document'].'>'.$value['code_document'].' ---> '.$value['nom_cli'].' ---> '.$value['pt_ttc_document'].'</option>';
                                                                }   
                                                            ?>
                                                        <?php }?> 
                                                    <?php }?> 
                                                </select>
                                                <b class="text-danger" id="facture_error"></b>
                                                <?php //echo form_error('facture', '<div class="text-danger">', '</div>'); ?>
                                            </div>

                                            <div class="col-lg-1 ml-lg-auto">
                                                <span class="form-text text-muted">...</span>
                                                <button type="submit" name="new_bon_retour" id="new_bon_retour" class="btn btn-dark">OK</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                            </div>

                            <hr>
                            <b class="text-danger" id="message"></b>
                            <hr>
                            <form id="form_br_submit_op" class="form_br_submit_op" name="form_br_submit_op">
                                <span id="art_doc"></span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->

               
            <!-- liste des des bon de retour debut -->
            <div class="modal fade" tabindex="-1" role="dialog" id="br">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Liste des bons de retour</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            
                            <input type="search" id="recherche" name="recherche" class="form-control" placeholder="saisi ici le nom du client pour trouver ses retours...">
                            <span id="text"></span>
                            <hr>
                            
                            <div class="list-group" id="br_cli">

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
            <!-- liste des des bon de retour fin -->


            <!-- liste des articles du règlement ticket debut -->
            <div class="modal fade" tabindex="-1" role="dialog" id="ART_br">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">ARTICLES</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="art_br_cli">
                            
                        </div>
                        <div class="modal-footer" id="btn">
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- liste des articles du règlement ticket fin -->

<script>
    $(document).ready(function () {
        
        /**affiche la liste des bon de retour debut */
        $(".brbtn").click(function (e){ 
            e.preventDefault();
            bonde_retour(1);
        });
        function bonde_retour(page){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('allbonretour/');?>"+page,
                dataType: "json",
                success: function (data){
                    $("#br_cli").html(data.infos);
                    $("#pagination").html(data.pagination_link);
                    $("#br").modal('show');
                }
            });
        }
        /**gerer le click sur la pagination article*/
        $(document).on("click", ".pagination li a", function(event){
            event.preventDefault();
            var page = $(this).data("ci-pagination-page");
            bonde_retour(page);
        });
        /**selectionne tous les articles dans la base des données fin */
        
        
        /**affiche la liste des bon de retourn fin */

        /**affiche la liste des articles d'un règlement ticket debut */
        $(document).on("click", ".art_br", function(event){
            var code_rt = $(this).attr('id');
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('all_art_br');?>",
                data: {code_rt:code_rt},
                dataType: "json",
                success: function (data) {
                    $("#art_br_cli").html(data.infos);
                    $("#btn").html(data.link);
                    $("#ART_br").modal('show');
                }
            });
        });
        /**affiche la liste des article d'un règlement ticket fin */
        
        
        /**rechercher une facture debut*/
        $("#recherche").keyup(function(){
            var rechercher = $("#recherche").val();
            if(rechercher !==""){
                $('#text').html(rechercher);
                $.ajax({
                    method: "POST",
                    url: "<?php echo base_url('allbonretour');?>",
                    data: {rechercher:rechercher},
                    dataType: "json",
                    success: function(data){
                        $("#br_cli").html(data.infos);
                    }
                });
            }else{
                $('#text').html("");
                bonde_retour(1);
            }
        });
        /**rechercher une facture fin*/
        

        /**soumettre le formulaire pour afficher les articles a retourner d'un document debut */
        $('#idformsubmitbr').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#new_bon_retour').attr('disabled', 'disabled');
                },
                success: function(data) {
                    if(data.error){
                        if(data.tdocument_error != ''){
                            $('#tdocument_error').html(data.tdocument_error);
                        }else{
                            $('#tdocument_error').html('');
                        }

                        if(data.document_error != ''){
                            $('#document_error').html(data.document_error);
                        }else{
                            $('#document_error').html('');
                        }

                        if(data.commercial_error != ''){
                            $('#commercial_error').html(data.commercial_error);
                        }else{
                            $('#commercial_error').html('');
                        }

                        if(data.facture_error != ''){
                            $('#facture_error').html(data.facture_error);
                        }else{
                            $('#facture_error').html('');
                        }
                    }
                    if(data.success){
                        $('#tdocument_error').html('');
                        $('#document_error').html('');
                        $('#commercial_error').html('');
                        $('#facture_error').html('');
                        $('#message').html(data.success);
                        $('#art_doc').html(data.art_doc);
                    }
                    $('#new_bon_retour').attr('disabled', false);
                }
            });
        });
        /**soumettre le formulaire pour afficher les articles a retourner d'un document fin */
        
        /**cocher decocher */
        $(document).on('click', '.mt-checkbox-list', function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        }); 


        /**effectuer un retour*/
        $(document).on("click", "#retour_artopbtn", function(event) {
            event.preventDefault();
            var docs = $("#docs").val();
            var matdocret = $("#matdocret").val();
            var typedoc = $("#typedoc").val();
            //var returnlist = $("input[name='returnlist']").val();
            var returnlist = $("[name^='returnlist']:checked").map(function(){return $(this).val();}).get();
            $("#returnlist").val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('opbr');?>",
                data: {docs:docs,matdocret:matdocret,returnlist:returnlist,typedoc:typedoc},
                dataType: "json",
                beforeSend:function(){
                    $('#retour_artopbtn').attr('disabled', 'disabled');
                },
                success: function(data){
                    if(data.success){
                        $('#message').html(data.success);
                        toastr["success"](data.success);
                        setTimeout(function(){
                            window.location.reload();
                        }, 5000);
                    } 
                    $('#retour_artopbtn').attr('disabled', false);
                }
            });
        });

        

        /**annuler un bon de retour debut */
        $(document).on("click", ".editdocbr", function(event){
            event.preventDefault();
            var code_doc = $(this).attr('id');
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('edit_docs');?>",
                data: {code_doc:code_doc},
                dataType: "json",
                success: function(data){
                    $("#message").html(data.success);
                    /*$("#kt_select2_1").val(data.datas.code_type_doc);
                    $("#kt_select2_2").val(data.datas.code_client_document);
                    $("#depot").val(data.datas.depot_doc);
                    $("#caisse").val(data.datas.code_caisse);
                    //$("#commercial").val(data.datas.code_employe);
                    $("#kt_select2_3").val(data.datas.code_document);
                    $("#delais").val(data.datas.delais_reg_doc);
                    if(data.tva != ""){
                        $("#tva").attr('checked',data.tva);  
                    }else{
                        $("#tva").attr('checked',false);
                    }
                    if(data.ir != ""){
                        $("#ir").attr('checked',data.ir);  
                    }else{
                        $("#ir").attr('checked',false);
                    }*/
                    $('#art_doc').html(data.art_doc);
                    $("#br").modal('hide');
                }
            });
        })


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
                text: 'vous êtes sur le point d\'annuler le retour de cet article...',
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
        

 
        
    });
</script>





















            <!-- end:: Content -->				

        </div>

    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->

