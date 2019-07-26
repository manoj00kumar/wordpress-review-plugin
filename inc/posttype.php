<?php

function register_cli_survey() {

   register_post_type('cli_survey', array(
         'label'           => 'Survey',
         'description'     => 'Add survey shortcode',
         'public'          => true,
         'show_ui'         => true,
         'show_in_menu'    => true,
         'capability_type' => 'post',
         'map_meta_cap'    => true,
         'hierarchical'    => false,
         'rewrite'         => array(
                                 'slug'       => 'cli_survey',
                                 'with_front' => true
                              ),
         'query_var'       => true,
         'exclude_from_search' => true,
         'menu_position'   => 5,
         'supports'        => array('title','editor', 'page-attributes','custom_field',"author","taxonomy"),
         'taxonomies'      => array('cli_survey_cat'),
         'labels'          => array (
                                'name'               => 'surveys',
                                'singular_name'      => 'Survey',
                                'menu_name'          => 'Surveys',
                                'add_new'            => 'Add survey',
                                'add_new_item'       => 'Add New Survey',
                                'edit'               => 'Edit',
                                'edit_item'          => 'Edit Survey',
                                'new_item'           => 'New Survey',
                                'view'               => 'View Survey',
                                'view_item'          => 'View survey',
                                'search_items'       => 'Search Survey',
                                'not_found'          => 'No FAQs Found',
                                'not_found_in_trash' => 'No FAQs Found in Trash',
                                'parent'             => 'Parent survey',
                              )

) );
        
        $labels = array(
            'name'              => _x( 'Survey Categories', 'taxonomy general name' ),
            'singular_name'     => _x( 'Survey Category', 'taxonomy singular name' ),
            'search_items'      =>  __( 'Search survey Categories' ),
            'all_items'         => __( 'All survey Category' ),
            'parent_item'       => __( 'Parent Survey Category' ),
            'parent_item_colon' => __( 'Parent Survey Category:' ),
            'edit_item'         => __( 'Edit survey Category' ),
            'update_item'       => __( 'Update survey Category' ),
            'add_new_item'      => __( 'Add New Survey Category' ),
            'new_item_name'     => __( 'New Survey Category Name' ),
            'menu_name'         => __( 'Survey Category' ),
        );
    
        register_taxonomy('cli_survey_cat',array('cli_survey'), array(
            'hierarchical' => true,
            'labels'       => $labels,
            'show_ui'      => false,
            'query_var'    => true,
            'rewrite'      => array( 'slug' => 'cli_survey_cat' ),
        ));                      
}

add_filter( 'manage_cli_survey_posts_columns', 'set_custom_survey_columns' );
add_action( 'manage_cli_survey_posts_custom_column' , 'custom_survey_column', 10, 2 );

function set_custom_survey_columns($columns) {
    
    $columns['shortcode'] = __( 'shortcode', 'your_text_domain' );
   

    return $columns;
}

function custom_survey_column( $column, $post_id ) {
    switch ( $column ) {

        case 'shortcode' :
            $terms = get_the_term_list( $post_id , 'shortcode' , '' , ',' , '' );
           
                echo "[collective_survey]";
            

        

    }
}