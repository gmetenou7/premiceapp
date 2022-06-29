
    <!-- begin::Scrolltop -->
    <div id="kt_scrolltop" class="kt-scrolltop">
        <i class="fa fa-arrow-up"></i>
    </div>
    <!-- end::Scrolltop -->

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

        <!--begin::Page Vendors(used by this page) -->
            <script src="<?php echo assets_dir();?>plugins/custom/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script>
        <!--end::Page Vendors -->
    
        <!--begin::Page Scripts(used by this page) -->
            <script src="<?php echo assets_dir();?>js/pages/dashboard.js" type="text/javascript"></script>
        <!--end::Page Scripts -->

        <!--begin::Page Scripts(used by this page) -->
            <script src="<?php echo assets_dir();?>js/pages/components/forms/widgets/clipboard.js" type="text/javascript"></script>
        <!--end::Page Scripts -->


        <!--begin::Page Scripts(used by this page) -->
            <script src="<?php echo assets_dir();?>js/pages/components/forms/widgets/select2.js" type="text/javascript"></script>
        <!--end::Page Scripts -->


         <!--begin::Page Scripts(used by this page) -->
         <script src="<?php echo assets_dir();?>js/pages/components/forms/widgets/bootstrap-select.js" type="text/javascript"></script>
        <!--end::Page Scripts -->

        <!--begin::Page Scripts(used by this page) -->
            <script src="<?php echo assets_dir();?>js/pages/components/forms/widgets/autosize.js" type="text/javascript"></script>
        <!--end::Page Scripts -->
         <!--begin::Page Scripts(used by this page) -->
         <script src="<?php echo assets_dir();?>js/pages/components/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
        <!--end::Page Scripts -->

        <!--begin::Page Scripts(used by this page) -->
        <script src="<?php echo assets_dir();?>js/pages/components/forms/widgets/bootstrap-timepicker.js" type="text/javascript"></script>
        <!--end::Page Scripts -->
            
                  
    </body>
    <!-- end::Body -->
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