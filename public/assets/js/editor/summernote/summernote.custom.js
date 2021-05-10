var summernote_custom = {
    init: function () {
        $('.summernote').summernote({
            height: 100,
            tabsize: 2,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ]
        });
    }
};
(function ($) {
    "use strict";
    summernote_custom.init();
})(jQuery);
