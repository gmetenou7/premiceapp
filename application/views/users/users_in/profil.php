</div>
<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">                 
    <?php $this->load->view('parts/subheader');?>

    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">


            


        <!-- begin:: Content -->
		<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<!--Begin::App-->
        <hr>
		<div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
				

                
    <!--Begin:: App Aside-->
    <div class="kt-grid__item kt-app__toggle kt-app__aside kt-app__aside--sm kt-app__aside--fit" id="kt_profile_aside">
       

    
        <!--Begin:: Portlet-->
		<div class="kt-portlet">
            <?php if(session('users')['status'] == "" && session('users')['matricule_ag'] == "" && session('users')['matricule_serv'] == ""): ?>
                <?php if(!empty($user)): ?>
                <div class="kt-portlet__body">
                    <div class="kt-widget kt-widget--general-1">
                        <div class="kt-media kt-media--brand kt-media--md kt-media--circle">
                            <img src="<?php echo assets_dir(); ?>media/logos/logo-1.1.png" alt="image">
                        </div>
                        <div class="kt-widget__wrapper">
                            <div class="kt-widget__label">
                                <a class="kt-widget__title"><?php echo $user['nom_en']; ?></a>
                                <span class="kt-widget__desc"><?php echo $user['matricule_en']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                    <p class="text-danger">informations non trouvées</p>
                <?php endif ?>
            <?php else: ?>
            <?php if(!empty($employe)): ?>
                <div class="kt-portlet__body">
                    <div class="kt-widget kt-widget--general-1">
                        <div class="kt-media kt-media--brand kt-media--md kt-media--circle">
                            <img src="<?php echo assets_dir(); ?>media/logos/logo-1.1.png" alt="image">
                        </div>
                        <div class="kt-widget__wrapper">
                            <div class="kt-widget__label">
                                <a class="kt-widget__title"><?php echo $employe['nom_emp']; ?></a>
                                <span class="kt-widget__desc"><?php echo $employe['matricule_emp']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                    <p class="text-danger">informations non trouvées</p>
                <?php endif ?>
            <?php endif ?>

			<div class="kt-portlet__separator"></div>
                <?php if(session('users')['status'] == "" && session('users')['matricule_ag'] == "" && session('users')['matricule_serv'] == ""): ?>
                    ...
                <?php else: ?>
                    <div class="kt-portlet__body">
                        <ul class="kt-nav kt-nav--bolder kt-nav--fit-ver kt-nav--v4" role="tablist">
                            <li class="kt-nav__item  ">
                                <a class="kt-nav__link" href="#" role="tab" id="pass">
                                    <span class="kt-nav__link-icon"><i class="flaticon2-settings"></i></span>
                                    <span class="kt-nav__link-text">Modifier mot de passe</span>
                                </a>
                            </li>
                            <li class="kt-nav__item  ">
                                <a class="kt-nav__link" href="#" role="tab" id="client">
                                    <span class="kt-nav__link-icon"><i class="flaticon2-chart2"></i></span>
                                    <span class="kt-nav__link-text">Client</span>
                                </a>
                            </li>
                            <li class="kt-nav__item  ">
                                <a class="kt-nav__link" href="#" role="tab" id="vente">
                                    <span class="kt-nav__link-icon"><i class="flaticon-security"></i></span>
                                    <span class="kt-nav__link-text">Ventes</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
			

			<div class="kt-portlet__separator"></div>

			<div class="kt-portlet__body">
				<ul class="kt-nav kt-nav--bolder kt-nav--fit-ver kt-nav--v4" role="tablist">
					<li class="kt-nav__custom">
						...
					</li>
				</ul>
			</div>
		</div>
		<!--End:: Portlet-->


    </div>
    <!--End:: App Aside-->

    <!--Begin:: App Content-->
    <div class="kt-grid__item kt-grid__item--fluid kt-app__content">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">Information Personnel <small>Modifier Vos Informations Personnel</small></h3>
                </div>
            </div>


            <?php if(session('users')['status'] == "" && session('users')['matricule_ag'] == "" && session('users')['matricule_serv'] == ""): ?>
            <?php if(!empty($user)): ?>
            <!--begin::Form form entreprise ou groupe-->
                <form class="kt-form" method="POST" action="<?php echo base_url('profil/'.$user['matricule_en']);?>">
                    <div class="kt-portlet__body">
                        <?php $this->load->view('parts/message');?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nom</label>
                                    <input type="text" class="form-control" value="<?php echo $user['nom_en'] ?>" name="nom" id="nom" placeholder="entre le nom">
                                    <?php echo form_error('nom', '<div class="text-danger">', '</div>'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Addresse</label>
                                    <input type="text" class="form-control" value="<?php echo $user['adresse_en'] ?>" name="adresse" id="adresse" placeholder="entre la localisation exact de l'entreprise">
                                    <?php echo form_error('adresse', '<div class="text-danger">', '</div>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Activité</label>
                                    <input type="text" class="form-control" value="<?php echo $user['activite_en'] ?>" name="activite" id="activite" placeholder="quel est le domaine d'actibité de l'entreprise">
                                    <?php echo form_error('activite', '<div class="text-danger">', '</div>'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>forme juriquique</label>
                                    <select class="form-control"  id="juridique" name="juridique">
                                        <option selected value="<?php echo $user['form_juridique_en'] ?>"><?php echo $user['form_juridique_en'] ?></option>
                                        <?php if(!empty($juridique)):?>
                                            <?php foreach($juridique as $row):?>
                                                <option value="<?php echo $row['nom_form']; ?>"><?php echo $row['nom_form']; ?></option>
                                            <?php endforeach?>
                                        
                                        <?php else:?>
                                            <option>aucunr forme juridique trouvé trouvé</option>
                                        <?php endif?>
                                    </select>
                                    <?php echo form_error('juridique', '<div class="text-danger">', '</div>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pays</label>
                                    <select class="form-control" id="pays" name="pays">
                                        <option selected value="<?php echo $user['pays_en'] ?>"><?php echo $user['pays_en'] ?></option>
                                        <?php if(!empty($pays)):?>
                                            <?php foreach($pays as $row):?>
                                                <option value="<?php echo $row['nom_fr_fr']; ?>"><?php echo $row['nom_fr_fr']; ?></option>
                                            <?php endforeach?>
                                        <?php else:?>
                                            <option>aucun pays trouvé</option>
                                        <?php endif?>
                                    </select>
                                    <?php echo form_error('pays', '<div class="text-danger">', '</div>'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Telephone 1</label>
                                    <input type="tel" class="form-control" value="<?php echo $telephone[0] ?>" name="telephone1" id="telephone1" placeholder="numéro de téléphone 1 de l'entreprise">
                                    <span class="form-text text-muted">ajouter l'indicatif téléphonique exemple: +237 6..., +1 9...</span>
                                    <?php echo form_error('telephone1', '<div class="text-danger">', '</div>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                                    <label>Telephone 2</label>
                                    <input type="tel" class="form-control" value="<?php echo $telephone[1] ?>" name="telephone2" id="telephone2" placeholder="numéro de téléphone 2 de l'entreprise">
                                    <span class="form-text text-muted">ajouter l'indicatif téléphonique exemple: +237 6..., +1 9...</span>
                                    <?php echo form_error('telephone2', '<div class="text-danger">', '</div>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                            <div class="form-group">
                                    <label>site interne</label>
                                    <input type="tel" class="form-control" value="<?php echo $user['site_internet_en'] ?>" name="site" id="site" placeholder="site internet de l'entreprise">
                                    <?php echo form_error('site', '<div class="text-danger">', '</div>'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button type="submit" name="new_entreprise_submit" class="btn btn-danger">Modifier</button>
                        </div>
                    </div>
                </form>
                <!--end::Form entreprise ou groupe-->
                <?php else: ?>
                    <p class="text-danger">informations non trouvées</p>
                <?php endif ?>
            <?php else: ?>

            <?php if(!empty($employe)): ?>
            <!--begin::Form user(employe)-->
                <form action="<?php echo base_url('profil/'.$employe['matricule_emp']);?>" method="POST">
                <input type="hidden" name="matricule" id="matricule" value="<?php echo $employe['matricule_emp']; ?>">
                    <div class="modal-body">
                        <!--debut message-->
                            <span id="message"></span>
                        <!--fin message-->
                        <div class="kt-portlet__body">
                            <?php $this->load->view('parts/message');?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nom<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nom" id="nom" value="<?php echo $employe['nom_emp']; ?>" placeholder="nom de l'employé">
                                        <?php echo form_error('nom', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Adresse</label>
                                        <input type="text" class="form-control" name="adresse" id="adresse" value="<?php echo $employe['adresse_emp']; ?>" placeholder="adresse">
                                        <span class="form-text text-muted">comment vous retrouvez au besion?</span>
                                        <?php echo form_error('adresse', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="email" value="<?php echo $employe['email_emp']; ?>">
                                        <span class="form-text text-muted">nous assurons la protection de votre email</span>
                                        <?php echo form_error('email', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Numero de telephone 1<span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" name="telephone1" id="telephone1" placeholder="telephone" value="<?php echo $telephone_emp[0]; ?>">
                                        <?php echo form_error('telephone1', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Numero de telephone 2</label>
                                        <input type="tel" class="form-control" name="telephone2" id="telephone2" placeholder="telephone" value="<?php echo $telephone_emp[1]; ?>">
                                        <?php echo form_error('telephone2', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Date de naissance</label>
                                        <input type="date" class="form-control" name="date_naiss" id="date_naiss" placeholder="date de naissance" value="<?php echo $employe['date_naiss_emp']; ?>">
                                        <span class="form-text text-muted">une date correcte nous permet de partager ce merveilleux jour avec vous</span>
                                        <?php echo form_error('date_naiss', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fonction<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="fonction" id="fonction" placeholder="fonction" value="<?php echo $employe['fonction_emp']; ?>">
                                        <span class="form-text text-muted">quel est votre rôle dans l'entreprise?</span>
                                        <?php echo form_error('fonction', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    vous devez absolument remplir les champs ayant <span class="text-danger">*</span>
                                </div>
                            </div>
                        </div>	
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btn_save_user" class="btn btn-outline-brand">Modifier</button>
                    </div>
                </form>
                <!--end::Form user(employe)-->
            <?php else: ?>
                <p class="text-danger">aucune information trouvée</p>
            <?php endif ?>
            <?php endif; ?>
        </div>
    </div>
    <!--End:: App Content-->
</div>
<!--End::App-->	</div>
<!-- end:: Content -->	



<!-- debut update pass modal -->
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="pass_modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le mot de passe</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_pass">
                <div class="modal-body">
                    <span id="update_message"></span>
                    <div class="row form-group">
                        <input type="hidden" name="matricule_emp_pass" id="matricule_emp_pass">
                        <div class="col-md-4">
                            <input type="password" id="ancientpassword" name="ancientpassword" class="form-control" placeholder="entre le mot de passe actuel">
                            <span id="update_ancientpassword_error" class="text-danger"></span>
                        </div>
                        <div class="col-md-4">
                            <input type="password" id="password" name="password" class="form-control" placeholder="entre le mot de passe">
                            <span id="update_password_error" class="text-danger"></span>
                        </div>
                        <div class="col-md-4">
                            <input type="password" id="cpassword" name="cpassword" class="form-control" placeholder="entre la confirmation de mot de passe">
                            <span id="confirm_update_password_error" class="text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
                    <button type="submit" id="btn_password" class="btn btn-outline-brand">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- fin update pass modal -->

<!-- debut client pass modal -->
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="client_modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Liste de mes client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive">
                    <input type="search" class="form-control constomusershearchinput" name="constomusershearchinput" id="constomusershearchinput" placeholder="chercher un client ici avec son nom">
                    <span id="costomshearch"></span>
                    <hr>
                <div class="kt-scroll" data-scroll="true" style="height: 400px;">
                    <table class="table table-striped">
                        <thead>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Adresse</th>
                            <th>Date Naissance</th>
                            <th>date creer</th>
                        </thead>
                        <tbody id="costomus">
                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- fin client pass modal -->

<!-- debut vente modal -->
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="vente_modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mes ventes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>choisi la période</p>
                <span class="text-danger" id="sels_message"></span>
                <form id="showuselsform">
                    <div class="form-group row">
                        <div class="col-lg-11 col-md-9 col-sm-12">
                            <div class="input-daterange input-group" id="kt_datepicker_5">
                                <input type="text" class="form-control" name="start" placeholder="debut" />
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="la la-ellipsis-h"></i></span>
                                </div>
                                <input type="text" class="form-control" name="end" placeholder="fin" />
                            </div>
                            <div class="row">
                                <div class="col-md-6"><span class="form-text text-danger" id="start_error"></span></div>
                                <div class="col-md-6"><span class="form-text text-danger" id="end_error"></span></div>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit" id="btnshowusels">Voir</button>
                    </div>
                </form>
                <hr>
                <div class="table_responsive kt-scroll" data-scroll="true" style="height: 400px;">
                    <table class="table table-striped">
                        <thead>
                            <th>Client</th>
                            <th>CASH</th>
                            <th>DETTE</th>
                        </thead>
                        <tbody id="informationssels">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- fin vente modal -->

<!-- modal d'informations sur les vente du users debut --
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="infosmodal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mes ventes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span class="table_responsive">
                        <table class="table table-striped">
                            <thead>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Délai</th>
                                <th>Statut</th>
                                <th>Prix HT total</th>
                                <th>Prix TTC total</th>
                            </thead>
                            <tbody id="informationssels">
                                
                            </tbody>
                        </table>
                    </span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
<!-- modal d'informations sur les vente du users fin -->













            <!-- end:: Content -->
        </div>

    <?php $this->load->view('parts/footer');?>

</div>
<!-- end:: Page -->



<script>
    $(document).ready(function(){
        /**affiche le mot de passe modal*/
        $("#pass").click(function (e) { 
            e.preventDefault();
            $("#matricule_emp_pass").val('<?php echo session('users')['matricule_emp']; ?>');
            
            $("#pass_modal").modal('show');
        });


        $('#form_pass').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                method: "POST",
                url: "<?php echo base_url('update_employe_password'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btn_password').attr('disabled', 'disabled');
                    $('#btn_password').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                    $('.fa-spin').addClass('active');
                },
                success: function (data){
                    if(data.error){
                        $('#update_message').html('');

                        if(data.update_ancientpassword_error != ''){
                            $('#update_ancientpassword_error').html(data.update_ancientpassword_error);
                        }else{
                            $('#update_ancientpassword_error').html('');
                        }

                        if(data.update_password_error != ''){
                            $('#update_password_error').html(data.update_password_error);
                        }else{
                            $('#update_password_error').html('');
                        }

                        if(data.confirm_update_password_error != ''){
                            $('#confirm_update_password_error').html(data.confirm_update_password_error);
                        }else{
                            $('#confirm_update_password_error').html('');
                        }
                    }
                    if(data.success){
                        $('#update_password_error').html('');
                        $('#confirm_update_password_error').html('');
                        $('#update_ancientpassword_error').html('');
                        $('#update_message').html(data.success);

                        setTimeout(function(){
                            window.location.href = "<?php echo base_url('logout'); ?>";
                        }, 5000);
                    }

                    if(data.loss){
                        $('#update_password_error').html('');
                        $('#confirm_update_password_error').html('');
                        $('#update_ancientpassword_error').html('');
                        $('#update_message').html(data.loss);
                    }
                    $('#btn_password').attr('disabled', false);
                    $('#btn_password').html("Modifier");
                }
            });
        });

        /**affiche les ventes d'un user sur une période*/
        $('#showuselsform').on('submit', function(event){
            event.preventDefault();
           $.ajax({
               method: "POST",
                url: "<?php echo base_url('showusels'); ?>",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend:function(){
                    $('#btnshowusels').attr('disabled', 'disabled');
                },
                success: function (data){
                    if(data.error){
                        $('#sels_message').html('');
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
                        $('#start_error').html('');
                        $('#end_error').html('');
                        //$('#sels_message').html(data.success);
                        $('#informationssels').html(data.success);
                        //$('#infosmodal').modal('show');
                    }
                    
                    $('#btnshowusels').attr('disabled', false);
                }
           });
        });





        /**affiche le client modal*/
        $("#client").click(function (e) { 
            e.preventDefault();
            $("#client_modal").modal('show');
        });


        /**affiche le client modal*/
        $("#vente").click(function (e) { 
            e.preventDefault();
            $("#vente_modal").modal('show');
        });

        /*liste des clients de cet utilisateur*/
        costomuse();
        function costomuse(){
            $.ajax({
                method: "GET",
                url: "<?php echo base_url('costomu'); ?>",
                dataType: "json",
                success: function (data){
                    $('#costomus').html(data);
                }
            });  
        }


        /**chercher le client d'un utilisateur a partir de son profil */
        $(".constomusershearchinput").keyup(function (e) { 
            e.preventDefault();
            var content = $("#constomusershearchinput").val();
            if(content !== ''){
                $("#costomshearch").html(content);
                $.ajax({
                    method: "POST",
                    url: "<?php echo base_url('costomu'); ?>",
                    data: {content:content},
                    dataType: "json",
                    beforeSend:function(){
                        $('#costomus').html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span>');
                        $('.fa-spin').addClass('active');
                    },
                    success: function (data){
                        $('#costomus').html(data);
                        $('.has-spinner').removeAttr("disabled");
                    }
                });
            }else{
                $("#costomshearch").html('');
                costomuse();
            }
        });



    });
</script>