
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
                            <h3 class="kt-portlet__head-title">Configuration des messages</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-outline-brand" id="showmodalnrbmsg">Nombre Message</button>
                                        <hr class="text-primary">
                                        <span id="message"></span><hr>
                                        <span>Nb: seul la dernière config pour une entreprise est pris en compte</span>
                                        <span class="table-responsive">
                                            <table class="table">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Entreprise</th>
                                                        <th>Nombre Message</th>
                                                        <th>Creer Par</th>
                                                        <th>Date</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="nrbmsgs">
            
                                                </tbody>
                                            </table>
                                        </span>
                                


                           




                                <!-- Large Modal pour nombre de message debut-->
                                <div class="modal modalnbrmsg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Configurer le nombre de message</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="<?= base_url('nbr_msg');?>" method="post" id="newformnbrmsg">
                                            <div class="modal-body">
                                                    <span class="text-muted">saisi le nombre de messages limite à envoyé</span>
                                                    <input type="text" name="nbr_msg" id="nbr_msg" class="form-control" placeholder="saisi ici le nombre de message... exemple: 10...">
                                                    <span class="text-danger" id="nbr_msg_error"></span>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-danger" id="close">Annuler</button>
                                                <button type="submit" class="btn btn-outline-brand" id="btnnuewnbrmsg">Enrégistrer</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Large Modal pour nombre de message fin-->

                            </div>
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
    $(document).ready(function (){

        /**fermer le modal et vider le formulaire */
        $("#close").click(function (e) { 
            e.preventDefault();
            /**on vide puis on ferme le modal */
            $("#newformnbrmsg").trigger("reset");
            $(".modalnbrmsg").modal('hide');
        });

        /**ouvrir le modla */
        $("#showmodalnrbmsg").click(function (e) { 
            e.preventDefault();
            $(".modalnbrmsg").modal('show');
        });

        $('#newformnbrmsg').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btnnuewnbrmsg').attr('disabled', 'disabled');
                },
                success: function (data) {
                    if(data.error){
                        $('#message').html('');
                        if(data.nbr_msg_error != ''){
                            $('#nbr_msg_error').html(data.nbr_msg_error);
                        }else{
                            $('#nbr_msg_error').html('');
                        }
                    } 
                    if(data.success){
                        $('#nbr_msg_error').html('');
                        getallnrbmsg();
                        $('#message').html(data.success);
                        $("#newformnbrmsg").trigger("reset");
                        $(".modalnbrmsg").modal('hide');
                    }
                    $('#btnnuewnbrmsg').attr('disabled', false);
                }
            });
        });
        

        /**supprimer un "nombre de message" configurer*/
        $(document).on("click", ".deletenbrmsg", function(event){
            event.preventDefault();
            var id = $(this).attr('id');
            swal.fire({
                title:"Es-tu sûr?",
                text: 'vous êtes sur le point de supprimer cette config...',
                type:"warning",
                showCancelButton:!0,
                confirmButtonText: `Oui`,
            }).then(function(event){
                if(event.value){
                    $.ajax({
                        method:"POST",
                        url: "<?php echo base_url('delete_nbrmsg'); ?>",
                        data:{id:id},
                        dataType: "json",
                        success: function(data){
                            if(data.success){
                                getallnrbmsg();
                                $('#message').html(data.success);
                            }
                        }
                    });
                }else{
                    Swal.fire('Les modifications ne sont pas enregistrées', '', 'info')
                }
            })
        });

        /**liste le nombre de message */
        getallnrbmsg();
        function getallnrbmsg(){
            $.ajax({
                type: "GET",
                url: "<?= base_url('nbrmsg_list');?>",
                dataType: "json",
                beforeSend:function(){
                    $('#nrbmsgs').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Chargement...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data) {
                    if(data.success){
                        $("#nrbmsgs").html(data.success);   
                        $('#nrbmsgs').attr('disabled', false);
                    }
                }
            });
        }

        
        
    });
</script>