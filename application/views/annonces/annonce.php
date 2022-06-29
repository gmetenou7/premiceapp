</div>

 <!-- Add custom CSS here -->
 <link rel="stylesheet" type="text/css" href="<?php echo assets_dir();?>multiselect/jquery.multiselect.css"/>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    
    <?php $this->load->view('parts/subheader');?>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

            <!-- begin:: Content -->


            <!-- begin:: Content -->
            <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                    <!--Begin::Inbox-->
            <div class="kt-grid kt-grid--desktop kt-grid--ver-desktop  kt-inbox" id="kt_inbox">
                <!--Begin::Aside Mobile Toggle-->
                <button class="kt-inbox__aside-close" id="kt_inbox_aside_close">
                    <i class="la la-close"></i>
                </button>
                <!--End:: Aside Mobile Toggle-->


                <!--Begin:: Inbox List-->
                <div class="kt-grid__item kt-grid__item--fluid    kt-portlet    kt-inbox__list kt-inbox__list--shown" id="kt_inbox_list">
                    <div class="kt-portlet__head">
                        <div class="kt-inbox__toolbar kt-inbox__toolbar--extended">
                            <div class="kt-inbox__actions kt-inbox__actions--expanded">
                                <div class="kt-inbox__panel">
                                    <button class="kt-inbox__icon" data-toggle="modal" data-target=".bd-example-modal-xl" title="Nouveau message">
                                        <i class="flaticon2-writing"></i>
                                    </button>
                                </div>
                                <!--****-->
                            </div>
                            <div class="kt-inbox__search">
                                <div class="input-group">
                                    <input type="text" id="rechercherdestinat" name="rechercherdestinat" class="form-control" placeholder="Cherche l'expediteur ou le msg dans la liste des messag.ici...">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="flaticon2-magnifier-tool"></i>
                                        </span>
                                    </div>
                                </div>
                                <span id="saisi" class="text-warning"></span>
                            </div>
                            <div class="kt-inbox__controls">
                                <div class="kt-inbox__pages" data-toggle="kt-tooltip" title="Records per page">
                                    <span class="kt-inbox__perpage" data-toggle="dropdown">1 - 50 of 235</span>
                                </div>

                                <button class="kt-inbox__icon kt-inbox__icon--sm" data-toggle="kt-tooltip" title="page précédente">
                                    <i class="flaticon2-left-arrow"></i>
                                </button>

                                <button class="kt-inbox__icon kt-inbox__icon--sm" data-toggle="kt-tooltip" title="page suivante">
                                    <i class="flaticon2-right-arrow"></i>
                                </button>

                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body kt-portlet__body--fit-x">

                        <!--begin::Portlet-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-widget kt-widget--general-2">
                                <div class="kt-portlet__body kt-portlet__body--fit" id="annonce">
                                    
                                </div>
                            </div>
                        </div>
                        <!--end::Portlet-->
                    </div>
                </div>
                <!--End:: Inbox List-->

                <!--Begin:: new message-->
                    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Nouveau Message</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <span id="message" class="text-danger"></span>
                                    <form id="newmsgform">
                                        <div class="form-group row">
                                            <label for="example-text-input" class="col-form-label col-lg-3 col-sm-12">Titre</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12">
                                                <input type="text" class="form-control" name="titrenewmsg" id="titrenewmsg" placeholder="saisir le titre ici...">
                                                <span id="titrenewmsg_error" class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="example-text-input" class="col-form-label col-lg-3 col-sm-12">Message</label>
                                            <div class="col-lg-9 col-md-9 col-sm-12">
                                                <textarea class="form-control" id="newmsg" name="newmsg" rows="3" placeholder="saisir le message ici..."></textarea>
                                                <span id="newmsg_error" class="text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-brand btn-outline-danger" data-dismiss="modal">Annuler & Fermer</button>
                                            <button type="submit" id="btnnewmsg" class="btn btn-outline-brand btn-outline-primary">Envoyer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                <!--End:: new message-->
                


                    </div>

            <!-- end:: Content -->
        </div>

    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->


<script>
    $(document).ready(function () {

        /**affiche le formulaire pour ajouter une nouvelle annonce*/
        $('#newmsgform').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('newmsgsave'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btnnewmsg').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        $('#message').html('');
                        if(data.newmsg_error != ''){
                            $('#newmsg_error').html(data.newmsg_error);
                        }else{
                            $('#newmsg_error').html('');
                        }
                        
                        if(data.titrenewmsg_error != ''){
                            $('#titrenewmsg_error').html(data.titrenewmsg_error);
                        }else{
                            $('#titrenewmsg_error').html('');
                        }
                    }
                    if(data.success){
                        $('#newmsg_error').html('');
                        $('#titrenewmsg_error').html('');
                        annonce();
                        $('#newmsgform')[0].reset();
                        $(".bd-example-modal-xl").modal('hide');
                        $('#message').html(data.success);
                    }
                   $('#btnnewmsg').attr('disabled', false);
                }
            });
        });
        /**affiche le formulaire pour ajouter une nouvelle annonce fin*/
        
        annonce();
        function annonce(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('getmsgsave'); ?>",
                dataType: "json",
                success: function (data){
                    if(data.success){
                        $('#annonce').html(data.success);
                    }
                }
            });  
        }
        
        
        
        /****supprimer une annonce***/
        $(document).on('click', '.delannonce', function(){
            var codeann = $(this).attr("id");
            
            Swal.fire({
                title: 'êtes vous sur?',
                text: "de vouloir supprimer cette annonce?",
                type:"warning",
                showCancelButton:!0,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Annuler',
                confirmButtonText: 'oui'
            }).then(function(e){
                if (e.value) {
                    $.ajax({
                        method:"POST",
                        url: "<?php echo base_url('delannonce'); ?>",
                        data:{codeann:codeann},
                        dataType: "json",
                        success: function(data){
                            if(data.success == 'ok'){
                                
                            }else{
                                Swal.fire(
                                  'SUPPRIMER!',
                                  data.success,
                                  'success'
                                )
                            }  
                        }
                    });
                }
            })

        });


        $("#rechercherdestinat").keyup(function (e) { 
            var inputrecherche = $("#rechercherdestinat").val();
            $("#saisi").html(inputrecherche);
        });


    });
</script>