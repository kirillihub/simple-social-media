<?php

echo "<tr role='row' "
. "data-title='" . esc_attr( $data['name'] ) . "' "
        . "data-product_id='" . $data['id'] . "' "
        . "data-temp_number='" . $temp_number . "' "
        . "data-quantity='" . $default_quantity . "' "
        . "id='product_id_" . $data['id'] . "' "
        . "class='" . esc_attr( $tr_class ) . "' "
        . "data-product_variations='" . $data_product_variations . "' "
        . "{$data_tax}>"; //Data Tax has come from Taxonomy or Mini Filter



$items_permanent_dir = WPT_DIR_BASE . 'includes/items/';
$items_permanent_dir = apply_filters('wpto_item_permanent_dir', $items_permanent_dir, $table_ID, $product );
$items_directory = apply_filters('wpto_item_dir', $items_permanent_dir, $table_ID, $product );
foreach( $table_column_keywords as $keyword => $keyword_title ){
    if( is_string( $keyword ) ){
        $in_extra_manager = false;

        /**
         * Variable $setting for All Keyword/Items
         * User able get Diect setting Variable from Items file
         * Such: from action.php file Directory: WPT Plugin -> items -> action.php
         * Setting Declear For All Items (TD or Inside Item of TD
         * @since version 2.7.0
         */
        $settings = isset( $column_settings[$keyword] ) ? $column_settings[$keyword] : false;

        /**
         * @Hook Filter: wpto_keyword_settings_$keyword
         * Each Column/ Each Item/ Each Item has Indivisual Setting.\
         * User able to chagne Setting from Addon Plugin
         * 
         * Suppose Custom_Field.php file using following Setting
         * $settings = isset( $column_settings[$keyword] ) ? $column_settings[$keyword] : false;
         */
        $settings = apply_filters( 'wpto_keyword_settings_' . $keyword, $settings, $column_settings, $table_ID, $product  );

        /**
         * New Feature, Mainly for detect File Name. 
         * Such: for custom_field type, Scrip will load custom_field.php file inside Items Directory
         * Default value: default or empty
         */
        $type = isset( $column_settings[$keyword]['type'] ) && !empty( $column_settings[$keyword]['type'] ) ? $column_settings[$keyword]['type'] : 'default';

        /**
         * Type: It's a Type of column, Normally all type default, but can be different. such: custom_file, taxonomy, acf etc
        * @Hook Filter: wpto_column_type
        * When sole same type colum will open from one file, than use Type.
        * Such: For custom field, use one file named: custom_field.php
        * Same for Taxonomy.\
        * Like this, we can add new type: acf, when file will open from acf.php from items
        */
        $type = apply_filters( 'wpto_column_type', $type, $keyword, $table_ID, $product, $settings, $column_settings );

        /**
         * @Hook Filter: wpto_template_folder
         * Items Template folder location for Each Keyword,
         * such: product_title, product_id, content,shortcode etc
         * 
         * Abble to change Template Root Directory Based on $keyword, $column_type, $table_ID, Global $product
         * 
         */
        $items_directory_1 = apply_filters('wpto_template_folder', $items_directory,$keyword, $type, $table_ID, $product, $settings, $column_settings );


        /**
         * @Hook Filter: wpto_item_dir_type_($type) change directory based on Column type
         * Items folder Directory based on Colum Type.
         * Such: For all default type column, request file available in includes/items folder
         * but for other type, such: acf, custom_field,taxonomy, we can set another Directory location from Addons or fro Pro version
         */
        $items_directory_2 = apply_filters('wpto_item_dir_type_' . $type, $items_directory_1, $table_ID, $product, $settings, $column_settings ); //Added Filter

        $file_name = $type !== 'default' ? $type : $keyword;
        $file = $items_directory_2 . $file_name . '.php';

        $file = apply_filters( 'wpto_template_loc', $file, $keyword, $type, $table_ID, $product, $file_name, $column_settings, $settings ); //@Filter Added 
        $file = apply_filters( 'wpto_template_loc_type_' . $type, $file, $keyword, $table_ID, $product, $file_name, $column_settings, $settings ); //@Filter Added
        $file  = $requested_file = apply_filters( 'wpto_template_loc_item_' . $keyword, $file, $table_ID, $product, $file_name, $column_settings, $settings ); //@Filter Changed added Args $fileName

        if( !file_exists( $file ) ){
            $file = $items_permanent_dir . 'default.php';
            $file = apply_filters( 'wpto_defult_file_loc', $file, $keyword, $product, $settings);
        }

        $style_str = isset( $column_settings[$keyword]['style_str'] ) && !empty( $column_settings[$keyword]['style_str'] ) ? $column_settings[$keyword]['style_str'] : '';
        
        $td_class_arr = array(
            "td_or_cell",
            "wpt_" . $keyword,
            "wpt_temp_" . $temp_number,
        );
        $td_class_arr = apply_filters( 'wpto_td_class_arr', $td_class_arr, $keyword, $table_ID, $args, $column_settings, $table_column_keywords, $product );
        $td_class = implode( " ", $td_class_arr );
        
        ?>
        <td class="<?php echo esc_attr( $td_class ); ?>"  
            data-keyword="<?php echo esc_attr( $keyword ); ?>" 
            data-temp_number="<?php echo esc_attr( $temp_number ); ?>" 
            data-sku="<?php echo esc_attr( $product->get_sku() ); ?>"
            style="<?php echo esc_attr( $style_str ); ?>"
            >    
            <?php
            //*****************************FILE INCLUDING HERE
            $tag = isset( $column_settings[$keyword]['tag'] ) && !empty( $column_settings[$keyword]['tag'] ) ? $column_settings[$keyword]['tag'] : 'div';
            $tag_class = isset( $column_settings[$keyword]['tag_class'] ) && !empty( $column_settings[$keyword]['tag_class'] ) ? $column_settings[$keyword]['tag_class'] : '';
            echo $tag ? "<$tag "
            . "class='" . esc_attr( $keyword ) . " " . esc_attr( $tag_class ) . "' "
            . "data-keyword='" . esc_attr( $keyword ) . "' "
            . "data-sku='" . esc_attr( $product->get_sku() ) . "' "
            . ">" : '';

            do_action( 'wpto_column_top', $keyword, $table_ID, $settings, $column_settings, $product );

            //Including File for TD
            include $file;
            echo $tag ? "</$tag>" : '';
            if( isset( $column_settings[$keyword]['items'] ) ){
                include __DIR__ . '/extra_items_manager.php';
            }

            do_action( 'wpto_column_bottom', $keyword, $table_ID, $settings, $column_settings, $product );
             ?>
        </td>    
        <?php

    }
}











//$html .= wpt_generate_each_row_data($table_column_keywords, $wpt_each_row);
echo "</tr>"; //End of Table row