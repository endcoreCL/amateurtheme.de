// JavaScript Document
(function($) {
    $(document).ready(function() {
        /**
         * Automatically trigger recompile when color_picker changes
         *
         * @type {jQuery|HTMLElement}
         */
        var design_recompile = jQuery('[data-name="design_recompile"]');
        jQuery('.framework_page_acf-options-design [data-type="color_picker"]').change( function() {
            if ( design_recompile.find('input[type="checkbox"]').prop('checked') != true ) {
                jQuery(design_recompile).find('.acf-label label').click();
            }
        });
    });
})(jQuery);