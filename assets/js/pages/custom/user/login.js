"use strict";
var KTLoginPage = function(){
    var e=function(){
        $("#kt_login_submit").
        click(function(e){
            e.preventDefault();
            var t = $(this), i = $("#kt_login_form"); 
                i.validate({
                    rules:{
                        username:{required:!0},
                        password:{required:!0}
                    }
                }),

            i.valid()&&(KTApp.progress(t[0]),

            setTimeout(
                function(){ KTApp.unprogress(t[0]) },
                2e3
            ),

            i.ajaxSubmit({
                url:"",
                success:function(e,r,s,a){
                    setTimeout(function(){
                        KTApp.unprogress(t[0]),function(e,t,i){
                            var r = $('<div class="alert alert-bold alert-solid-'+t+' alert-dismissible" role="alert">\t\t\t<div class="alert-text">'+i+'</div>\t\t\t<div class="alert-close"><i class="flaticon2-cross kt-icon-sm" data-dismiss="alert"></i></div>\t\t</div>');
                            e.find(".alert").remove(),
                            r.prependTo(e),
                            KTUtil.animateClass(r[0], "fadeIn animated")
                        }
                        (i,"danger","Incorrect username or password. Please try again.")
                    },
                    2e3)
                }
            })

        )})
    };

            return{
                init:function(){e()}
            }
}();

    jQuery(document).ready(
        function(){
            KTLoginPage.init()
        }
    );