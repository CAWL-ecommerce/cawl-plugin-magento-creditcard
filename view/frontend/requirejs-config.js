var config = {
    map: {
        '*': {
            'Amasty_CheckoutStyleSwitcher/template/onepage/place-order.html':
                'Cawl_CreditCard/template/compatibility/amasty-osc/place-order.html',
            'Amasty_CheckoutStyleSwitcher/js/action/start-place-order':
                'Cawl_CreditCard/js/compatibility/amasty-osc/start-place-order'
        }
    },

    config: {
        mixins: {
            'Amasty_CheckoutStyleSwitcher/js/view/place-button': {
                'Cawl_CreditCard/js/compatibility/amasty-osc/place-button-mixin': true
            },
            'Amasty_CheckoutCore/js/view/checkout/summary/item/details': {
                'Cawl_CreditCard/js/compatibility/amasty-osc/view/checkout/summary/item/details-mixin': true
            }
        }
    }
};
