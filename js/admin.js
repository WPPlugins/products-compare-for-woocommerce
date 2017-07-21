(function ($){
    $(document).ready( function () {
        $('.berocket_compare_products_styler .colorpicker_field').each(function (i,o){
            $(o).css('backgroundColor', '#'+$(o).data('color'));
            $(o).colpick({
                layout: 'hex',
                submit: 0,
                color: '#'+$(o).data('color'),
                onChange: function(hsb,hex,rgb,el,bySetColor) {
                    $(el).css('backgroundColor', '#'+hex).next().val(hex);
                }
            })
        });
        $(document).on('click', '.berocket_compare_products_styler .theme_default', function (event) {
            event.preventDefault();
            var data = $(this).prev().data('default');
            $(this).prev().prev().css('backgroundColor', '#' + data).colpickSetColor('#' + data);
            $(this).prev().val(data);
        });

        $(document).on('click', '.berocket_compare_products_styler .all_theme_default', function (event) {
            event.preventDefault();
            $table = $(this).parents('table');
            $table.find('.colorpicker_field').each( function( i, o ) {
                $(o).css('backgroundColor', '#' + $(o).next().data('default')).colpickSetColor('#' + $(o).next().data('default'));
                $(o).next().val($(o).next().data('default'));
            });
            $table.find('select').each( function( i, o ) {
                $(o).val($(o).data('default'));
            });
            $table.find('input[type=text]').each( function( i, o ) {
                $(o).val($(o).data('default'));
            });
        });
    });
})(jQuery);