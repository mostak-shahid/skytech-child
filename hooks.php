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
            <div class="author-description" itemprop="name"><?php echo do_shortcode(get_the_author_meta('description')) ?></div>
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
            //$working_areas = carbon_get_post_meta( get_the_ID(), 'case-study-working-areas' );
            $working_areas = get_the_terms( get_the_ID(), 'case_study_working_area' );
            $short_description = carbon_get_post_meta( get_the_ID(), 'case-study-short-description' );
            $client_name = carbon_get_post_meta( get_the_ID(), 'case-study-client-name' );
            $client_image = carbon_get_post_meta( get_the_ID(), 'case-study-client-image' );
            $client_position = carbon_get_post_meta( get_the_ID(), 'case-study-client-position' );
        
            $results = carbon_get_post_meta( get_the_ID(), 'case-study-results' );
            $company_name = carbon_get_post_meta( get_the_ID(), 'case-study-company-name' );
            $company_about = carbon_get_post_meta( get_the_ID(), 'case-study-company-about' );
            $oembed = carbon_get_post_meta( get_the_ID(), 'case-study-oembed' );
        
            $industry = carbon_get_post_meta( get_the_ID(), 'case-study-industry' );
            $company_data = carbon_get_post_meta( get_the_ID(), 'case-study-company-data' );
        
            $team = carbon_get_post_meta( get_the_ID(), 'case-study-team' );
        
            ?>
            <div class="case-study-metas">
                <?php if(sizeof($working_areas)) : ?>
                    <ul class="working-areas list-inline">
                        <?php foreach($working_areas as $working_areas) : ?>
                            <li><?php echo $working_areas->name; ?></li>
                        <?php endforeach?>
                    </ul>
                <?php endif?>
                <div class="d-flex">
                    <?php if ($short_description) : ?>
                        
                        <div class="short-description">
                            <h2>What we did</h2>
                            <?php echo do_shortcode($short_description)?>
                        </div>
                    <?php endif?>
                    <div class="customer-box text-center">
                        <?php if ($client_image) : ?>
                        <div class="customer-avatar-block">
                            <img src="<?php echo wp_get_attachment_url($client_image) ?>" alt="<?php echo $client_name ?>">
                        </div>
                        <?php endif?>
                        <div class="customer-block">
                            <div class="customer-name"><?php echo $client_name ?></div>
                            <div class="customer-info"><?php echo $client_position?></div>
                        </div>
                    </div>
                </div>
                <?php if (sizeof($results)) : ?>
                <div class="header-results">
                    <?php foreach($results as $key => $value) : ?>
                    <div class="result result-<?php echo $key ?>">
                        <div class="chart-icon"></div>
                        <div class="result-text">
                            <div class="result-value"><span class="counter"><?php echo $value['case-study-results-value'] ?></span><span class="suffix"><?php echo $value['case-study-results-suffix'] ?></span></div>
                            <div class="result-title"><?php echo $value['case-study-results-title'] ?></div>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
                <?php endif?>
                <?php if ($company_about) : ?>
                    <h2>About <?php echo ($company_name)?$company_name:'Comapny' ?></h2>
                    <div class="company_about"><?php echo $company_about?></div>
                <?php endif?> 
                <?php if ($oembed) : ?>
                    <div class="embed-responsive embed-responsive-21by9 mb-20">
                        <iframe class="embed-responsive-item" src="<?php echo $oembed ?>"></iframe>
                    </div>
                <?php endif?>  
                <div class="post-fields">
                    <?php if ($industry) : ?>
                    <div class="field field-industry">
                        <div class="field-title">Industry</div>
                        <div class="field-value"><?php echo $industry ?></div>
                    </div>
                    <?php endif?>
                    <?php if (sizeof($company_data)) : ?>
                        <?php foreach($company_data as $key => $value) : ?>
                            <div class="field field-<?php echo $key ?>">
                                <div class="field-title"><?php echo $value['case-study-company-data-title'] ?></div>
                                <div class="field-value"><?php echo $value['case-study-company-data-value'] ?></div>
                            </div>
                        <?php endforeach?>
                    <?php endif?>
                </div>
                <?php if (sizeof($team)) : ?> 
                <div class="post-team">
                    <h3 class="team-title">Responsible Team:</h3>
                    <div class="team-set">
                        <?php foreach($team as $key=>$value) : ?>
                        <div class="person">
                            <?php if (@$value['case-study-team-image']) :?>
                            <div class="person-avatar">
                                <img src="<?php echo wp_get_attachment_url($value['case-study-team-image']) ?>" alt="<?php echo $value['case-study-team-name'] ?>">
                            </div>
                            <?php endif?>
                            <div class="person-name"><?php echo $value['case-study-team-name'] ?></div>
                            <div class="person-position"><?php echo $value['case-study-team-position'] ?></div>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
                <?php endif?>             
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

add_action( 'wp_head', 'add_mos_additional_coding', 999 );
function add_mos_additional_coding() {
    echo carbon_get_theme_option( 'mos_additional_coding' );
    ?>
    <link rel="apple-touch-icon" href="<?php echo get_site_icon_url(512) ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_site_icon_url(180) ?>">
    <link rel="apple-touch-icon" sizes="150x150" href="<?php echo get_site_icon_url(150) ?>">
    
    <link rel="icon" href="<?php echo get_site_icon_url(512) ?>">
    <link rel="icon" sizes="32x32" href="<?php echo get_site_icon_url(32) ?>">
    <link rel="icon" sizes="16x16" href="h<?php echo get_site_icon_url(16) ?>">  
              
    <?php 
}

