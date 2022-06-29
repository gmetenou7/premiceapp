
</div>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    
    <?php $this->load->view('parts/subheader');?>

        <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                 <!-- begin:: Content -->


                <!--begin::Row-->
                <div class="row">			
                    <div class="col-xl-12">
                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <div class="kt-portlet__body">
                                <div class="kt-section">
                                    <h3 class="kt-section__title">GESTION DES CAISSES</h3>
                                    <div class="kt-section__content kt-section__content--border">
                                        <button type="button" class="btn btn-outline-brand" data-toggle="modal" data-target=".modalcaisse">+ CAISSE</button>
                                        <hr>
                                        <span id="ok"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Portlet-->
                    </div>
                </div>

                <div class="row" id="caisses">
                   
                </div>

                <!-- Large Modal -->
                <div class="modal fade modalcaisse" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Nouvelle Caisse</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <span id="message"></span>

                            <form id="new_caisse_form">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Entreprise</label>
                                            <div class="form-group">
                                                <select name="entreprise" id="entreprise" class="form-control">
                                                    <?php if(!empty(session('users')['matricule'])){?> 
                                                        <?php echo '<option value='.session('users')['matricule'].'>'.session('users')['nom'].'</option>'; ?>
                                                    <?php }?>
                                                </select>
                                                <span class="form-text text-danger" id="entreprise_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Agence</label>
                                            <div class="form-group">
                                                <select name="agence" class="form-control" id="agence">
                                                    <?php echo '<option value=""></option>'; ?>
                                                    <?php if(!empty($agences)){?>
                                                        <?php foreach ($agences as $key => $value) {?>
                                                            <?php echo '<option value="'.$value['matricule_ag'].'">'.$value['nom_ag'].'</option>'; ?>
                                                        <?php }?>
                                                    <?php }?>
                                                </select>
                                                <span class="form-text text-danger" id="agence_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Employé</label>
                                            <div class="form-group">
                                                <select name="employe" class="form-control" id="employe">
                                                    <?php echo '<option value=""></option>'; ?>
                                                    <?php if(!empty($employes)){?>
                                                        <?php foreach ($employes as $key => $value) {?>
                                                            <?php echo '<option value="'.$value['matricule_emp'].'">'.$value['nom_emp'].'</option>'; ?>
                                                        <?php }?>
                                                    <?php }?>
                                                </select>
                                                <span class="form-text text-danger" id="employe_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Libele</label>
                                                <input type="text" class="form-control" id="libele" name="libele" placeholder="nom de la caisse">
                                                <span class="form-text text-danger" id="libele_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Code Banque</label>
                                                <input type="text" class="form-control" id="codeb" name="codeb" placeholder="code de la banque">
                                                <span class="form-text text-danger" id="codeb_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Numero de copmpte</label>
                                                <input type="text" class="form-control" id="numcompte" name="numcompte" placeholder="numero de compte">
                                                <span class="form-text text-danger" id="numcompte_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Clé RIB</label>
                                                <input type="text" class="form-control" id="crib" name="crib" placeholder="clé RIB">
                                                <span class="form-text text-danger" id="crib_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">FERMER</button>
                                    <button type="submit" id="btn_new_caisse" class="btn btn-outline-brand">CREER</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                
                <!-- Large Modal -->
                <div class="modal fade updatemodalcaisse" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modifier Caisse</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <span id="message1"></span>
                            <form id="update_caisse_form">
                                <div class="modal-body">
                                    <div class="row">
                                        <input type="hidden" name="code" id="code">
                                        <div class="col-md-6">
                                            <label>Entreprise</label>
                                            <div class="form-group">
                                                <select name="entreprise1" id="entreprise1" class="form-control">
                                                    <?php if(!empty(session('users')['matricule'])){?> 
                                                        <?php echo '<option value='.session('users')['matricule'].'>'.session('users')['nom'].'</option>'; ?>
                                                    <?php }?>
                                                </select>
                                                <span class="form-text text-danger" id="entreprise1_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Agence</label>
                                            <div class="form-group">
                                                <select name="agence1" class="form-control" id="agence1">
                                                    <?php echo '<option value=""></option>'; ?>
                                                    <?php if(!empty($agences)){?>
                                                        <?php foreach ($agences as $key => $value) {?>
                                                            <?php echo '<option value="'.$value['matricule_ag'].'">'.$value['nom_ag'].'</option>'; ?>
                                                        <?php }?>
                                                    <?php }?>
                                                </select>
                                                <span class="form-text text-danger" id="agence1_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Employé</label>
                                            <div class="form-group">
                                                <select name="employe1" class="form-control" id="employe1">
                                                    <?php echo '<option value=""></option>'; ?>
                                                    <?php if(!empty($employes)){?>
                                                        <?php foreach ($employes as $key => $value) {?>
                                                            <?php echo '<option value="'.$value['matricule_emp'].'">'.$value['nom_emp'].'</option>'; ?>
                                                        <?php }?>
                                                    <?php }?>
                                                </select>
                                                <span class="form-text text-danger" id="employe1_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Libele</label>
                                                <input type="text" class="form-control" id="libele1" name="libele1" placeholder="nom de la caisse">
                                                <span class="form-text text-danger" id="libele1_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Code Banque</label>
                                                <input type="text" class="form-control" id="codeb1" name="codeb1" placeholder="code de la banque">
                                                <span class="form-text text-danger" id="codeb1_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Numero de copmpte</label>
                                                <input type="text" class="form-control" id="numcompte1" name="numcompte1" placeholder="numero de compte">
                                                <span class="form-text text-danger" id="numcompte1_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Clé RIB</label>
                                                <input type="text" class="form-control" id="crib1" name="crib1" placeholder="clé RIB">
                                                <span class="form-text text-danger" id="crib1_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">FERMER</button>
                                    <button type="submit" id="btn_update_caisse" class="btn btn-outline-brand">MODIFIER</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>






            <!-- end:: Content -->				
        </div>

    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->

