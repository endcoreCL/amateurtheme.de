// JavaScript Document
(function($) {
    $(document).ready(function(){
        function xcore_get_preview_image(value) {
            var result = value.split('/');
            var path = value.replace(result.pop(), '');
            var image = template_directory_uri + '/' + path + 'preview.jpg';

            return image;
        }
        /**
         * Design / Layout / Topbar Preview
         */
        function xcore_topbar_preview() {
            var value = $('#acf-field_5a7d99e7b111e').val();
            if(value) {
                var image = xcore_get_preview_image(value);

                $('.acf-field-5a7d99fcb111f .acf-input').html('<img src="' + image + '" class="img-fluid" />');
            } else {
                $('.acf-field-5a7d99fcb111f .acf-input').html('');
            }
        }

        xcore_topbar_preview();

        $('#acf-field_5a7d99e7b111e').on('change', function() {
            xcore_topbar_preview();
        });

        /**
         * Design / Layout / Header Preview
         */
        function xcore_header_preview() {
            var value = $('#acf-field_5a7d5c42dc7b0').val();
            if(value) {
                var image = xcore_get_preview_image(value);

                $('.acf-field-5a7d5c5cdc7b1 .acf-input').html('<img src="' + image + '" class="img-fluid" />');
            } else {
                $('.acf-field-5a7d5c5cdc7b1 .acf-input').html('');
            }
        }

        xcore_header_preview();

        $('#acf-field_5a7d5c42dc7b0').on('change', function() {
            xcore_header_preview();
        });

        /**
         * Design / Layout / Teaser Preview
         */
        function xcore_teaser_preview() {
            var value = $('#acf-field_5a7d6f9fb1497').val();
            if(value) {
                var image = xcore_get_preview_image(value);

                $('.acf-field-5a7d6faeb1498 .acf-input').html('<img src="' + image + '" class="img-fluid" />');
            } else {
                $('.acf-field-5a7d6faeb1498 .acf-input').html('');
            }
        }

        xcore_teaser_preview();

        $('#acf-field_5a7d6f9fb1497').on('change', function() {
            xcore_teaser_preview();
        });

        /**
         * Design / Layout / Content Preview
         */
        function xcore_content_preview() {
            var value = $('#acf-field_5a7d6fb4b1499').val();
            if(value) {
                var image = xcore_get_preview_image(value);

                $('.acf-field-5a7d6fceb149a .acf-input').html('<img src="' + image + '" class="img-fluid" />');
            } else {
                $('.acf-field-5a7d6fceb149a .acf-input').html('');
            }
        }

        xcore_content_preview();

        $('#acf-field_5a7d6fb4b1499').on('change', function() {
            xcore_content_preview();
        });

        /**
         * Design / Layout / Footer Preview
         */
        function xcore_footer_preview() {
            var value = $('#acf-field_5a7d6fd4b149b').val();
            if(value) {
                var image = xcore_get_preview_image(value);

                $('.acf-field-5a7d6fe0b149c .acf-input').html('<img src="' + image + '" class="img-fluid" />');
            } else {
                $('.acf-field-5a7d6fe0b149c .acf-input').html('');
            }
        }

        xcore_footer_preview();

        $('#acf-field_5a7d6fd4b149b').on('change', function() {
            xcore_footer_preview();
        });
    });
})(jQuery);