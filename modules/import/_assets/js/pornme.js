jQuery(document).ready(function (e) {
    /**
     * Get amateur select
     */
    jQuery('.at-amateur-select').select2({
        ajax: {
            url: ajaxurl,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    action: 'at_pornme_amateurs'
                };
            },
            processResults: function (data) {
                var options = [];
                if (data) {
                    jQuery.each(data, function (index, text) {
                        options.push({id: text[0], text: text[1]});
                    });
                }
                return {
                    results: options
                };
            },
            cache: true
        },
        minimumInputLength: 2
    });
});