<script>
    $(document).ready(function () {
        
        /**nouvelle caisse debut */
        $('#new_caisse_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('new_caisse'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_new_caisse').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        if(data.entreprise_error != ''){
                            $('#entreprise_error').html(data.entreprise_error);
                        }else{
                            $('#entreprise_error').html('');
                        }

                        if(data.agence_error != ''){
                            $('#agence_error').html(data.agence_error);
                        }else{
                            $('#agence_error').html('');
                        }

                        if(data.employe_error != ''){
                            $('#employe_error').html(data.employe_error);
                        }else{
                            $('#employe_error').html('');
                        }

                        if(data.libele_error != ''){
                            $('#libele_error').html(data.libele_error);
                        }else{
                            $('#libele_error').html('');
                        }
                        
                        if(data.numcompte_error != ''){
                            $('#numcompte_error').html(data.numcompte_error);
                        }else{
                            $('#numcompte_error').html('');
                        }
                        
                        if(data.codeb_error != ''){
                            $('#codeb_error').html(data.codeb_error);
                        }else{
                            $('#codeb_error').html('');
                        }
                        
                        if(data.crib_error != ''){
                            $('#crib_error').html(data.crib_error);
                        }else{
                            $('#crib_error').html('');
                        }
                    }
                    if(data.success){
                        $('#numcompte_error').html('');
                        $('#crib_error').html('');
                        $('#codeb_error').html('');
                        $('#entreprise_error').html('');
                        $('#agence_error').html('');
                        $('#employe_error').html('');
                        $('#libele_error').html('');
                        $('#new_caisse_form')[0].reset();
                        $('#message').html(data.success);

                        list_caisse();
                    }
                    $('#btn_new_caisse').attr('disabled', false);
                }
            });
        });
        /**nouvelle caisse fin */

        /**afficher la liste des caisses debut */
        list_caisse();
        function list_caisse(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('all_caisse'); ?>",
                dataType: "json",
                success: function(data) {
                    $("#caisses").html(data);
                }
            });
        }
        /**afficher la liste des caisses fin */

        /**afficher les informations dans le formulaire de modification debut */
        $(document).on('click', '.viewcaisse', function(){
            var mat_caisse = $(this).attr("id");
            $.ajax({
                method:"POST",
                url: "<?php echo base_url('get_single_caisse'); ?>",
                data:{mat_caisse:mat_caisse},
                dataType: "json",
                success: function(data){
                   $("#agence1").val(data.agence);
                    $('#employe1').val(data.employe);
                    $('#libele1').val(data.libelle);
                    $('#code').val(data.code);
                    
                    $('#numcompte1').val(data.compte);
                    $('#crib1').val(data.rib);
                    $('#codeb1').val(data.banque);
                }
            }); 
            $(".updatemodalcaisse").modal('show');
        });
        /**afficher les informations dans le formulaire de modification fin */

        /**modifier la caisse debut */
        $('#update_caisse_form').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('update_caisse'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_update_caisse').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        if(data.entreprise1_error != ''){
                            $('#entreprise1_error').html(data.entreprise1_error);
                        }else{
                            $('#entreprise1_error').html('');
                        }

                        if(data.agence1_error != ''){
                            $('#agence1_error').html(data.agence1_error);
                        }else{
                            $('#agence1_error').html('');
                        }

                        if(data.employe1_error != ''){
                            $('#employe1_error').html(data.employe1_error);
                        }else{
                            $('#employe1_error').html('');
                        }

                        if(data.libele1_error != ''){
                            $('#libele1_error').html(data.libele1_error);
                        }else{
                            $('#libele1_error').html('');
                        }
                        
                        if(data.numcompte1_error != ''){
                            $('#numcompte1_error').html(data.numcompte1_error);
                        }else{
                            $('#numcompte1_error').html('');
                        }
                        
                        if(data.codeb1_error != ''){
                            $('#codeb1_error').html(data.codeb1_error);
                        }else{
                            $('#codeb1_error').html('');
                        }
                        
                        if(data.crib1_error != ''){
                            $('#crib1_error').html(data.crib1_error);
                        }else{
                            $('#crib1_error').html('');
                        }
                    }
                    if(data.success){
                        $('#numcompte1_error').html('');
                        $('#crib1_error').html('');
                        $('#codeb1_error').html('');
                        $('#entreprise1_error').html('');
                        $('#agence1_error').html('');
                        $('#employe1_error').html('');
                        $('#libele1_error').html('');
                        $('#message1').html(data.success);
                        list_caisse();
                    }
                    $('#btn_update_caisse').attr('disabled', false);
                }
            });
        });
        /**modifier la caisse fin */

    });
</script>