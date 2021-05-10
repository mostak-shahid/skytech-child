<?php
function add_slug_body_class( $classes ) {
    global $post;
    if ( isset( $post ) AND $post->post_type == 'page' ) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    } else {
        $classes[] = $post->post_type . '-archive';
    }
    return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

add_action( 'astra_template_parts_content', 'mos_author_details_func', 14 );
function mos_author_details_func(){
    if(is_single() && get_post_type()=='post') :
    ?>
    <div class="mos-post-autor-details">
        <div class="img-part"><?php echo get_avatar(get_the_author_meta('ID'),120) ?></div>
        <div class="text-part">
            <h4 class="author-name" itemprop="name"><a href="<?php echo get_the_author_meta('user_url') ?>" title="View all posts by <?php echo get_the_author_meta('display_name') ?>" rel="author" class="url fn n" itemprop="url"><?php echo get_the_author_meta('display_name') ?></a></h4>
            <div class="author-description" itemprop="name"><?php echo get_the_author_meta('description') ?></div>
        </div>
    </div>
    <?php
    endif;
}
add_action('astra_primary_content_bottom','mos_related_posts_func');
function mos_related_posts_func(){
    if(is_single() && get_post_type()=='post'):
        $term_ids = [];
        $categories = get_the_category(get_the_ID());
        foreach($categories as $category){
            $term_ids[] = $category->term_id;
        }
        //var_dump(implode(',',$term_ids));
        $args = array(
            'posts_per_page' => 6,
            'cat' => implode(',',$term_ids),
            'post__not_in' => array(get_the_ID())
        );
        // The Query
        $the_query = new WP_Query( $args );

        // The Loop
        if ( $the_query->have_posts() ) : ?>
        <div class="related-post">
            <h2 class="section-title"><?php echo __('Related Posts') ?></h2>
            
            <div class="related-post-wrapper">
                <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

                    <div class="post-content">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="ast-blog-featured-section post-thumb">
                                <div class="post-thumb-img-content post-thumb"><a href="<?php echo get_the_permalink() ?>"><img width="373" height="210" src="<?php echo aq_resize(get_the_post_thumbnail_url('','full'), 384, 210, true) ?>" class="attachment-373x250 size-373x250 wp-post-image" alt="office cleaning safety tips - janitorial leads pro" loading="lazy" itemprop="image"></a></div>
                            </div>
                        <?php endif;?>
                        <div class="related-entry-header">
                            <h4 class="related-entry-title" itemprop="headline"><a href="<?php echo get_the_permalink() ?>" rel="bookmark"><?php echo get_the_title() ?></a></h4>
                        </div>
                    </div>       
               
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif;
        /* Restore original Post Data */
        wp_reset_postdata();        
    endif;
}

