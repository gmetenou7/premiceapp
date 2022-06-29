<?php $this->load->view('parts/header_assets');?>
    
    
    
    
    
    
    
    <!-- begin::Body -->
        <body  class="kt-demo-panel--right kt-header-mobile--fixed kt-page-content-white kt-subheader--enabled kt-aside--secondary-enabled kt-offcanvas-panel--left kt-aside--left kt-page--loading"  >
    <!-- begin::Page loader -->
	
    <!-- end::Page Loader -->        
    	<!-- begin:: Page -->

	<!-- begin:: Header Mobile -->
    <div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed " >
        <div class="kt-header-mobile__logo">
            <a href="<?php echo base_url('home');?>">
                <img alt="Logo" src="<?php echo assets_dir();?>media/logos/logo-9.1.png"/>
            </a>
        </div>
        <div class="kt-header-mobile__toolbar">
            <button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
        </div>
        
    </div>
    

<!-- end:: Header Mobile -->


        <!-- begin:: Root -->
        <div class="kt-grid kt-grid--hor kt-grid--root">
                <!-- begin:: Page -->
                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
                    <!-- begin:: Aside -->
            <button class="kt-aside-close kt-hidden " id="kt_aside_close_btn"><i class="la la-close"></i></button>

            <div class="kt-aside  kt-grid__item kt-grid kt-grid--ver" id="kt_aside">
                <!-- begin::Aside Primary -->
            <div class="kt-aside__primary">
                <!-- begin::Aside Top -->
                <div class="kt-aside__top">
                    <a class="kt-aside__brand" href="<?php echo base_url('home'); ?>">
                        <img alt="Logo" src="<?php echo assets_dir();?>media/logos/logo-9.1.png">
                    </a>
                </div>
        <!-- end:: Aside Top -->



    <!-- begin::Aside Middle -->
	<div class="kt-aside__middle">
		<ul class="kt-aside__nav">
			<li class="kt-aside__nav-item">
				<a class="kt-aside__nav-link active"  href="<?php echo base_url('home'); ?>" data-toggle="kt-tooltip" data-title="Tableau de bord" data-placement="right" data-container="body" data-boundary="window">
					<i class="flaticon2-protection"></i>
				</a>
			</li>
			<li class="kt-aside__nav-item">
				<a class="kt-aside__nav-link" href="<?php echo base_url('home'); ?>" data-toggle="kt-tooltip" data-title="Latest orders" data-placement="right" data-container="body" data-boundary="window">
					<i class="flaticon2-hourglass-1"></i>
				</a>
			</li>
			<li class="kt-aside__nav-item">
				<a class="kt-aside__nav-link" href="<?php echo base_url('home'); ?>" data-toggle="kt-tooltip" data-title="User feedbacks" data-placement="right" data-container="body" data-boundary="window">
					<i class="flaticon2-schedule"></i>
				</a>
			</li>
			<li class="kt-aside__nav-item">
				<a  class="kt-aside__nav-link" href="<?php echo base_url('home'); ?>" data-toggle="kt-tooltip" data-title="System settings" data-placement="right" data-container="body" data-boundary="window">
					<i class="flaticon2-shopping-cart-1"></i>
				</a>
			</li>
			<li class="kt-aside__nav-item">
				<a  class="kt-aside__nav-link" href="<?php echo base_url('home'); ?>" data-toggle="kt-tooltip" data-title="Finance reports" data-placement="right" data-container="body" data-boundary="window">
					<i class="flaticon2-list-3"></i>
				</a>
			</li>
			<li class="kt-aside__nav-item">
				<a  class="kt-aside__nav-link" href="<?php echo base_url('home'); ?>" data-toggle="kt-tooltip" data-title="Membership reports" data-placement="right" data-container="body" data-boundary="window">
					<i class="flaticon2-drop"></i>
				</a>
			</li>
			<li class="kt-aside__nav-item">
				<a class="kt-aside__nav-link" href="<?php echo base_url('home'); ?>" data-toggle="kt-tooltip" data-title="Notifications" data-placement="right" data-container="body" data-boundary="window">
					<i class="flaticon2-delivery-truck"></i>
				</a>
			</li>
		</ul>
	</div>
	<!-- end::Aside Middle -->


    <!-- begin::Aside Bottom -->
	<div class="kt-aside__bottom">
		<ul class="kt-aside__nav">
            <!--<li class="kt-aside__nav-item">
                <a href="#" class="kt-aside__nav-link" id="kt_offcanvas_toolbar_search_toggler_btn">
                    <i class="flaticon2-search-1"></i>
                </a>
            </li>-->
			
            <li class="kt-aside__nav-item">
                <a href="#" class="kt-aside__nav-link"  id="kt_offcanvas_toolbar_notifications_toggler_btn" data-toggle="kt-tooltip" data-title="notifications récente" data-placement="right" data-container="body" data-boundary="window">
                    <i class="flaticon2-bell-alarm-symbol"></i>
                    <!--affiche le nobre de notifications non lu-->
                    <span id="nbr"></span>
                </a>
            </li>
			
            <!--<li class="kt-aside__nav-item">
                <a href="#" class="kt-aside__nav-link"  id="kt_quick_panel_toggler_btn">
                <i class="flaticon2-grids"></i>
                </a>
            </li>-->
			
            <li class="kt-aside__nav-item dropdown">
                <a href="#" class="kt-aside__nav-link" data-toggle="dropdown" data-offset="100px, -50px">
                    <i class="flaticon-globe"></i>
                </a>

            <div class="dropdown-menu dropdown-menu-fit dropdown-menu-left dropdown-menu-anim">
                <ul class="kt-nav kt-margin-t-10 kt-margin-b-10">
                    <li class="kt-nav__item kt-nav__item--active">
                        <a href="#" class="kt-nav__link">
                            <span class="kt-nav__link-icon"><img src="<?php echo assets_dir();?>media/flags/united-states.png" alt="" /></span>
                            <span class="kt-nav__link-text">English</span>
                        </a>
                    </li>
                    <li class="kt-nav__item">
                        <a href="#" class="kt-nav__link">
                            <span class="kt-nav__link-icon"><img src="<?php echo assets_dir();?>media/flags/la-france.png" alt="" /></span>
                            <span class="kt-nav__link-text">French</span>
                        </a>
                    </li>
                    <li class="kt-nav__item">
                        <a href="#" class="kt-nav__link">
                            <span class="kt-nav__link-icon"><img src="<?php echo assets_dir();?>media/flags/spain.png" alt="" /></span>
                            <span class="kt-nav__link-text">Spanish</span>
                        </a>
                    </li>
                    <li class="kt-nav__item">
                        <a href="#" class="kt-nav__link">
                            <span class="kt-nav__link-icon"><img src="<?php echo assets_dir();?>media/flags/germany.png" alt="" /></span>
                            <span class="kt-nav__link-text">German</span>
                        </a>
                    </li>
                </ul>					
            </div>

        </li>
            <li class="kt-aside__nav-item">
                <a href="#" class="kt-aside__nav-link" id="kt_offcanvas_toolbar_profile_toggler_btn">
        
                    <i class="flaticon2-hourglass-1 kt-hidden"></i>
                    <img class="kt-hidden-" alt="" src="<?php echo assets_dir();?>media/users/300_25.png">
                    <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                    <span class="kt-aside__nav-username kt-bg-brand kt-hidden">S</span>
                </a>
            </li>
        </ul>
	</div>
	<!-- end::Aside Bottom -->
</div>
<!-- end::Aside Primary -->




<script>
	$(document).ready(function () {

		/**affiche le nombre de notifications non lu */
		function count(){
    		$.ajax({
				method: "GET",
				url: "<?php echo base_url('count_notification');?>",
				dataType: "JSON",
				success: function(data){
					$("#nbr").html(data);
				}
			});
		}
		intervalId = setInterval(count, 1000); //86 400

	});
</script>


