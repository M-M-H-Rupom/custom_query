<?php
/**
 * Plugin Name: custom query 
 * Author: Rupom
 * Description: plugin description
 * Version: 1.0
 *
 */
function custom_post_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'ids' => '',
        ),
        $atts
    );
    $posts_array = explode(',', $atts['ids']);
    $args = array(
        'post__in'       => $posts_array,
        'orderby'        => 'post__in', 
    );
    $posts = get_posts($args);
    $output = '<ul>';
    foreach ($posts as $post) {
        $output .= '<h5><a href="' . get_the_permalink($post) . '">' . get_the_title($post) . '</a></h5>';
    }
    $output .= '</ul>';
    wp_reset_postdata();
    return $output;
}
add_shortcode('custom_posts', 'custom_post_shortcode');

// wp query shortcode 
function callback_for_wp_query(){
    $paged = get_query_var('paged') ? get_query_var('paged') : '1';
    $post_ids = array(42, 93, 95, 25); // Your specific post IDs
    $args = array(
        'post_type'=> 'page',
        'posts_per_page' => 2,
        'paged' => $paged,
        'post__in' => $post_ids,
    );
    $a_query = new WP_Query($args);
    $output = '';
    while($a_query->have_posts()){
        $a_query->the_post();
        $output .= '<h2><a href="'.get_permalink().'">' . get_the_title() . '</a></h2>';
    }
    wp_reset_query();
    $total_pages = ceil(count($post_ids) / $args['posts_per_page']);
    $output .= paginate_links(array(
        'total' => $total_pages,
        'current' => $paged,
        'prev_next'=> false,
    ));
    return $output;
}
add_shortcode('wp_query1', 'callback_for_wp_query');
// shortcode for query 2
function callback_for_wp_query2(){
    $args = array(
        'post_type' => 'post',
        'post_status' => 'draft',
        // 'category_name' => 'cars',

        'tax_query' => array(
            // 'relation' => 'OR',
            // array(
            //     'taxonomy'=> 'category',
            //     'field' => 'slug',
            //     'terms' => array('cars'),
            // ),
            // array(
            //     'taxonomy'=> 'post_tag',
            //     'field' => 'slug',
            //     'terms' => array('sports'),
            // )
            ),    
    );
     // $args = array(
    //     'date_query' => array(
    //        array(
    //         'year' => 2024,
    //         'month' => 03,
    //        ),
    //     ),
    //     );
    $output ='';
    $a_query = new WP_Query($args);
    while($a_query->have_posts()){
        $a_query->the_post();
        $output .= '<h3><a href="'. get_permalink().'">'. get_the_title() . " ". get_the_ID().'</a></h3>';
    }
    wp_reset_query();
    return $output;
}
add_shortcode('wp_query2', 'callback_for_wp_query2');
function callback_for_wp_query3(){
    // $args = array(
    //     'post_type' => 'post',
    //     'meta_query' => array(
    //         'relation' => 'OR',
    //         array(
    //             'key' => 'fav_color'
    //         ),
    //         array(
    //             'key' => 'new_color'
    //         ),
    //     )
    // );
    // $args = array(
    //     'post_type' => 'post',
    //     'meta_key' => 'new_color',
    // );
    $args = array(
        'post_type' => 'post',
        'tax_query' => array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => array('cars')
            ),
            array(
                'taxonomy' => 'post_format',
                'field' => 'slug',
                'terms' => array('post-format-image')
            )
        )
    );
    $a_post = new WP_Query($args);
    $output = '';
    while($a_post->have_posts()){
        $a_post->the_post();
        $output .= '<h2><a href="' .get_permalink() .'">'.get_the_title().'</a></h2>';
    }
    wp_reset_query();
    return $output;
}
add_shortcode( 'wp_query3', 'callback_for_wp_query3');

// function callback_for_pre_post_not_in($wpq){
//     if(is_home()){
//         $wpq->set('post__not_in',array(25,42));
//     }
// }
// add_action( 'pre_get_posts', 'callback_for_pre_post_not_in' );
?>