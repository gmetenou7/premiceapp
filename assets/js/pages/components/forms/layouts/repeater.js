"use strict";var KTRepeaterDemo={init:function(){$(".kt-repeater").each(function(){$(this).repeater({show:function(){$(this).slideDown()},isFirstItemUndeletable:!0})})}};jQuery(document).ready(function(){KTRepeaterDemo.init()});