
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
                            <h3 class="kt-portlet__head-title">Changer le service, l'agence d'un utilisateur</h3>
                        </div>
                    </div>
			        <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-outline-brand" data-toggle="modal" data-target=".bd-example-modal-xl">Nouvel Mutation</button>
                            
                                <!-- Large Modal -->
                                <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Creer Un Utilisateur</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Modal body text goes here.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-brand" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-outline-brand">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <hr class="text-primary">
                        <div class="row">
                            <div class="col-xl-4 col-lg-6">
                                <!--begin::Portlet-->
                                <div class="kt-portlet kt-portlet--height-fluid">
                                    <div class="kt-widget kt-widget--general-2">
                                        <div class="kt-portlet__body kt-portlet__body--fit">
                                            <div class="kt-widget__top">
                                                <div class="kt-media kt-media--lg kt-media--circle">
                                                    <img src="<?php echo assets_dir();?>media/users/100_6.jpg" alt="image">
                                                </div>
                                                <div class="kt-widget__wrapper">
                                                    <div class="kt-widget__label">
                                                        <a href="#" class="kt-widget__title">
                                                            Luke Davids
                                                        </a>
                                                        <span class="kt-widget__desc">
                                                            Angular Developer
                                                        </span>
                                                    </div>     
                                                    <div class="kt-widget__toolbar">
                                                        <a href="#" class="btn btn-icon btn-circle btn-label-facebook">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-icon btn-circle btn-label-twitter">
                                                            <i class="fa fa-eye"></i>
                                                        </a> 
                                                        <a href="#" class="btn btn-icon btn-circle btn-label-instagram">
                                                            <i class="fa fa-eye-slash"></i>
                                                        </a> 
                                                    </div>   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Portlet-->
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

