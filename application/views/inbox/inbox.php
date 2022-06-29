
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
                            <!--<div class="kt-inbox__search">
                                <div class="input-group">
                                    <input type="text" id="rechercherdestinat" name="rechercherdestinat" class="form-control" placeholder="Rechercher un destinataire dans la liste des message ici...">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="flaticon2-magnifier-tool"></i>
                                        </span>
                                    </div>
                                </div>
                                <span id="saisi" class="text-warning"></span>
                            </div>-->
                            <div class="kt-inbox__controls">
                                <div class="kt-inbox__pages">
                                    <span class="kt-inbox__perpage" id="pagination"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body kt-portlet__body--fit-x">

                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <!--<div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">MESSAGES</h3>
                                </div>
                            </div>-->
                            <div class="kt-portlet__body">
                                <div class="kt-section">
                                    <!--<div class="kt-section__info">
                                        ...
                                    </div>-->
                                    <div class="kt-section__content kt-section__content--border">
                                        <div class="list-group" id="sendmessages">
                                            
                                        </div>
                                    </div>
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
                        <form id="newgroupemessageform">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Destinataire</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <span class="text-warninng">
                                        NB: lorsque vous cliquerez sur <b>tout coché</b> patientez que le système termine de cocher
                                        le temps mis sera fonction de la quantité des données. <b>pour tout décocher il faut cliquer sur tout coché</b>
                                    </span>
                                    <select class="form-control kt_selectpicker dest" data-actions-box="true" name="destinataire[]" id="destinataire" multiple> <!-- id="langOpt3" multiple -->
                                        <?php if(!empty($clients)){ ?>
                                            <?php foreach($clients as $values){?> 
                                                <?php echo '<option value='.$values['matricule_cli'].'>'.strtoupper($values['nom_cli']).'</option>'; ?>
                                            <?php }?> 
                                        <?php }?>
                                    </select>
                                    <span id="destinataire_error" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-form-label col-lg-3 col-sm-12">Objet</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <input class="form-control" type="text"  id="newobjet" name="newobjet" placeholder="objet du message">
                                    <span id="newobjet_error" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-form-label col-lg-3 col-sm-12">Message</label>
                                <div class="col-lg-9 col-md-9 col-sm-12">
                                    <textarea class="form-control" id="newmessgroupe" name="newmessgroupe" rows="3" placeholder="saisir le message ici..."></textarea>
                                    <span id="newmessgroupe_error" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-brand btn-outline-danger" data-dismiss="modal">Annuler & Fermer</button>
                                <button type="submit" id="btnnewmessage" class="btn btn-outline-brand btn-outline-primary">Envoyer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <!--End:: new message-->
                

    <!--Begin:: Inbox View-->
        <div class="modal fade modal_detail" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail message:</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card w-100">
                            <div class="card-body" style="overflow-y: scroll; height: 300px;">
                                <span id="detailmsg">
                                    
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End:: Inbox View-->
        </div>
<!--End::Inbox-->

                    </div>

            <!-- end:: Content -->
        </div>

    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->






<script src="<?php echo assets_dir();?>multiselect/jquery.multiselect.js"></script>
<script>
    $('#langOpt3').multiselect({
        columns: 2,
        placeholder: 'choisi le ou les destinataires',
        search: true,
        selectAll: true,
        noneSelected: 'Sélectionnez aumoins un élément!',
        oneOrMoreSelected: '% clients sélectionnées',
        selectAllText:true
    });
</script>


<script>
    $(document).ready(function () {

        /**affiche le formulaire pour ajouter un produit dans le panier debut*/
        $('#newgroupemessageform').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('sendmessage'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btnnewmessage').attr('disabled', 'disabled');
                    $('#btnnewmessage').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Envoi...');
                    $('.fa-spin').addClass('active');
                },
                success: function (data){
                    if(data.error){
                        $('#message').html('');
                        if(data.destinataire_error != ''){
                            $('#destinataire_error').html(data.destinataire_error);
                        }else{
                            $('#destinataire_error').html('');
                        }

                        if(data.newobjet_error != ''){
                            $('#newobjet_error').html(data.newobjet_error);
                        }else{
                            $('#newobjet_error').html('');
                        }

                        if(data.newmessgroupe_error != ''){
                            $('#newmessgroupe_error').html(data.newmessgroupe_error);
                        }else{
                            $('#newmessgroupe_error').html('');
                        } 
                    }
                    if(data.success){
                        $('#destinataire_error').html('');
                        $('#newobjet_error').html('');
                        $('#newmessgroupe_error').html('');
                        getsendmessages(1);
                        $('#message').html(data.success);
                    }
                   $('#btnnewmessage').attr('disabled', false);
                   $('#btnnewmessage').html('Envoyer');
                }
            });
        });
        /**affiche le formulaire pour ajouter un produit dans le panier fin*/

        /*$("#rechercherdestinat").keyup(function (e) { 
            var inputrecherche = $("#rechercherdestinat").val();
            $("#saisi").html(inputrecherche);
        });*/



        /**afficher la liste des messages envoyé et le nombre de personnes qui ont potentielement recu le message */
        getsendmessages(1);
        function getsendmessages(page){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url('sendmsg/');?>'+page,
                dataType: "json",
                success: function (data) {
                    if(data.success){
                        $('#sendmessages').html(data.success);
                        $('#pagination').html(data.pagination);
                    }
                }
            });
        }
        /**pour géré le click sur les paginations */
        $(document).on("click", ".pagination li a", function(event){
            event.preventDefault();
            var page = $(this).data("ci-pagination-page");
            getsendmessages(page);
        });


        /**show msg informations */
        $(document).on('click','.detail_msg',function (e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('infosmsg')?>",
                data: {id:id},
                dataType: "json",
                success: function (data){
                    if(data.infos){
                        $("#detailmsg").html(data.infos);
                        $(".modal_detail").modal('show');
                    } 
                }
            });
            
        });


    });
</script>