if ( ! function_exists( 'astra_post_author' ) ) {
	function astra_post_author( $output_filter = '' ) {

		ob_start();

		echo '<span ';
			echo astra_attr(
				'post-meta-author',
				array(
					'class' => 'posted-by vcard author',
				)
			);
		echo '>';
			// Translators: Author Name. ?>
			<a title="<?php printf( esc_attr__( 'View all posts by %1$s', 'astra' ), get_the_author() ); ?>" 
				href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"
				<?php
					echo astra_attr(
						'author-url',
						array(
							'class' => 'url fn n',
						)
					);
				?>
				>
				<sapn class="author-name"><?php echo get_avatar( get_the_author_meta( 'ID' ), 32, '', '', ['class'=>'rounded-circle'] ); ?></sapn>
				<span 
				<?php
					echo astra_attr(
						'author-name',
						array(
							'class' => 'author-name',
						)
					);
				?>
				><?php echo get_the_author(); ?></span>
			</a>
		</span>

		<?php

		$output = ob_get_clean();

		return apply_filters( 'astra_post_author', $output, $output_filter );
	}
}

add_action('astra_primary_content_top', 'mos_single_blog_post_header'); //astra_content_top
if ( ! function_exists( 'mos_single_blog_post_header' ) ) {
	function mos_single_blog_post_header() {
        if (is_single() && get_post_type() == 'post') {
            ?>
            <div class="BlogPost__header">
                <?php if (has_post_thumbnail()) :?>
                <div class="BlogPost__thumbnail">
                    <!--<img src="https://cdn.liveagent.com/app/uploads/2021/02/The-25-best-WordPress-live-chat-plugins.svg" class="attachment-blog_post_thumbnail size-blog_post_thumbnail wp-post-image" alt="The 25 best WordPress live chat plugins" loading="lazy" height="400" width="1340">-->
                    <?php the_post_thumbnail() ?>
                </div>
                <?php endif?>
                <div class="BlogPost__intro">
                    <div class="BlogPost__category">
                        <span class="d-none">Categories:</span>
                        <span>
                            <?php
                            $categories = get_the_category();
                            if ( ! empty( $categories ) ) {
                                $n = 0;
                                foreach($categories as $category) {
                                    if ($n) echo ', ';
                                    echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
                                    //echo esc_html( $category->name );
                                    $n++;                                                    
                                }
                            }
                            ?>
                        </span>
                    </div>
                    <h1 class="BlogPost__title"><?php echo get_the_title(); ?></h1>

                    <div class="BlogPost__author">
                        <?php 
                        $author_id = get_post_field ('post_author', $cause_id); 
                        $display_name = get_the_author_meta( 'display_name' , $author_id );
                        ?>
                        <div class="BlogPost__author__avatar">
                            <img alt="<?php echo $display_name;?>" src="<?php echo get_avatar_url($author_id,['size'=>40])?>" class="avatar avatar-40 photo" height="40" width="40" loading="lazy">
                        </div>

                        <p class="BlogPost__author__name"><?php echo $display_name;?></p>
                        <p class="BlogPost__author__position">Last modified on <?php echo get_the_modified_date('M n, Y') ?></p>
                    </div>
					<div class="share-buttons">
						<?php echo do_shortcode('[addtoany]')?>
					</div>
                </div>
            </div>
            <?php
        }
    }
}

/*if ( ! function_exists( 'astra_post_author' ) ) {
	function astra_post_author( $output_filter = '' ) {
   
		ob_start();
        
		echo '<span ';
			echo astra_attr(
				'post-meta-author',
				array(
					'class' => 'posted-by vcard author',
				)
			);
		echo '>';
			// Translators: Author Name. ?>
			<a title="<?php printf( esc_attr__( 'View all posts by %1$s', 'astra' ), get_the_author() ); ?>" 
				href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"
				<?php
					echo astra_attr(
						'author-url',
						array(
							'class' => 'url fn n',
						)
					);
				?>
				>
				<span
				<?php
					echo astra_attr(
						'author-name',
						array(
							'class' => 'author-name',
						)
					);
				?>
				><?php echo get_the_author(); ?></span>
			</a>

		</span>
        <?php echo '<span class="my_class">';
        echo get_avatar( $user_ID, 30 );
        echo '</span>'; ?>
		<?php

		$output = ob_get_clean();

		return apply_filters( 'astra_post_author', $output, $output_filter );
	}
}*/
/*
$purifyCssEnabled = true;
function dequeue_all_styles() {
    global $wp_styles;
    foreach( $wp_styles->queue as $style ) {
        wp_dequeue_style($wp_styles->registered[$style]->handle);
    }
}
 Remove inline <style> blocks. 
function start_html_buffer() {
    // buffer output html
    ob_start();
}
function end_html_buffer() {
    // get buffered HTML
    $wpHTML = ob_get_clean();

    // remove <style> blocks using regular expression
    $wpHTML = preg_replace("/<style[^>]*>[^<]*<\/style>/m",'', $wpHTML);

    echo $wpHTML;
}
function enqueue_pure_styles() {
    wp_enqueue_style('pure-styles', home_url().'/styles.pure.css');
}

if ($purifyCssEnabled) {
    // this will remove all enqueued styles in head
    add_action('wp_print_styles', 'dequeue_all_styles', PHP_INT_MAX - 1);

    // if there are any plugins that print styles in body (like Elementor),
    // you'll need to remove them as well
    add_action('elementor/frontend/after_enqueue_styles', 'dequeue_all_styles',PHP_INT_MAX);
    
    add_action('template_redirect', 'start_html_buffer', 0); // wp hook just before the template is loaded
    add_action('wp_footer', 'end_html_buffer', PHP_INT_MAX); // wp hook after wp_footer()
    add_action('wp_print_styles', 'enqueue_pure_styles', PHP_INT_MAX);
}
*/