/**
 * Detect plugin. For use on Front End only.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
 
// check for plugin using plugin name
if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    function is_shop(){
        return false;
    }
}
add_action('astra_content_top','mos_custom_header');
function mos_custom_header(){
    if (is_home()) :
        $page_for_posts = get_option( 'page_for_posts' );
    ?>       
        <header class="entry-header ast-no-thumbnail ast-no-meta"><h1 class="entry-title" itemprop="headline"><?php echo get_the_title($page_for_posts) ?></h1></header>    
    <?php
    elseif (is_shop()) :
        $page_for_products = get_option( 'woocommerce_shop_page_id' );
    ?>       
        <header class="entry-header ast-no-thumbnail ast-no-meta"><h1 class="entry-title" itemprop="headline"><?php echo get_the_title($page_for_products) ?></h1></header>    
    <?php
    elseif ( is_single() && 'product' == get_post_type() ) :
    ?>       
        <header class="entry-header ast-no-thumbnail ast-no-meta"><h1 class="entry-title" itemprop="headline"><?php echo get_the_title(get_the_ID()) ?></h1></header>    
    <?php
    endif;
}
add_action('astra_single_post_title_after', 'custom_mos_post_meta');
if ( ! function_exists( 'custom_mos_post_meta' ) ) {
	function custom_mos_post_meta() {
        if ('job' == get_post_type()) :
            $job_type = carbon_get_post_meta( get_the_ID(), 'job-job-type' );
            $nov = carbon_get_post_meta( get_the_ID(), 'job-nov' );
            $deadline = carbon_get_post_meta( get_the_ID(), 'job-deadline' );
            $link = carbon_get_post_meta( get_the_ID(), 'job-link' );
            ?>
            <div class="entry-meta mos-entry-meta job-item-meta">
                <?php if ($job_type) : ?><span class="type">Job Type: <?php echo $job_type; ?></span><?php endif;?>
                <?php if ($nov) : ?><span class="nof">No. of Vacancies: <?php echo $nov; ?></span><?php endif;?>
                <?php if ($deadline) : ?><span class="deadline">Deadline: <?php echo date_format(date_create($deadline),"F j, Y"); ?></span><?php endif;?>
            </div>
            <?php if ($link) :?>
            <div class="wp-block-buttons is-content-justification-center">
                <div class="wp-block-button"><a href="<?php echo esc_url(do_shortcode($link)) ?>" class="wp-block-button__link">Apply Now</a></div>
            </div>
        <?php endif;?>
        <?php        
        elseif ('event' == get_post_type()) :
            $date = carbon_get_post_meta( get_the_ID(), 'event-date' );
            $location = carbon_get_post_meta( get_the_ID(), 'event-location' );
            ?>
            <div class="entry-meta mos-entry-meta event-item-meta">
                <?php if ($date) : ?><span class="nof"><i class="fa fa-calendar"></i> <?php echo date_format(date_create($date),"F j, Y"); ?></span><?php endif;?>
                <?php if ($location) : ?><span class="type"><i class="fa fa-map-marker"></i> <?php echo $location; ?></span><?php endif;?>
            </div>
        <?php
        endif;
	}
}
add_action('astra_template_parts_content', 'custom_job_post_button', 11);
if ( ! function_exists( 'custom_job_post_button' ) ) {
	function custom_job_post_button() {
        if ('job' == get_post_type()) :
        $link = carbon_get_post_meta( get_the_ID(), 'job-link' );
        if ($link) :?>
        <div class="wp-block-buttons is-content-justification-center last-appply-btn">
            <div class="wp-block-button"><a href="<?php echo esc_url(do_shortcode($link)) ?>" class="wp-block-button__link">Apply Now</a></div>
        </div>
        <?php endif;?>
        <?php
        endif;
	}
}
add_action('astra_template_parts_content', 'custom_event_post_gallery', 11);
if ( ! function_exists( 'custom_event_post_gallery' ) ) {
	function custom_event_post_gallery() {
        if ('event' == get_post_type()) :
            $gallery = carbon_get_post_meta( get_the_ID(), 'event-gallery' );
            if (sizeof($gallery)) : ?>
                <div class="mos-event-post-wrapper">
                    <div class="event-list grid grid-3">
                        <?php foreach($gallery as $attachment_id) : ?>
                            <div class="event-list-item">
                                <a class="fancybox" data-fancybox="gallery" href="<?php echo wp_get_attachment_url( $attachment_id ); ?>">
                                    <?php 
                                        if (preg_match('/video/i', get_post_mime_type($attachment_id))) :
                                            $img_url = get_stylesheet_directory_uri().'/images/video-bg.jpg';
                                        else :
                                            $img_url = aq_resize(wp_get_attachment_url( $attachment_id ), 370,370, true);
                                        endif;
                                    ?>
                                    <img src="<?php echo $img_url; ?>" alt="" class="img-fluid img-event-gallery" width="370" height="370">
                                    <span class="hover-effect smooth"><i class="fa fa-search-plus"></i></span>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif;
        endif;
	}
}
add_action('astra_entry_content_before', 'custom_case_study_post_meta', 11);
if ( ! function_exists( 'custom_case_study_post_meta' ) ) {
	function custom_case_study_post_meta() {
        if ('case-study' == get_post_type()) :
            $working_areas = carbon_get_post_meta( get_the_ID(), 'case-study-working-areas' );
            $short_description = carbon_get_post_meta( get_the_ID(), 'case-study-short-description' );
            $client_name = carbon_get_post_meta( get_the_ID(), 'case-study-client-name' );
            $client_image = carbon_get_post_meta( get_the_ID(), 'case-study-client-image' );
            $client_position = carbon_get_post_meta( get_the_ID(), 'case-study-client-position' );
            ?>
            <div class="case-study-metas">
                <?php if(sizeof($working_areas)) : ?>
                    <ul class="working-areas list-inline">
                        <?php foreach($working_areas as $working_areas) : ?>
                            <li><?php echo $working_areas ?></li>
                        <?php endforeach?>
                    </ul>
                <?php endif?>
                <div class="d-flex">
                    <div class="short-description"><?php echo $short_description ?></div>
                    <div class="customer-box text-center">
                        <div class="customer-avatar-block">
                            <img src="<?php echo wp_get_attachment_url($client_image) ?>" alt="<?php echo $client_name ?>">
                        </div>
                        <div class="customer-block">
                            <div class="customer-name"><?php echo $client_name ?></div>
                            <div class="customer-info"><?php echo $client_position?></div>
                        </div>
                    </div>
                </div>
            </div>        
            <?php
            endif;
	}
}
add_action('astra_content_top', 'custom_page_title');
function custom_page_title () {
    if (!is_home() && !is_page() && !is_single()) : 
    ?>
        <header class="entry-header ast-no-thumbnail ast-no-meta">
            <h1 class="entry-title" itemprop="headline">
                <?php if (is_author()) : ?>
                Author Archive: <?php echo get_the_author()?>
                <?php elseif (is_category()) : ?>
                Category Archive: <?php single_cat_title(); ?>
                <?php elseif (is_tag()) : ?>
                Tag Archive: <?php single_tag_title(); ?>
                <?php elseif (is_search()) : ?>
                Search Result for <?php echo get_search_query(); ?>
                <?php elseif (is_404()) : ?>
                404 Page
                <?php else : ?>
                Archive Page
                <?php endif;?>
            </h1>
        </header>        
    <?php 
    endif;
}
if ( ! function_exists( 'mos_post_classes' ) ) {
	function mos_post_classes( $classes ) {

		if ( is_archive() || is_home() || is_search() ) {
			$classes[] = 'mos-post-block';
		}

		return $classes;
	}
}
add_filter( 'post_class', 'mos_post_classes' );
