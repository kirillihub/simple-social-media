<?php 
namespace WOO_PRODUCT_TABLE\Inc\Handle;
use WOO_PRODUCT_TABLE\Inc\Shortcode;

class Element{
    public static function loadMore( Shortcode $shortcode ){
        
        $config = $shortcode->_config;
        $text_btn = $config['load_more_text'] ?? '';
        $text_loading = $config['loading_more_text'] ?? '';
        ?>
    <div id="wpt_load_more_wrapper_<?php echo esc_attr( $shortcode->table_id ); ?>" 
    class="wpt_load_more_wrapper">

        <button data-table_id="<?php echo esc_attr( $shortcode->table_id ); ?>" 
        data-page_number="2"
        data-text_btn="<?php echo esc_attr( $text_btn ); ?>"
        data-text_loading="<?php echo esc_attr( $text_loading ); ?>"
        class="button wpt_load_more">
            <?php echo esc_html( $text_btn ); ?>
        </button>
    </div>
        <?php
    }

    /**
     * Instance Search just before Mini Filter Box.
     * need to enable from configuration page.
     *
     * @param Shortcode $shortcode
     * @return void
     */
    public static function instance_search( Shortcode $shortcode ){
        $placeholder = $shortcode->_config['instant_search_text'] ?? '';
        ?>
        <div class='instance_search_wrapper'>
        <input data-temp_number='<?php echo esc_attr( $shortcode->table_id ); ?>' 
        placeholder='<?php echo esc_attr( $placeholder ); ?>' 
        class='instance_search_input'>   
        </div>
        <?php 
    }
}