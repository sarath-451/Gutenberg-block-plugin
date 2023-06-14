<?php
/*
Plugin Name: Custom Gutenberg Blocks
Description: Adds custom Gutenberg blocks for the header and feed sections.
Version: 1.0
Author: Sarath
*/

function custom_gutenberg_blocks_scripts() {
    wp_enqueue_script( 'feed-block-editor', plugin_dir_url(__FILE__) . 'blocks/feed-block.js', array('wp-blocks', 'wp-editor'), '1.0', true );
}
add_action('enqueue_block_editor_assets', 'custom_gutenberg_blocks_scripts');

function tes_register_acf_block_types() {
    acf_register_block_type( [
        'name'            => 'header_block',
        'title'           => __( 'Header Block' ),
        'description'     => __( 'uplers header block.' ),
        'render_template' => dirname( __file__ ) . '/blocks/header/header_block.php',
        'category'        => 'common',
        'icon'            => 'heading',
        'enqueue_style'   => plugin_dir_url( __FILE__ ) . '/blocks/header/css/header_block.css',
    ] );
}

if ( function_exists( 'acf_register_block_type' ) ) {
    add_action( 'acf/init', 'tes_register_acf_block_types' );
}

function register_feed_block() {
    register_block_type(
        'custom-gutenberg-blocks/feed-block',
        array(
            'editor_script'   => 'feed-block-editor',
            'render_callback' => 'render_feed_block',
        )
    );
        wp_enqueue_style(
            'feed-block-frontend-styles',
            plugin_dir_url(__FILE__) . 'blocks/feed_block.css',
            array('wp-edit-blocks')
        );
}
add_action('init', 'register_feed_block');

function render_feed_block($attributes) {
    $posts_per_page = 9;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'paged' => $paged
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $output = '<div class="container">';
        $output .= '<ul id="feed-block" class="feed-block">';

        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $post_title = get_the_title();
            $post_thumbnail = get_the_post_thumbnail($post_id, 'thumbnail');
            
            $post_date = get_the_date();
            $post_tags = get_the_tags();
			$post_link = get_permalink();
            $category = get_the_category(); 
            $output .= '<li>';
			$output .= '<a href="'.$post_link.'" class="blog_link">';
			if ($post_thumbnail) {
                $output .= '<div class="blog_thumb">';
                if($category){
                    $output .= '<span class="post_category">' .$category[0]->cat_name. '</span>';
                }
                $output .= get_the_post_thumbnail( $post_id, array( 346, 256) ). '</div>';   
            }
            $output .= '<div class="feed-body">';
            $output .= '<p class="date"><span class="post_date">' . $post_date . '</span>';
            if(get_field('read_time', $post_id)){
            $output .= ' | <span class="read_time">' . get_field('read_time', $post_id) .'</span>';
            }
            $output .= '</p>';
			$output .= '<h2 class="post_title">' . wp_trim_words( $post_title, 5 ) . '</h2>';
            $output .= '<p class="post_content">' . wp_trim_words( get_the_content(), 18) . '</p>';
            if ($post_tags) {
                $output .= '<p class="tags"><ul class="tag-ul">';
                foreach ($post_tags as $tag) {
                    $output .=  '<li class="tag-li">' . $tag->name . '</li>';
                }
                $output .= '</ul></p>';
            }
            $output .= '</div></a></li>';
        }

        $output .= '</ul>';
        $output .= '</div>';

        $total_pages = $query->max_num_pages;
        if ($paged < $total_pages) {
            $output .= '<button id="load-more" class="load-more" data-current-page="' . $paged . '" data-max-pages="' . $total_pages . '">Load More</button>';
        }
    } else {
        $output = 'No posts found.';
    }

    wp_reset_postdata();

    return $output;
}

function output_block_content($block_content, $block) {
    echo $block_content;
}
add_filter('render_block', 'output_block_content', 99, 2);

function cpt_staff_ajax_scripts()
{
    wp_register_script( 'loadmore-js', plugin_dir_url(__FILE__) . 'blocks/load_more.js', array('jquery'), '1.0', true );
    wp_enqueue_script( 'loadmore-js' );

	wp_localize_script('loadmore-js', 'ajax_posts', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'noposts' => __('No older posts found', 'uplers'),
	)
	);
}

add_action('wp_enqueue_scripts', 'cpt_staff_ajax_scripts');


// AJAX callback for loading more posts
function load_more_posts() {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = 9;
    header("Content-Type: text/html");
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'paged' => $page
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<ul id="response-feed-block">';
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $post_title = get_the_title();
            $post_content = get_the_content();
            $post_thumbnail = get_the_post_thumbnail($post_id, 'thumbnail');
            $post_date = get_the_date();
            $post_tags = get_the_tags();

            echo '<li>';
            echo '<h2>' . $post_title . '</h2>';
            echo '<p>' . $post_content . '</p>';
            if ($post_thumbnail) {
                echo '<div>' . $post_thumbnail . '</div>';
            }
            echo '<p><strong>Published Date:</strong> ' . $post_date . '</p>';
            if ($post_tags) {
                echo '<p><strong>Tags:</strong> ';
                foreach ($post_tags as $tag) {
                    echo $tag->name . ' ';
                }
                echo '</p>';
            }
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '';
    }

    wp_reset_postdata();

    die();
}
add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');
