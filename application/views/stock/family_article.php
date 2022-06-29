
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
                            <h3 class="kt-portlet__head-title">Creer les familles d'article</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-outline-brand" data-toggle="modal" data-target=".bd-example-modal-xl">Nouvel famille</button>
                                <hr>

                                <!-- barre de recherche -->
                                <span class="form-text text-muted">saisi ici le nom ou le matricule de la famille pour la retrouver</span>
                                <input type="search" name="recherche" id="recherche" class="form-control" placeholder="saisi ici...">
                                <span id="text"></span>


                                <!-- nouvelle famille des produit debut -->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Creer Une famille d'article</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <!--begin::Form-->
                                            <form class="kt-form" id="form_famille">
                                                <div class="modal-body">
                                                    <div class="kt-portlet__body">
                                                        <span id="message"></span>
                                                        <div class="form-group">
                                                            <label>Nom de la famille</label>
                                                            <input type="text" class="form-control" id="nom" name="nom"  placeholder="nom famille produit">
                                                            <span class="form-text text-danger" id="nom_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" id="btn_save_famille" class="btn btn-outline-primary">Créer</button>
                                                </div>
                                            </form>
                                            <!--end::Form-->
                                        </div>
                                    </div>
                                </div>
                                <!-- nouvelle famille des produit fin -->


                                <!-- Large Modal detail d'une famille de produits debut -->
                                <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="detail_modal_fam">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Détails</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <span id="details_fam"></span>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Large Modal detail d'une famille de produits fin -->


                                <!-- modifier famille des produit debut -->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true" id="edit_modal">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modifier Une famille d'article</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <!--begin::Form-->
                                            <form class="kt-form" id="form_edit_famille">
                                                <div class="modal-body">
                                                    <div class="kt-portlet__body">
                                                        <span id="message1"></span>
                                                        <div class="form-group">
                                                            <input type="hidden" name="matricule" id="matricule" class="form-control">
                                                            <label>Nom de la famille</label>
                                                            <input type="text" class="form-control" id="nom1" name="nom1"  placeholder="nom famille produit">
                                                            <span class="form-text text-danger" id="nom1_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" id="btn_edit_famille" class="btn btn-outline-primary">Modifier</button>
                                                </div>
                                            </form>
                                            <!--end::Form-->
                                        </div>
                                    </div>
                                </div>
                                <!-- modifier famille des produit fin -->





                            </div>
                        </div>


                        <hr class="text-primary">
                        <div class="row" id="famille">
                           
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
    /**insertion dans la base des données*/
    $('#form_famille').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            method: "POST",
            url: "<?php echo base_url('save_famille'); ?>",
            data: $(this).serialize(),
            dataType: "json",
            beforeSend:function(){
                $('#btn_save_famille').attr('disabled', 'disabled');
                $('#btn_save_famille').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Encours...');
                $('.fa-spin').addClass('active');
            },
            success: function (data) {
                if(data.error){
                    $('#message').html('');
                    if(data.nom_error != ''){
                        $('#nom_error').html(data.nom_error);
                    }else{
                        $('#nom_error').html('');
                    }
                }
                if(data.success){
                    $('#nom_error').html('');

                    all_famille_produit();

                    $('#form_famille')[0].reset();
                    $('#message').html(data.success);
                }
                $('#btn_save_famille').attr('disabled', false);
                $('#btn_save_famille').html('Créer');
            }
        });
    });


    /**affiché la liste des familles de produit */
    all_famille_produit();
    function all_famille_produit(){
      $.ajax({
          method: "GET",
          url: "<?php echo base_url('all_famille'); ?>",
          dataType: "JSON",
            beforeSend:function(){
                $('#famille').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                $('.fa-spin').addClass('active');
            },
            success: function(data){
                $("#famille").html(data);
                $('.has-spinner').removeAttr("disabled");
            }
      });  
    }

    /**affiché les détailles d'une famille de produits*/
    $(document).on('click', '.detail_fam', function(){
        var famille_mat = $(this).attr("id");
        $.ajax({
            method: "POST",
            url: "<?php echo base_url('detail_famille'); ?>",
            data: {famille_mat:famille_mat},
            dataType: "JSON",
            success:function(data) {
                $("#details_fam").html(data); 
                $("#detail_modal_fam").modal("show");
            }
        });
    });


    /**modifier une famille de produits */
    $(document).on('click', '.edit', function(){
        var famille_mat = $(this).attr("id");
        $.ajax({
            method: "POST",
            url: "<?php echo base_url('edit_famille'); ?>",
            data: {famille_mat:famille_mat},
            dataType: "JSON",
            success:function(data){
                $("#nom1").val(data.nom_fam);
                $("#matricule").val(data.matricule_fam);
                $("#edit_modal").modal("show");
            }
        });
    });

    /**modifier une famille d'article */
    $('#form_edit_famille').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            method: "POST",
            url: "<?php echo base_url('update_famille'); ?>",
            data: $(this).serialize(),
            dataType: "json",
            beforeSend:function(){
                $('#btn_edit_famille').attr('disabled', 'disabled');
                $('#btn_edit_famille').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Encours...');
                $('.fa-spin').addClass('active');
            },
            success: function (data) {
                if(data.error){
                    $('#message1').html('');
                    if(data.nom1_error != ''){
                        $('#nom1_error').html(data.nom1_error);
                    }else{
                        $('#nom1_error').html('');
                    }
                }
                if(data.success){
                    $('#nom1_error').html('');

                    all_famille_produit();

                    $('#message1').html(data.success);
                }
                $('#btn_edit_famille').attr('disabled', false);
                $('#btn_edit_famille').html('Modifier');
            }
        });
    });

    /**rechercher une famille d'article */
    $("#recherche").keyup(function (e) { 
        var rechercher = $("#recherche").val();
        $("#text").html(rechercher);    
        if(rechercher !="") {
            $.ajax({
                method: "POST",
                data : {rechercher:rechercher},
                url: "<?php echo base_url('all_famille'); ?>",
                dataType: "JSON",
                beforeSend:function(){
                    $('#famille').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                    $('.fa-spin').addClass('active');
                },
                success: function(data){
                    $("#famille").html(data);
                    $('.has-spinner').removeAttr("disabled");
                }
            });
        }else{
            all_famille_produit(); 
        }
    });
        





</script>
