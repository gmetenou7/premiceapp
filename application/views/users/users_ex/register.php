
<!DOCTYPE html>
<html lang="fr" >
<head>
    <meta charset="utf-8"/>
    <title>Register</title>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta name="description" content="User login example"> 
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!--begin::Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">        

        
    <!--begin::Page Custom Styles(used by this page) -->
        <link href="<?php echo assets_dir();?>css/pages/login/login.css" rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles -->


    <!--begin::Page Custom Styles(used by this page) -->
        <link href="<?php echo assets_dir();?>css/pages/wizards/wizard-v1.css" rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles -->
        
    <!--begin::Global Theme Styles(used by all pages) -->
        <link href="<?php echo assets_dir();?>plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo assets_dir();?>css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
        <link rel="shortcut icon" href="https://keenthemes.com/keen/themes/keen/theme/demo6/dist/assets/media/logos/favicon.ico" />
    <!-- Hotjar Tracking Code for keenthemes.com -->

    <link rel="shortcut icon" href="<?php echo assets_dir();?>media/logos/logo-1.1.png" type="image/x-icon">



    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:1070954,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-37564768-1"></script>

    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-37564768-1');
    </script>    

</head>
    <!-- begin::Body -->
    <body  class="kt-demo-panel--right kt-header-mobile--fixed kt-page-content-white kt-subheader--enabled kt-aside--secondary-enabled kt-offcanvas-panel--left kt-aside--left kt-page--loading">
    <!-- begin::Page loader -->
        
    <!-- end::Page Loader -->        
    <!-- begin:: Page -->
	<div class="kt-grid kt-grid--ver kt-grid--root">
		<div class="kt-grid__item   kt-grid__item--fluid kt-grid  kt-grid kt-grid--hor kt-login-v2" id="kt_login_v2">
        <!--begin::Item-->
        <div class="kt-grid__item  kt-grid--hor">
            <!--begin::Heade-->
            <div class="kt-login-v2__head">
                <div class="kt-login-v2__logo">
                    <a href="#">
                        <img src="<?php echo assets_dir();?>media/logos/logo-1.1.png" alt="" />
                    </a>
                </div>
                <div class="kt-login-v2__signup">
                    <span>Vous avez un compte?</span>
                    <a href="<?php echo base_url('login'); ?>" class="kt-link kt-font-brand">Se Connecter</a>
                </div>
            </div>
            <!--begin::Head-->
        </div>
        <!--end::Item-->

    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="kt-portlet">
        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="kt-grid kt-grid--desktop-xl kt-grid--ver-desktop-xl  kt-wizard-v1" id="kt_wizard_v1" data-ktwizard-state="step-first">
                <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v1__wrapper">
                   
                


                <div class="row">
                    <div class="col-md-12">
                        <!--begin::Portlet-->
                        <div class="kt-portlet">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">C'est un nouveau groupe où une nouvelle entreprise, utilise ce formulaire pour l'enregistrer</h3>
                                </div>
                            </div>
                            <?php $this->load->view('parts/message');?>
                            <!--begin::Form-->
                            <form class="kt-form" method="POST" <?php echo base_url('register'); ?>>
                                <div class="kt-portlet__body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nom</label>
                                                <input type="text" class="form-control" value="<?php echo set_value('nom'); ?>" name="nom" id="nom" placeholder="entre le nom">
                                                <?php echo form_error('nom', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Addresse</label>
                                                <input type="text" class="form-control" value="<?php echo set_value('adresse'); ?>" name="adresse" id="adresse" placeholder="entre la localisation exact de l'entreprise">
                                                <?php echo form_error('adresse', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Activité</label>
                                                <input type="text" class="form-control" value="<?php echo set_value('activite'); ?>" name="activite" id="activite" placeholder="quel est le domaine d'actibité de l'entreprise">
                                                <?php echo form_error('activite', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>forme juriquique</label>
                                                <select class="form-control"  id="juridique" name="juridique">
                                                    <option value="<?php echo set_value('juridique'); ?>"><?php echo set_value('juridique'); ?></option>
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
                                                    <option value="<?php echo set_value('pays'); ?>"><?php echo set_value('pays'); ?></option>
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
                                                <input type="tel" class="form-control" value="<?php echo set_value('telephone1'); ?>" name="telephone1" id="telephone1" placeholder="numéro de téléphone 1 de l'entreprise">
                                                <span class="form-text text-muted">ajouter l'indicatif téléphonique exemple: +237 6..., +1 9...</span>
                                                <?php echo form_error('telephone1', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                        <div class="form-group">
                                                <label>Telephone 2</label>
                                                <input type="tel" class="form-control" value="<?php echo set_value('telephone2'); ?>" name="telephone2" id="telephone2" placeholder="numéro de téléphone 2 de l'entreprise">
                                                <span class="form-text text-muted">ajouter l'indicatif téléphonique exemple: +237 6..., +1 9...</span>
                                                <?php echo form_error('telephone2', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" value="<?php echo set_value('email'); ?>" name="email" id="email" placeholder="entre l'email de l'entreprise">
                                                <span class="form-text text-muted">Nous ne partagerons jamais votre e-mail avec qui que ce soit.</span>
                                                <?php echo form_error('email', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                        <div class="form-group">
                                                <label>site interne</label>
                                                <input type="link" class="form-control" value="<?php echo set_value('site'); ?>" name="site" id="site" placeholder="site internet de l'entreprise">
                                                <?php echo form_error('site', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Mot de passe</label>
                                                <input type="password" class="form-control" name="password" id="password" placeholder="entre le mot de passe">
                                                <?php echo form_error('password', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Confirmation Mot de passe</label>
                                                <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="entre la confirmation de mot de passe">
                                                <?php echo form_error('cpassword', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-portlet__foot">
                                    <div class="kt-form__actions">
                                        <button type="submit" name="new_entreprise_submit" class="btn btn-primary">Creer</button>
                                        <button type="reset" class="btn btn-secondary">Annuler</button>
                                    </div>
                                </div>
                            </form>
                            <!--end::Form-->			
                        </div>
                        <!--end::Portlet-->
                        </div>



                </div>
            </div>
        </div>
    </div>
</div>
<!-- end:: Content -->				
</div>

	

        <!--begin::Item-->
        <div class="kt-grid__item">
            <div class="kt-login-v2__footer">
                <div class="kt-login-v2__link">
                    <!--<a href="#" class="kt-link kt-font-brand">Privacy</a>
                    <a href="#" class="kt-link kt-font-brand">Legal</a>-->
                    <a class="kt-link kt-font-brand">PREMICE COMPUTER</a>
                </div>

                <div class="kt-login-v2__info">
                    <a href="#" class="kt-link">&copy; 2021 ...</a>
                </div>
            </div>
        </div>
        <!--end::Item-->
    </div>
</div>	
	
<!-- end:: Page -->


    <!-- begin::Global Config(global config for global JS sciprts) -->
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#3699ff",
                "metal": "#c4c5d6",
                "light": "#ffffff",
                "accent": "#00c5dc",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995",
                "focus": "#9816f4"
            },
            "base": {
                "label": [
                    "#c5cbe3",
                    "#a1a8c3",
                    "#3d4465",
                    "#3e4466"
                ],
                "shape": [
                    "#f0f3ff",
                    "#d9dffa",
                    "#afb4d4",
                    "#646c9a"
                ]
            }
        }
    };
</script>
<!-- end::Global Config -->

    <!--begin::Global Theme Bundle(used by all pages) -->
        <script src="<?php echo assets_dir();?>plugins/global/plugins.bundle.js" type="text/javascript"></script>
        <script src="<?php echo assets_dir();?>js/scripts.bundle.js" type="text/javascript"></script>
    <!--end::Global Theme Bundle -->

</body>
</html>


    <!--begin::Page Scripts(used by this page) -->
    <script src="<?php echo assets_dir();?>js/pages/components/extended/toastr.js" type="text/javascript"></script>
    <!--end::Page Scripts -->

<script>

    toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "10000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
    };

        <?php if($this->session->has_userdata('success')):?> 
            toastr.success("<?php echo $this->session->flashdata('success');?>")
        <?php endif ?>

        <?php if($this->session->has_userdata('error')):?> 
            toastr.error("<?php echo $this->session->flashdata('error');?>")
        <?php endif ?>

        <?php if($this->session->has_userdata('info')):?> 
            toastr.info("<?php echo $this->session->flashdata('info');?>") 
        <?php endif ?>

        <?php if($this->session->has_userdata('warning')):?> 
            toastr.warning("<?php echo $this->session->flashdata('warning');?>") 
        <?php endif ?>
</script>