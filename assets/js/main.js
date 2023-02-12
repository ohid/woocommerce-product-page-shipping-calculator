(function( $, window, document, undefined ) {
	"use strict";

    function shippingCalculator() {
        this.init = function() {
            this.initSelect2();
            this.openCalculator();
            this.onloadShippingMethod();
            this.onFormSubmit();
        }

        this.initSelect2 = function() {
            $( document ).ready(function() {
                var $select = $('form.wppsc-woocommerce-shipping-calculator select').select2({
                    dropdownCssClass: 'wppsc-select2-dropdown'
                });
            });
        },

        this.openCalculator = function() {
            $( document ).on( 'click', '.wppsc-shipping-calculator-button', function( e ) {
                e.preventDefault();
                $('.wppsc-shipping-calculator-form').toggle();
            });
        }

        this.onFormSubmit = function() {
            var parent = this;
            $( document ).on("submit", "form.wppsc-woocommerce-shipping-calculator", { parent: parent }, parent.onSubmitShippingCalculator);
        }

        this.onSubmitShippingCalculator = function (t) {
			t.preventDefault();
            var data = t.data;
			data.parent.onloadShippingMethod();
		}

		this.onloadShippingMethod = function () {
			var e = $('form.wppsc-woocommerce-shipping-calculator');
			this.getMethods(e);
		}

        this.getMethods = function(e) {
			var parent = this;
			this.loading();

            var action = $('input[type="hidden"][name="action"]', e).val();

            $.ajax({
				type: e.attr("method"),
				url: wppsc_script.ajaxurl,
				data: e.serialize(),
				dataType: "json",
				success: function (data) {
                    if ( !data.success ) {
                        return;
                    }
                    $('#wppsc-shipping-message').html(data.data.shipping_methods);
				}
			}).always(function () {
				parent.removeLoading();
			});
        }

        this.loading = function() {
			$('body').addClass('wppsc-processing');
        }

		this.removeLoading = function () {
			$('body').removeClass('wppsc-processing');
		}
    }

    jQuery(function($) {
        var shippingCalculatorObj = new shippingCalculator();
        shippingCalculatorObj.init();
    })

})( jQuery, window, document );