
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
                            <h3 class="kt-portlet__head-title">Gestion des Documents</h3>
                            
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-outline-brand" id="btn_modal">Nouveau document</button>
                                <!-- debut Modal création d'un type de document debut-->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true" id="new_doc_modal">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="name_heade">Creer Un type de document</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="doc_form">
                                                <div class="modal-body">
                                                    <div class="kt-portlet__body">
                                                        <!--debut message-->
                                                        <span id="message"></span>
                                                        <!--fin message-->

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Libelle</label>
                                                                    <input type="text" class="form-control" id="libel_doc" name="libel_doc" placeholder="nom complet du documnet">
                                                                    <span class="form-text text-danger" id="libel_doc_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Abreviation</label>
                                                                    <input type="text" class="form-control" name="ab_doc" id="ab_doc" placeholder="abreviation du document">
                                                                    <span class="form-text text-danger" id="ab_doc_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Catégorie</label>
                                                                    <select name="categorie" id="categorie" class="form-control">
                                                                        <?php if(!empty($categorie)){?>
                                                                            <?php echo '<option selected disabled>catégorie de document</option>'; ?>
                                                                            <?php foreach($categorie as $value){?>
                                                                                <?php echo '<option value="'.$value.'">'.$value.'</option>'; ?>
                                                                            <?php }?>
                                                                        <?php }?>
                                                                    </select>
                                                                    <span class="form-text text-danger" id="categorie_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Form-->
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" id="btn_save_doc" class="btn btn-outline-brand">Enregistrer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!--fin modal creation d'un type de document fin-->

                                 <!-- debut Modal modification d'un type de document debut-->
                                 <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true" id="edit_doc_modal">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="name_heade">Modifier Un type de document</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="doc_form_update">
                                                <input type="hidden" name="matricule" id="matricule">
                                                <div class="modal-body">
                                                    <div class="kt-portlet__body">
                                                        <!--debut message-->
                                                        <span id="message1"></span>
                                                        <!--fin message-->

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Libelle</label>
                                                                    <input type="text" class="form-control" id="libel_doc1" name="libel_doc1" placeholder="nom complet du documnet">
                                                                    <span class="form-text text-danger" id="libel_doc1_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Abreviation</label>
                                                                    <input type="text" class="form-control" name="ab_doc1" id="ab_doc1" placeholder="abreviation du document">
                                                                    <span class="form-text text-danger" id="ab_doc1_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Catégorie</label>
                                                                    <select name="categorie1" id="categorie1" class="form-control">
                                                                        <?php if(!empty($categorie)){?>
                                                                            <?php echo '<option selected disabled>catégorie de document</option>'; ?>
                                                                            <?php foreach($categorie as $value){?>
                                                                                <?php echo '<option value="'.$value.'">'.$value.'</option>'; ?>
                                                                            <?php }?>
                                                                        <?php }?>
                                                                    </select>
                                                                    <span class="form-text text-danger" id="categorie1_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end::Form-->
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" id="btn_update_doc" class="btn btn-outline-brand">Modifier</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!--fin modal modification d'un type de document fin-->





                            </div>
                        </div>


                        <hr class="text-primary">
                        <!--liste des agences-->
                        <div class="row" id="docs">
                            
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
<!-- end:: Page -->


<script>
    $(document).ready(function(){

        /**affiche le modal*/
        $("#btn_modal").click(function (e) { 
            e.preventDefault();
            $("#new_doc_modal").modal('show');
        });
   

        /**ajouter un nouveau document dans la base des données */
        $('#doc_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('doc_new'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_save_doc').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        if(data.libel_doc_error != ''){
                            $('#libel_doc_error').html(data.libel_doc_error);
                        }else{
                            $('#libel_doc_error').html('');
                        }

                        if(data.ab_doc_error != ''){
                            $('#ab_doc_error').html(data.ab_doc_error);
                        }else{
                            $('#ab_doc_error').html('');
                        }

                        if(data.categorie_error != ''){
                            $('#categorie_error').html(data.categorie_error);
                        }else{
                            $('#categorie_error').html('');
                        }

                        
                    }
                    if(data.success){
                        $('#libel_doc_error').html('');
                        $('#ab_doc_error').html('');
                        $('#categorie_error').html('');

                        $('#doc_form')[0].reset();
                        $('#message').html(data.success);

                        /**affiche les documents*/
                        get_doc();
                    }
                    
                    $('#btn_save_doc').attr('disabled', false);

                }
            });
        });


        /**afficher les agences */
        get_doc();
        function  get_doc(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('get_doc'); ?>",
                dataType: "json",
                success: function (data) {
                    $("#docs").html(data);
                }
            });
        }


        /**afficher les informations dans le formulaire avant modification */
        $(document).on('click', '.edit_doc', function(){
            var mat_doc = $(this).attr("id");
            $.ajax({
                method:"POST",
                url: "<?php echo base_url('get_single_doc'); ?>",
                data:{mat_doc:mat_doc},
                dataType: "json",
                success: function(data){
                    $("#matricule").val(data.code_doc);
                    $("#libel_doc1").val(data.intiutle_doc);
                    $("#ab_doc1").val(data.abrev_doc);
                    $("#categorie1").val(data.categorie_doc);
                }
            }); 
            
            $("#edit_doc_modal").modal('show');
        });


        /**modifier les inforamations d'un document */
        $('#doc_form_update').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('doc_update'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_update_doc').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        if(data.libel_doc1_error != ''){
                            $('#libel_doc1_error').html(data.libel_doc1_error);
                        }else{
                            $('#libel_doc1_error').html('');
                        }

                        if(data.ab_doc1_error != ''){
                            $('#ab_doc1_error').html(data.ab_doc1_error);
                        }else{
                            $('#ab_doc1_error').html('');
                        }

                        if(data.categorie1_error != ''){
                            $('#categorie1_error').html(data.categorie1_error);
                        }else{
                            $('#categorie1_error').html('');
                        }
                    }
                    if(data.success){
                        $('#libel_doc1_error').html('');
                        $('#ab_doc1_error').html('');
                        $('#categorie1_error').html('');

                        $('#message1').html(data.success);

                        /**affiche les documents*/
                        get_doc();
                    }
                    
                    $('#btn_update_doc').attr('disabled', false);

                }
            });
        });







    });
</script>