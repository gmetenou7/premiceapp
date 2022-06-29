
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
                            <h3 class="kt-portlet__head-title">Configuration des Point pour un prix</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">

                            <div class="row">
                                 <!--begin::Portlet-->
                                    <div class="kt-portlet">
                                        <div class="kt-portlet__body">
                                            <div class="kt-section">
                                                <div class="kt-section__info">
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-outline-brand" data-toggle="modal" data-target=".configpoint">Nouvelle Config</button>
                                                    <hr>
                                                    dans ce tableau nous avons la liste des configurations. seul la dernière configuration est pris en compte
                                                    <span id="message1"></span>
                                                </div>
                                                <div class="kt-section__content kt-section__content--border">
                                                    <ul class="list-group" id="config">
                                                        
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Portlet-->
                            </div>
                                


                           




                                <!-- Large Modal -->
                                <div class="modal fade configpoint" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Prix du point</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <span id="message"></span>
                                                <form id="formpoint">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="inputprixpoint" class="form-label">Prix du point</label>
                                                            <input type="text" id="prixpoint" name="prixpoint" class="form-control" placeholder="saisi ici le prix du point">
                                                            <div id="prixpoint_error" class="form-text text-danger"></div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="inputPassword5" class="form-label">Nombre de point</label>
                                                            <input type="text" id="nbrpoint" name="nbrpoint" class="form-control" placeholder="saisi ici le nombre point">
                                                            <div id="nbrpoint_error" class="form-text text-danger"></div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <button type="submit" id="btn_point" class="btn btn-outline-brand">Enregistrer</button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <hr class="text-primary">
                       
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

    

        /**inscrer l'agence dans la base des donnéé */
        $('#formpoint').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('point_new'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_point').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        $('#message').html('');
                        if(data.prixpoint_error != ''){
                            $('#prixpoint_error').html(data.prixpoint_error);
                        }else{
                            $('#prixpoint_error').html('');
                        }

                        if(data.nbrpoint_error != ''){
                            $('#nbrpoint_error').html(data.nbrpoint_error);
                        }else{
                            $('#nbrpoint_error').html('');
                        }
                    }
                    if(data.success){
                        $('#prixpoint_error').html('');
                        $('#nbrpoint_error').html('');
                        
                        get_pointprix();
                        
                        $('#message').html(data.success);
                    }
                    
                    $('#btn_point').attr('disabled', false);

                }
            });
        });


        /**afficher les agences */
        get_pointprix();
        function get_pointprix(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('get_pointprix'); ?>",
                dataType: "json",
                success: function (data){
                    $("#config").html(data);
                }
            });
        }


        
         /**supprimer une sorti de caisse debut */
        $(document).on("click", ".deletepoint", function(event){
            event.preventDefault();
            var mat_point = $(this).attr('id');
            swal.fire({
                title:"Es-tu sûr?",
                text: 'vous êtes sur le point de supprimer cette config...',
                type:"warning",
                showCancelButton:!0,
                confirmButtonText: `Oui`,
            }).then(function(e){
                if(e.value){
                    $.ajax({
                        method:"POST",
                        url: "<?php echo base_url('delete_point'); ?>",
                        data:{mat_point:mat_point},
                        dataType: "json",
                        success: function(data){
                            if(data.success){
                                get_pointprix();
                                $('#message1').html(data.success);
                            }
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

