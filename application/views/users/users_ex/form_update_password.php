
<!DOCTYPE html>
<html lang="fr" >
<head>
    <meta charset="utf-8"/>
    <title>Password</title>
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
                        <a href="<?php echo base_url('login'); ?>" class="kt-link kt-font-brand">Se connecter</a>
                    </div>
                </div>
                <!--begin::Head-->
            </div>
            <!--end::Item-->




        <!-- begin:: Content -->
        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <div class="kt-portlet">
                <div class="kt-portlet__body kt-portlet__body--fit">
                    <div class="kt-grid kt-grid--desktop-xl kt-grid--ver-desktop-xl  kt-wizard-v1" id="kt_wizard_v1" data-ktwizard-state="step-first">
                        <div class="kt-grid__item kt-grid__item--fluid kt-wizard-v1__wrapper">
                            


                        <div class="row">
                            <div class="col-md-6">
                                <!--begin::Portlet-->
                                <div class="kt-portlet">
                                    <div class="kt-portlet__head">
                                        <div class="kt-portlet__head-label">
                                            <h3 class="kt-portlet__head-title">Modifier votre mot de passe</h3>
                                        </div>
                                    </div>
                                    <!--begin::Form-->
                                    
                                    <form class="kt-form" method="POST" action="<?php echo base_url('pass/'.$this->uri->segment(2)); ?>">
                                        
                                        <?php $this->load->view('parts/message');?>
                                        
                                        <div class="kt-portlet__body">
                                            <div class="form-group">
                                                <label>Mot de passe</label>
                                                <input type="password" name="password" value="<?php echo set_value('password'); ?>" class="form-control" placeholder="mot de passe">
                                                <?php echo form_error('password', '<div class="text-danger">', '</div>');?>
                                            </div>
                                            <div class="form-group">
                                                <label>Confirmation du mot de passe</label>
                                                <input type="password" name="cpassword" value="<?php echo set_value('cpassword'); ?>" class="form-control" placeholder="confirmation du mot de passe">
                                                <?php echo form_error('cpassword', '<div class="text-danger">', '</div>');?>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__foot">
                                            <div class="kt-form__actions">
                                                <button type="submit" id="btn_send" class="btn btn-primary">Modifier</button>
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
        </div>
        <!-- end:: Content -->		


	



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
    "timeOut": "5000",
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
