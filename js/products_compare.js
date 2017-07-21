(function ($){
    $(document).ready( function () {
        $(document).on( 'click', '.br_compare_button .fa', function ( event ) {
            $button = $(this).parent();
            event.preventDefault();
            event.stopPropagation();
            var id = $button.data('id');
            toggle_products_compare(id);
            load_selected_list();
        });
        $(document).on( 'click', '.br_compare_button', function ( event ) {
            if ( add_products_compare($(this).data('id')) ) {
                event.preventDefault();
                load_selected_list();
            }
        });
        $(document).on( 'click', '.br_remove_compare_product', function ( event ) {
            event.preventDefault();
            compare_products_execute_func( the_compare_products_data.user_func.before_remove );
            var $parent = $(this).parents('.berocket_compare_widget');
            remove_products_compare($(this).data('id'));
            $('.br_widget_compare_product_'+$(this).data('id')).remove();
            has_compared_products( $parent.find('ul li').length );
            compare_products_execute_func( the_compare_products_data.user_func.after_remove );
            if( typeof(br_compare_page) != 'undefined' ) {
                location.replace(br_compare_page);
            }
        });
        $(document).on( 'click', '.br_remove_compare_product_reload', function ( event ) {
            event.preventDefault();
            remove_products_compare($(this).data('id'));
            if($(this).parents('.br_compare_popup').length > 0) {
                $('.br_compare_popup').remove();
                load_selected_list();
                $('.berocket_open_compare').click();
            } else {
                location.replace(br_compare_page);
            }
        });
        function load_selected_list() {
            if ( $('.berocket_compare_widget').length > 0 ) {
                compare_products_execute_func( the_compare_products_data.user_func.before_load );
                var data = {action: 'br_get_compare_products'};
                $.post( the_compare_products_data.ajax_url, data, function ( data ) {
                    var $data = $(data).find('.berocket_compare_widget');
                    var $data_text = $(data).find('.berocket_compare_widget');
                    $data_text.find('img').remove();
                    $('.berocket_compare_widget').each( function ( i, o ) {
                        if ( $(o).data('type') == 'text' ) {
                            $(o).html($data_text.html());
                        } else {
                            $(o).html($data.html());
                        }
                        if( $(o).data('fast_compare') ) {
                            $(o).find('.berocket_open_compare').addClass('berocket_open_smart_compare');
                        }
                    });
                    has_compared_products( $data.find('ul li').length );
                    compare_products_execute_func( the_compare_products_data.user_func.after_load );
                });
            }
        }
        function has_compared_products( count ) {
            if ( count > 0 ) {
                $('.berocket_show_compare_toolbar').show();
            } else {
                $('.berocket_compare_widget_toolbar').hide();
                $('.berocket_show_compare_toolbar').hide();
                $('.berocket_open_compare').remove();
            }
        }
    });
})(jQuery);
function toggle_products_compare( product ) {
    var products = set_get_products_compare_cookie();
    if ( ! products || products.search( new RegExp("(^" + product + "$)|(^" + product + ",)|(," + product + "$)|(," + product + ",)") ) == -1 ) {
        add_products_compare( product );
        return true;
    } else {
        remove_products_compare( product );
        return false;
    }
}
function add_products_compare( product ) {
    var products = set_get_products_compare_cookie();
    jQuery('.br_product_' + product).addClass('br_compare_added');
    jQuery('.br_product_' + product).find('span.br_compare_button_text').text(jQuery('.br_product_' + product).find('span.br_compare_button_text').data('added'));
    if ( ! products || products.search( new RegExp("(^" + product + "$)|(^" + product + ",)|(," + product + "$)|(," + product + ",)") ) == -1 ) {
        if ( products !== false && products ) {
            products = products + ',' + product;
        } else {
            products = '' + product;
        }
        set_get_products_compare_cookie( products );
        return true;
    } else {
        set_get_products_compare_cookie( products );
        return false;
    }
}
function remove_products_compare( product ) {
    var products = set_get_products_compare_cookie();
    if ( products !== false && products ) {
        products = products.replace( new RegExp("(^" + product + "$)|(^" + product + ",)|(," + product + "$)"), '' );
        products = products.replace( new RegExp("," + product + ","), ',' );
    }
    jQuery('.br_product_' + product).removeClass('br_compare_added');
    jQuery('.br_product_' + product).find('span.br_compare_button_text').text(jQuery('.br_product_' + product).find('span.br_compare_button_text').data('not_added'));
    set_get_products_compare_cookie(products);
    return products;
}
function set_get_products_compare_cookie ( value ) {
    if ( typeof value === "undefined" ) {
        value = false;
    }
    if ( value === false ) {
        if ( jQuery.cookie( 'br_products_compare' ) ) {
            return jQuery.cookie( 'br_products_compare' );
        } else {
            return false;
        }
    } else {
        var path = the_compare_products_data.home_url;
        path = path.split(document.domain);
        path = path[1];
        if ( path == '' ) {
            path == '/';
        }
        jQuery.cookie( 'br_products_compare', value, { path: path, domain: document.domain } );
    }
}
function compare_products_execute_func ( func ) {
    if( the_compare_products_data.user_func != 'undefined'
        && the_compare_products_data.user_func != null
        && typeof func != 'undefined' 
        && func.length > 0 ) {
        try{
            eval( func );
        } catch(err){
            alert('You have some incorrect JavaScript code (Product Compare)');
        }
    }
}