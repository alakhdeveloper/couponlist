define([
    'ko',
    'jquery',
    'uiComponent' 
    ], function (ko, $, Component) {
        'use strict'; 
        return Component.extend({ 
            defaults: { 
                template: 'Diligent_CouponList/coupon/link' 
            } 
        }); 
    } 
);
