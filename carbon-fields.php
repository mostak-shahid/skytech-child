<?php
use Carbon_Fields\Block;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'carbon_fields_register_fields', 'crb_attach_theme_options' );
function crb_attach_theme_options() {
    /*Container::make( 'theme_options', __( 'Theme Options', 'crb' ) )
        ->add_fields( array(
            Field::make( 'text', 'crb_text', 'Text Field' ),
        ));*/
    Container::make( 'post_meta', 'Event Data' )
        ->where( 'post_type', '=', 'event' )
        ->add_fields( array(
            Field::make( 'media_gallery', 'event-gallery', __( 'Media Gallery' ) )
                ->set_type( array( 'image', 'video' ) ),
            Field::make( 'date', 'event-date', __( 'Event Date' ) )
                ->set_attribute( 'placeholder', __( 'Event Date' ) )
                ->set_storage_format( 'Y-m-d' ),
            Field::make( 'text', 'event-location', __( 'Event Location' ) ),
        ));
    Container::make( 'post_meta', 'Job Data' )
        ->where( 'post_type', '=', 'job' )
        ->add_fields( array(
            Field::make( 'select', 'job-job-type', __( 'Job Type' ) )
                ->set_options( array(
                    'FullTime' => 'Full-Time',
                    'PartTime' => 'Part-Time',
                    'Contract' => 'Contractual',
                    'Intern' => 'Intern',
                )),
            Field::make( 'text', 'job-nov', __( 'No. of Vacancies' ) ),
            Field::make( 'date', 'job-deadline', __( 'Deadline' ) )
                ->set_attribute( 'placeholder', __( 'Deadline' ) )
                ->set_storage_format( 'Y-m-d' ),
            Field::make( 'text', 'job-link', __( 'Apply Link' ) ),
        ));
    Container::make( 'post_meta', 'Case Study Data' )
        ->where( 'post_type', '=', 'case-study' )
        ->add_fields( array(
            Field::make( 'text', 'case-study-company-name', __( 'Company Name' ) ),
            Field::make( 'image', 'case-study-company-logo', __( 'Logo' ) ),
            Field::make( 'rich_text', 'case-study-company-about', __( 'About Company' ) ),
            Field::make( 'text', 'case-study-industry', __( 'Industry' ) ),
            Field::make( 'complex', 'case-study-company-data', __( 'Company data' ) )
                ->add_fields( array(
                    Field::make( 'text', 'case-study-company-data-title', __( 'Title' ) ),
                    Field::make( 'text', 'case-study-company-data-value', __( 'Value' ) ),
                )),            
            Field::make( 'text', 'case-study-client-name', __( 'Client Name' ) ),
            Field::make( 'image', 'case-study-client-image', __( 'Client Image' ) ),
            Field::make( 'text', 'case-study-client-position', __( 'Client Position' ) ),            
            Field::make( 'multiselect', 'case-study-working-areas', __( 'Working Areas' ) )
                ->set_options( array(
                    'Appointment Setting' => 'Appointment Setting',
                    'Software Development' => 'Software Development',
                    'Advertising & Marketing' => 'Advertising & Marketing',
                )),
            Field::make( 'complex', 'case-study-results', __( 'Results' ) )
                ->add_fields( array(
                    Field::make( 'text', 'case-study-results-title', __( 'Title' ) ),
                    Field::make( 'text', 'case-study-results-value', __( 'Value' ) ),
                    Field::make( 'text', 'case-study-results-suffix', __( 'Suffix' ) ),
                )),
            Field::make( 'rich_text', 'case-study-short-description', __( 'Short Description' ) ),
            //Field::make( 'oembed', 'case-study-oembed', __( 'Embeded Video' ) ),
            Field::make( 'complex', 'case-study-team', __( 'Team' ) )
                ->add_fields( array(
                    Field::make( 'text', 'case-study-team-name', __( 'Title' ) ),
                    Field::make( 'text', 'case-study-team-position', __( 'Position' ) ),
                    Field::make( 'image', 'case-study-team-image', __( 'Image' ) ),
                )),
            
        ));
    Block::make( __( 'Mos Image Block' ) )
    ->add_fields( array(
        Field::make( 'text', 'mos-image-heading', __( 'Heading' ) ),
        Field::make( 'image', 'mos-image-media', __( 'Image' ) ),
        Field::make( 'rich_text', 'mos-image-content', __( 'Content' ) ),
        //Field::make( 'color', 'mos-image-hr', __( 'Border Color' ) ),
        Field::make( 'text', 'mos-image-btn-title', __( 'Button' ) ),
        Field::make( 'text', 'mos-image-btn-url', __( 'URL' ) ),
        Field::make( 'select', 'mos-image-alignment', __( 'Content Alignment' ) )
            ->set_options( array(
                'left' => 'Left',
                'right' => 'Right',
                'center' => 'Center',
            ))
    ))
    ->set_icon( 'id-alt' )
    ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
        ?>
        <div class="mos-image-block-wrapper <?php echo $attributes['className'] ?>">
            <div class="mos-image-block text-<?php echo esc_html( $fields['mos-image-alignment'] ) ?>">
                <div class="img-part"><?php echo wp_get_attachment_image( $fields['mos-image-media'], 'full' ); ?></div>
                <div class="text-part">
                    <h4><?php echo esc_html( $fields['mos-image-heading'] ); ?></h4>
<!--                    <hr style="background-color: <?php echo esc_html( $fields['mos-image-hr'] ) ?>;">-->
                <?php if ($fields['mos-image-content']) :?>
                    <div class="desc"><?php echo apply_filters( 'the_content', $fields['mos-image-content'] ); ?></div> 
                <?php endif?>                 
                <?php if ($fields['mos-image-btn-title'] && $fields['mos-image-btn-url']) :?>   
                    <div class="wp-block-buttons"><div class="wp-block-button"><a href="<?php echo esc_url( $fields['mos-image-btn-url'] ); ?>" title="" class="wp-block-button__link"><?php echo esc_html( $fields['mos-image-btn-title'] ); ?></a></div></div>  
                <?php endif?>                 
                </div>
            </div>
        </div>
        <?php
    });
    Block::make( __( 'Mos 3 Column CTA' ) )
    ->add_fields( array(
        Field::make( 'text', 'mos-3ccta-heading', __( 'Heading' ) ),        
        Field::make( 'image', 'mos-3ccta-media', __( 'Image' ) ),
        Field::make( 'text', 'mos-3ccta-link', __( 'Link' ) ),
        Field::make( 'textarea', 'mos-3ccta-content', __( 'Content' ) ),
        Field::make( 'image', 'mos-3ccta-bgimage', __( 'Background Image' ) ),
        Field::make( 'color', 'mos-3ccta-bgcolor', __( 'Background Color' ) ),
    ))
    ->set_icon( 'phone' )
    ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
        ?>
        <div class="mos-3ccta-wrapper <?php echo $attributes['className'] ?>" style="<?php if ($fields['mos-3ccta-bgcolor']) echo 'background-color:'.esc_html($fields['mos-3ccta-bgcolor']).';' ?><?php if ($fields['mos-3ccta-bgimage']) echo 'background-image:url('.wp_get_attachment_url($fields['mos-3ccta-bgimage']).');' ?>">
            <div class="mos-3ccta">
                <div class="call-left">
                    <h3><?php echo esc_html( $fields['mos-3ccta-heading'] ); ?></h3>
                </div>
                <div class="call-center">
                    <a href="<?php echo esc_url( $fields['mos-3ccta-link'] ); ?>" class="" target="_blank"><?php echo wp_get_attachment_image( $fields['mos-3ccta-media'], 'full' ); ?></a>
                </div>
                <div class="call-right">
                    <div class="desc"><?php echo esc_html( $fields['mos-3ccta-content'] ); ?></div>
                </div>
            </div>
        </div>
        <?php
    });
    Block::make( __( 'Mos Icon Block' ) )
    ->add_fields( array(
        Field::make( 'text', 'mos-icon-heading', __( 'Heading' ) ),
        Field::make( 'text', 'mos-icon-class', __( 'Icon Class' ) ),
        Field::make( 'color', 'mos-icon-color', __( 'Color' ) ),
        Field::make( 'textarea', 'mos-icon-content', __( 'Content' ) ),
        Field::make( 'select', 'mos-icon-alignment', __( 'Content Alignment' ) )
            ->set_options( array(
                'left' => 'Left',
                'right' => 'Right',
                'center' => 'Center',
            ))
    ))
    ->set_description( __( 'Use Font Awesome in <b>Icon class</b>, you can find Fontawesome <a href="https://fontawesome.com/v4.7.0/cheatsheet/">Here</a>.' ) )
    ->set_icon( 'editor-customchar' )
    ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
        ?>
        <div class="mos-icon-block-wrapper <?php echo $attributes['className'] ?>">
            <div class="mos-icon-block text-<?php echo esc_html( $fields['mos-icon-alignment'] ) ?>">
                <?php if ($fields['mos-icon-class']) : ?>
                <div class="icon-part"><i class="fa <?php echo esc_html( $fields['mos-icon-class'] ); ?>" style="--color:<?php echo esc_html( $fields['mos-icon-color'] ); ?>"></i></div>
                <?php endif;?>
                <div class="text-part">
                    <?php if ($fields['mos-icon-heading']) : ?>
                    <h4><?php echo esc_html( $fields['mos-icon-heading'] ); ?></h4>
                    <?php endif;?>
                    <?php if ($fields['mos-icon-content']) : ?>
                    <div class="desc"><?php echo  $fields['mos-icon-content']; ?></div>                    
                    <?php endif;?>
                </div>
            </div>
        </div>
        <?php
    });
    Block::make( __( 'Mos Counter Block' ) )
    ->add_fields( array(
        Field::make( 'text', 'mos-counter-prefix', __( 'Prefix' ) ),
        Field::make( 'text', 'mos-counter-number', __( 'Number' ) ),
        Field::make( 'text', 'mos-counter-suffix', __( 'Suffix' ) ),
        Field::make( 'color', 'mos-counter-color', __( 'Heading Color' ) ),
        Field::make( 'color', 'mos-counter-text-color', __( 'Text Color' ) ),
        Field::make( 'textarea', 'mos-counter-content', __( 'Content' ) ),
        Field::make( 'select', 'mos-counter-alignment', __( 'Content Alignment' ) )
            ->set_options( array(
                'left' => 'Left',
                'right' => 'Right',
                'center' => 'Center',
            ))
    ))
    ->set_icon( 'clock' )
    ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
        ?>
        <div class="mos-counter-block-wrapper <?php echo $attributes['className'] ?>">
            <div class="mos-counter-block text-<?php echo esc_html( $fields['mos-counter-alignment'] ) ?>">
                <h2 style="color: <?php echo esc_html( $fields['mos-counter-color'] ); ?>"><span class="prefix"><?php echo esc_html( $fields['mos-counter-prefix'] ); ?></span><span class='counter' data-min='1' data-counterup-time="1500"><?php echo esc_html( $fields['mos-counter-number'] ); ?></span><span class="suffix"><?php echo esc_html( $fields['mos-counter-suffix'] ); ?></span></h2>
                <div class="mb-0" style="color: <?php echo esc_html( $fields['mos-counter-text-color'] ); ?>"><?php echo esc_html( $fields['mos-counter-content'] ); ?></div>
            </div>
        </div>
        <?php
    });
    Block::make( __( 'Mos Pricing Block' ) )
    ->add_fields( array(
        Field::make( 'text', 'mos-pricing-title', __( 'Heading' ) ),
        Field::make( 'text', 'mos-pricing-symbol', __( 'Symbol' ) ),
        Field::make( 'text', 'mos-pricing-amount', __( 'Amount' ) ),
        Field::make( 'text', 'mos-pricing-period', __( 'Period' ) )
            ->set_attribute( 'placeholder', 'Ex: per clean / billed weekly' ),
        Field::make( 'text', 'mos-pricing-subtitle', __( 'Sub Heading' ) ),
        Field::make( 'textarea', 'mos-pricing-desc', __( 'Desacription' ) ),
        Field::make( 'complex', 'mos-pricing-features', __( 'Features' ) )
            ->add_fields( array(
                Field::make( 'text', 'item', __( 'Feature' ) ),
            )),
        Field::make( 'text', 'mos-pricing-btn-title', __( 'Button' ) ),
        Field::make( 'text', 'mos-pricing-btn-url', __( 'URL' ) ),
        Field::make( 'select', 'mos-pricing-alignment', __( 'Content Alignment' ) )
        ->set_options( array(
            'left' => 'Left',
            'right' => 'Right',
            'center' => 'Center',
        ))
    ))
    ->set_icon( 'list-view' )
    ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
        ?>
        <div class="mos-pricing-wrapper <?php echo $attributes['className'] ?>">
            <div class="mos-pricing text-<?php echo esc_html( $fields['mos-pricing-alignment'] ) ?>">            
                <div class="title-part">
                    <h3><?php echo esc_html( $fields['mos-pricing-title'] ); ?></h3>
                </div>
                <div class="pricing-part">
                    <div class="pricing-value"> <span class="pricing-symbol"><?php echo esc_html( $fields['mos-pricing-symbol'] ); ?></span> <span class="pricing-amount"><?php echo esc_html( $fields['mos-pricing-amount'] ); ?></span> <span class="plan-period"><?php echo esc_html( $fields['mos-pricing-period'] ); ?></span>
                    </div>
                </div>
                <?php if ($fields['mos-pricing-subtitle']) : ?>
                    <h5 class="desc-subtitle"><?php echo esc_html( $fields['mos-pricing-subtitle'] ); ?></h5>
                <?php endif?>
                <?php if ($fields['mos-pricing-desc']) : ?>
                    <div class="desc-part"><?php echo esc_html( $fields['mos-pricing-desc'] ); ?></div>
                <?php endif?>
                
                <?php if (sizeof(@$fields['mos-pricing-features'])) : ?>
                <div class="features-part">
                    <ul class="pricing-features">
                        <?php foreach ($fields['mos-pricing-features'] as $value) : ?>
                            <li><?php echo $value['item'] ?></li>
                        <?php endforeach;?>
                    </ul>
                </div>
                <?php endif?>
                
                <?php if($fields['mos-pricing-btn-title'] && $fields['mos-pricing-btn-url']) : ?>
                <div class="wp-block-buttons"><div class="wp-block-button"><a href="<?php echo esc_html( $fields['mos-pricing-btn-url'] ); ?>" title="" class="wp-block-button__link"><?php echo esc_html( $fields['mos-pricing-btn-title'] ); ?></a></div></div>
                <?php endif;?>
            
            </div>
        </div>
        <?php
    });
    Block::make( __( 'Mos Services Block' ) )
    ->add_fields( array(
        Field::make( 'select', 'mos-services-block-view', __( 'View' ) )
        ->set_options( array(
            'carousel' => 'Slider',
            'block' => 'Block',
        )),
        Field::make( 'select', 'mos-services-block-grid', __( 'Large Device Grid' ) )
        ->set_options( array(
            '1' => 'Single Column',
            '2' => 'Two Column',
            '3' => 'Three Column',
            '4' => 'Four Column',
            '5' => 'Five Column',
        )),
        Field::make( 'select', 'mos-services-block-grid-md', __( 'Medium Device Grid' ) )
        ->set_options( array(
            '1' => 'Single Column',
            '2' => 'Two Column',
            '3' => 'Three Column',
            '4' => 'Four Column',
            '5' => 'Five Column',
        )),
        Field::make( 'select', 'mos-services-block-grid-sm', __( 'Small Device Grid' ) )
        ->set_options( array(
            '1' => 'Single Column',
            '2' => 'Two Column',
            '3' => 'Three Column',
            '4' => 'Four Column',
            '5' => 'Five Column',
        )),
        Field::make( 'select', 'mos-services-block-autoplay', __( 'Autoplay' ) )
        ->set_options( array(
            'true' => 'Enable',
            'false' => 'Disable'
        )),
        Field::make( 'text', 'mos-services-block-autoplay-speed', __( 'Autoplay Speed' ) )
            ->set_attribute( 'placeholder', '2000' ),
        Field::make( 'text', 'image_size', __( 'Image Size' ) )
            ->set_attribute( 'placeholder', 'width,height. Ex: 370,370' ),
        Field::make( 'complex', 'mos-services-block-slider', __( 'Services' ) )
            ->add_fields( array(
                Field::make( 'image', 'media', __( 'Image' ) ),
                Field::make( 'text', 'title', __( 'Heading' ) ),
                Field::make( 'textarea', 'desc', __( 'Desacription' ) ),
                Field::make( 'text', 'btn-title', __( 'Button' ) ),
                Field::make( 'text', 'btn-url', __( 'URL' ) ),
            )),
        Field::make( 'select', 'mos-services-block-alignment', __( 'Content Alignment' ) )
        ->set_options( array(
            'left' => 'Left',
            'right' => 'Right',
            'center' => 'Center',
        ))
    ))
    ->set_icon( 'list-view' )
    ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
        ?>
        <div class="mos-services-block-wrapper <?php echo $attributes['className'] ?>">
            <?php //var_dump($fields['mos-services-block-grid-sm']) ?>
            <?php if (sizeof($fields['mos-services-block-slider'])) : ?>
            <?php
            $data_slick = 'slick-slider';
            $slidesToScroll = ($fields['mos-services-block-grid'])?$fields['mos-services-block-grid']:1;
            $slidesToScroll_782 = ($fields['mos-services-block-grid-md'])?$fields['mos-services-block-grid-md']:1;
            $slidesToScroll_600 = ($fields['mos-services-block-grid-sm'])?$fields['mos-services-block-grid-sm']:1;
            if ($fields['mos-services-block-view'] == 'carousel') {
                $autoplay = ($fields['mos-services-block-autoplay'])?$fields['mos-services-block-autoplay']:true;
                $autoplaySpeed = ($fields['mos-services-block-autoplay-speed'])?$fields['mos-services-block-autoplay-speed']:2000;
                $cls = 'slick-slider';
                $data_slick = '{"slidesToShow": '.$slidesToScroll.',"slidesToScroll": '.$slidesToScroll.',"autoplay": '.$autoplay.',"autoplaySpeed": '.$autoplaySpeed.',"dots": true,"arrows":false,"responsive": [{"breakpoint": 782,"settings": {"slidesToShow": '.$slidesToScroll_782.',"slidesToScroll": '.$slidesToScroll_782.'}},{"breakpoint": 600,"settings": {"arrows": true,"dots": false,"slidesToShow": '.$slidesToScroll_600.',"slidesToScroll": '.$slidesToScroll_600.'}}]}';
            } else {
                $cls = 'block-view block-view-'.$slidesToScroll.' block-view-md-'.$slidesToScroll_782.' block-view-sm-'.$slidesToScroll_600;
            }
            ?>
            <div class="mos-services-block text-<?php echo esc_html( $fields['mos-services-block-alignment'] ) ?> <?php echo $cls ?>" data-slick='<?php echo $data_slick ?>'>
                <?php foreach($fields['mos-services-block-slider'] as $slide) : ?>
                <div class="item" id="item-<?php echo $slide['_id']?>">
                    <div class="line-filter-outer">
                        <?php if ($slide['media']) : ?>
                            <?php        
                            $width = 370;
                            $height = 370;
                            if ($fields['image_size']){
                                $parts = explode(',',$fields['image_size']);
                                $width = ($parts[0])?intval($parts[0]):370;
                                $height = ($parts[1])?intval($parts[1]):370;
                            }
                            ?> 
                        <div class="line-filter-media"> <a href="<?php echo ($slide['btn-url'])?$slide['btn-url']:'#' ?>"> <img width="<?php echo $width ?>" height="<?php echo $width ?>" src="<?php echo aq_resize(wp_get_attachment_url($slide['media']),$width,$height,true) ?>" class="attachment-industroz-projects-carousel size-industroz-projects-carousel wp-post-image" alt="" loading="lazy"> </a>
                            <div class="hover-effect-1">
                                <div class="hover-effect-content text-white">
                                    <?php echo do_shortcode($slide['desc']) ?>
                                </div>
                            </div>
                        </div>
                        <?php endif?>
                        <div class="line-filter bg-theme p-30">
                            <div class="filter-content">
                                <h3 class="mb-0 text-capitalize mos-services-block-title"> <a class="text-white" href="<?php echo ($slide['btn-url'])?$slide['btn-url']:'#' ?>"><?php echo do_shortcode($slide['title']) ?></a></h3>
                                <?php if ($slide['btn-title']) : ?> 
                                <a href="<?php echo ($slide['btn-url'])?$slide['btn-url']:'#' ?>" class="site-button-link mt-10 text-white"><?php echo $slide['btn-title'] ?></a>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach;?>
            </div>
            <?php endif;?>
        </div>
        <?php
    });
    Block::make( __( 'Mos FAQ Block' ) )
    ->add_fields( array(
        Field::make( 'text', 'mos-faq-question', __( 'Question' ) ),
        Field::make( 'textarea', 'mos-faq-answer', __( 'Answer' ) ),
        Field::make( 'color', 'mos-faq-qcolor', __( 'Question Color' ) ),
        Field::make( 'color', 'mos-faq-acolor', __( 'Answer Color' ) ),
        Field::make( 'select', 'mos-faq-alignment', __( 'Content Alignment' ) )
            ->set_options( array(
                'left' => 'Left',
                'right' => 'Right',
                'center' => 'Center',
            ))
    ))
    ->set_icon( 'warning' )
    ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
        ?>
        <div class="mos-faq-block-wrapper <?php echo $attributes['className'] ?>">
            <div class="mos-faq-block text-<?php echo esc_html( $fields['mos-faq-alignment'] ) ?>">
                <dl class="faq-wrapper">
					<dt class="faq-question" style="color: <?php echo esc_html( $fields['mos-faq-qcolor'] ); ?>"><?php echo esc_html( $fields['mos-faq-question'] ); ?></dt>
					<dd class="faq-answer" style="color: <?php echo esc_html( $fields['mos-faq-acolor'] ); ?>"><?php echo esc_html( $fields['mos-faq-answer'] ); ?></dd>
				</dl>
            </div>
        </div>
        <?php
    });
    Block::make( __( 'Mos Case Studies Block' ) )
    ->add_fields( array(
        Field::make( 'text', 'mos-post-block-nop', __( 'No of Posts' ) ),
        Field::make( 'multiselect', 'mos-post-block-posts', __( 'Select Posts' ) )
            ->add_options( mos_get_posts('case-study')),
        Field::make( 'multiselect', 'mos-post-block-categories', __( 'Select Categories' ) )
            ->add_options( mos_get_terms ('case_study_category', 'small')),
        Field::make( 'select', 'mos-post-block-layout', __( 'Layout' ) )
            ->set_options( array(
                'grid' => 'Grid',
                'list' => 'List',
            )),
        Field::make( 'select', 'mos-post-block-grid', __( 'Grid' ) )
            ->set_options( array(
                'default' => 'Default',
                'six' => 'Two Grids',
                'four' => 'Three Grids',
                'three' => 'Four Grids',
            )),
        Field::make( 'select', 'mos-post-block-gap', __( 'Gap' ) )
            ->set_options( array(
                'gap-default' => 'Default',
                'gap-sm' => 'Small Gap',
                'gap-md' => 'Medium Gap',
                'gap-lg' => 'Large Gap',
            )),
    ))
    ->set_icon( 'admin-post' )
    ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
        $layout = (@$fields['mos-post-block-layout'])?$fields['mos-post-block-layout']:'grid';
        $grid = (@$fields['mos-post-block-grid'])?$fields['mos-post-block-grid']:'default';
        $gap = (@$fields['mos-post-block-gap'])?$fields['mos-post-block-gap']:'gap-default';
        $options = array(
            'post_type' => 'case-study'
        );  
        /*
    'tax_query' => array(
        array(
            'taxonomy' => 'people',
            'field'    => 'slug',
            'terms'    => 'bob',
        ),
    ),        
        */
        if (@$fields['mos-post-block-categories'] && sizeof($fields['mos-post-block-categories'])) {
            //$options['category__in'] = $fields['mos-post-block-categories'];
            $options['tax_query'][] = array(
                'taxonomy' => 'case_study_category',
                'field'    => 'id',
                'terms'    => $fields['mos-post-block-categories'],
            );
            $options['posts_per_page'] = ($fields['mos-post-block-nop'])?$fields['mos-post-block-nop']:-1;
        } elseif (@$fields['mos-post-block-posts'] && sizeof($fields['mos-post-block-posts'])) {
            $options['post__in'] = $fields['mos-post-block-posts'];                    
        }
        $query = new WP_Query( $options );
        if ( $query->have_posts() ) : ?>
            <div class="mos-post-block-wrapper <?php echo $attributes['className'] ?>">
                <div class="mos-post-block mos-post-grid mos-post-layout-<?php echo $layout ?> <?php echo $gap?>">
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?> 
                        <?php 
                            if ($layout == 'grid') {
                                $unit_class="mos-post-grid-".$fields['mos-post-block-grid'];
                            } else {
                                if ($grid == 'default')
                                    $unit_class="mos-post-grid-twelve";
                                else
                                    $unit_class="mos-post-grid-".$fields['mos-post-block-grid']; 
                            }
                        ?>
                        <?php echo get_the_title()?>     
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif;
        wp_reset_postdata();
    });
}
add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
    require_once( 'vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}