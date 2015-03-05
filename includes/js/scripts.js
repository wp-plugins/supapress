(function($) {
    window.supapress = window.supapress || {};

    supapress.$layout = null;

    supapress.gridAlign = function(maxHeight) {
        supapress.$layout.find('.image-wrapper').height(maxHeight);
        supapress.$layout.find('.image-wrapper > img').addClass('baselined');
    };

    supapress.setMaxCoverHeight = function() {
        var $images = supapress.$layout.find('.image-wrapper > img'),
            maxHeight = 0,
            count = 0;

        $images.on('load', function() {
            var $this = $(this);

            count++;

            if($this.height() > maxHeight) {
                maxHeight = $this.height();
            }

            if(count === $images.length) {
                return supapress.gridAlign(maxHeight);
            }

            return false;
        }).each(function() {
            if (this.complete) {
                $(this).trigger('load');
            }
        });
    };

    $.fn.supapressInit = function() {
        supapress.$layout = this;

        if(supapress.$layout.hasClass('isbn-grid')) {
            $(window).on('resize', function() {
                supapress.setMaxCoverHeight();
            });

            $(window).trigger('resize');
        }
    };

    $('div.supapress > div').supapressInit();
})(jQuery);