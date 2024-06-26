<?php
/**
 * j3 Custom
 * 
 * @package WordPress
 * @since 1.0
 */

if (!isset($content_width)) {
    $content_width = 940; //pixels
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function j3Setup() {

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

        // support html5
	add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list') );

        // not modifying the title
        add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size(160, 160, true ); // 160x160 pixels, crop mode

        // Enable site logo
        add_theme_support( 'custom-logo',
            array('height' => 200,
                  'flex-width' => true,
                  'header-text' => array( 'site-title', 'site-description' ))
              );

	// This theme uses wp_nav_menu() for the top menu and bottom menu
	register_nav_menus( array(
		'primary' => 'Primary Menu',
                'footer' => 'Footer Menu',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'gallery', 'image',
	) );

}
add_action('after_setup_theme', 'j3Setup');

function j3AddExternals() {
    $templateDir = get_template_directory_uri();

    if (!is_front_page()) {
        wp_enqueue_script('photoswipe',
            $templateDir."/photoswipe/photoswipe.min.js",
            array('jquery'), "4.1", true);
        wp_enqueue_script('photoswipe-ui',
            $templateDir."/photoswipe/photoswipe-ui-default.min.js",
            array('jquery', 'photoswipe'), "4.1", true);
    }
    wp_enqueue_script('j3Scripts', 
        $templateDir."/baseScripts.js",
        array('jquery'), "2.8", true);

    $styleDir = get_stylesheet_directory_uri();
    wp_register_style( 'j3BaseStyle', $styleDir . '/style.css', 
        array(), "4.22" );
    wp_enqueue_style('j3BaseStyle');

    wp_register_style( 'fontAwesome',
        $styleDir . "/font-awesome-4.6.3/css/font-awesome.min.css",
        array(), "4.6.3");
    wp_enqueue_style('fontAwesome');

    if (!is_front_page()) {
        wp_register_style('photoswipe',
            $styleDir . "/photoswipe/photoswipe.css",
            array(), "4.1");
        wp_enqueue_style('photoswipe');
        wp_register_style( 'lightbox-skin',
            $styleDir . '/lightbox-skin.css',
            array(), "0.2" );
        wp_enqueue_style('lightbox-skin');
    }

}
add_action( 'wp_enqueue_scripts', 'j3AddExternals' );

function j3AdminSetup() {
    add_settings_section(
        'j3Settings',
        'j3 Custom Settings',
        'j3SettingsFunc',
        'general');

    // Add the field with the names and function to use for our new
    // settings, put it in our new section
    add_settings_field(
        'j3SetPhotoPage',
        'Last photo page',
        'j3SettingPhotoPage',
        'general',
        'j3Settings'
    );
    add_settings_field(
        'j3GalleryShowOnly',
        'Number of pictures to show in galleries',
        'j3SettingGalleryShow',
        'general',
        'j3Settings'
    );
    add_settings_field(
        'j3SetCommentsPolicy',
        'Comment Policy Page',
        'j3SettingCommentsPolicy',
        'general',
        'j3Settings'
    );

    // Register our setting so that $_POST handling is done for us and
    // our callback function just has to echo the <input>
    register_setting( 'general', 'j3SetPhotoPage' );
    register_setting( 'general', 'j3GalleryShowOnly' , "intval");
    register_setting( 'general', 'j3SetCommentsPolicy');
}
add_action( 'admin_init', 'j3AdminSetup');

function j3SettingsFunc($arg) {
  // echo section intro text here
  echo "<p>Settings specific to the j3Custom theme</p>";
}

function j3SettingPhotoPage() {
    echo '<input name="j3SetPhotoPage" id="j3SetPhotoPage" value="';
    echo get_option( 'j3SetPhotoPage', "");
    echo '" class="regular-text code" type="text">';
    echo '<p>The slug for the page to list after all native photo albums</p>';
}

function j3SettingGalleryShow() {
    echo '<input name="j3GalleryShowOnly" id="j3GalleryShowOnly" value="';
    echo get_option( 'j3GalleryShowOnly', -1);
    echo '" class="regular-text code" type="text">';
    echo '<p>Number of icons to show in the collapsed gallery view.</p>';
}

function j3SettingCommentsPolicy() {
    echo '<input name="j3SetCommentsPolicy" id="j3SetCommentsPolicy" value="';
    echo get_option( 'j3SetCommentsPolicy', "");
    echo '" class="regular-text code" type="text">';
    echo '<p>The slug for the page with the commenting policy</p>';
}

function j3RegisterWidgets() {
    register_sidebar( array(
        'name' => 'CTA Box',
        'id'   => 'cta_box',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'j3RegisterWidgets' );

// large image size is 640x640 which is what the css limits the image to
function j3PostThumbnail( $size='large', $forceLink=false) {
    if ( has_post_thumbnail()) {
        if ( is_singular() && !$forceLink) {
            the_post_thumbnail( $size );
        } else {
            echo '<a href="';
            echo esc_url( get_permalink() );
            echo '" class="photoLink">';
            the_post_thumbnail( $size );
            echo '</a>';
        } // End is_singular()
    } // end has_post_thumbnail
}


function j3PageNav($defaultPrev="", $defaultNext="", $standalone=false) {
    // Don't print empty markup if there's only one page.
    if ( $GLOBALS['wp_query']->max_num_pages < 2
        && $defaultPrev == "" && $defaultNext == "") {
            return;
        }
    if ($standalone) {
        echo '<div class="hgroup">';
    }
    echo '<div class="rightContent">';

    if ( get_next_posts_link() ) {
        echo '<div class="navPrevious">';
        next_posts_link("Older");
        echo '</div>';
    } else if ($defaultPrev != "") { 
        echo '<div class="navPrevious"><a href="' . $defaultPrev 
            .'">Older</a></div>';
    }

    if ( get_previous_posts_link() ) {
        echo '<div class="navNext">';
        previous_posts_link("Newer" );
        echo '</div>';
    } else if ($defaultNext != "") { 
        echo '<div class="navNext"><a href="' . $defaultNext 
            .'">Newer</a></div>';
    } 

    echo '</div>';
    if ( $standalone ) {
        echo '</div><!-- .hgroup -->';
    }
}

/* Helper for j3InPostPreview. Generates a text list of categories. */
function j3NoLinkCategories()
{
    $categories = get_the_category();
    if ( empty( $categories ) ) { 
        return "";
    }
    $i = 0;
    $theList = '<p class="topics">';
    $separator = ", ";
    foreach ( $categories as $category ) {
        if ( 0 < $i ) $theList .= $separator;
        $theList .=  $category->name;
        $i++;
    }
    $theList .= "</p>";
    return $theList;
}

/* Display stack of paper summary of a post */
function j3PagePreview( $echo = True )
{
    global $post;

    $result = '<div class="stackPaper">
          <a href="';
    $result .= esc_url( get_the_permalink() );
    $result .= '" class="noEmph">
        <div class="summary">';

    if ( has_post_thumbnail()) {
        $result .= get_the_post_thumbnail($post->ID, 'thumbnail' );
    }
    
    $result .= '<h1>' . get_the_title() . '</h1>';
    $result .= j3NoLinkCategories();
    if ($post->post_type == 'page') {
        j3SetShortExcerpt();
        $result .= '<p>' . get_the_excerpt() . "</p>";
        // we don't expect nested post previews, so this can just be set to 
        // false rather than having to remember the previous setting.
        j3EndShortExcerpt();
    }
    $result .= '</div> <!-- summary --> 
          </a>
          </div>';
    if ($echo) {
        echo $result;
    } else {
        return $result;
    }
}


/* archive helper function */
$j3LastYear = "";
function j3IsNewYear($thisYear)
{
    global $j3LastYear;
    if ($j3LastYear === $thisYear) {
        return false;
    } else {
        $j3LastYear = $thisYear;
        return true;
    }
}

$j3LastMonth = "";
function j3IsNewMonth($thisMonth)
{
    global $j3LastMonth;
    if ($j3LastMonth === $thisMonth) {
        return false;
    } else {
        $j3LastMonth = $thisMonth;
        return true;
    }
}

$j3ArchiveHasYear = false;
function j3ArchiveDoYear($year=NULL)
{
    global $j3ArchiveHasYear;
    global $j3ArchiveHasMonth ;
    if (empty($year)) {
        $year = get_the_date('Y');
    }
    if (j3IsNewYear($year)) {
        if ($j3ArchiveHasYear) {
            echo '</div></div>
                </div><!-- hgroup-->
                <div class="hgroup hasPage">';
            $j3ArchiveHasMonth  = false;
        }
        echo '<h1 class="topicTitle">' . $year . "</h1>";
        echo '<div class="rightContent visualPage history hasStack">';
    }
    $j3ArchiveHasYear = true;
}

$j3ArchiveHasMonth = false;
function j3ArchiveDoMonth($yearMonth=NULL)
{
    global $j3ArchiveHasMonth;
    if (empty($yearMonth)) {
        $yearMonth = get_the_date('Y-m');
    }
    if (j3IsNewMonth($yearMonth)) {
        if ($j3ArchiveHasMonth) {
            echo "</div>";
        }
        echo "<h1>" . mysql2date('F', $yearMonth . '-01', false) . "</h1>";
        echo '<div class="month">';
    }
    $j3ArchiveHasMonth = true;
}

/* Arguments
 * tag - optional tag name 
 *
 * TODO - need to handle galleries. Either ignore them, or display as photo 
 * gallery
 */
function j3RecentPosts($atts) {
    $args = shortcode_atts( array(
        'tag' => -1,
    ), $atts );
    if ($args['tag'] == -1) {
        return "No tag specified";
    }
    
    $tagTerm = get_term_by('slug', $args['tag'], 'post_tag');
    if (! $tagTerm ) {
        return "Bad tag specified";
    }

    $displayNum = 6;

    $taxOnlyStd = array( array(
            'taxonomy' => 'post_format',
            'field' => 'slug',
            'terms' => array('post-format-image' ),
            'operator' => 'NOT IN',
        ) );

    $query = new WP_Query(array(
        'tag' => $args['tag'],
        'post_type' => array('post', 'photo_album'),
        'posts_per_page' => $displayNum ,
        'tax_query' => $taxOnlyStd ));

    $result = "";
    // The Loop
    if ( $query->have_posts() ) {
        $result .= '<div class="trippleStack">';
        while ( $query->have_posts() ) {
                $query->the_post();
                ob_start();
                if (get_post_type() == 'photo_album') {
                    $format = 'gallery';
                } else {
                    $format = get_post_format();
                }
                get_template_part( 'card', $format ); 
                $result .= ob_get_clean();
        }

        if ($query->found_posts > $displayNum) {
            $result .= '<p class="placeInPaper"><a href="' 
                . esc_url(get_term_link($tagTerm)) 
                . '">More entries ...</a></p>' ;
        }

        $result .= '</div>';
    } 

    /* Restore original Post Data */
    wp_reset_postdata(); 

    return $result;
}
add_shortcode('j3recent', 'j3RecentPosts');

function j3HelpBox () 
{
    echo '<h3>Shortcodes</h3>
        <ul>
            <li> <tt>j3recent</tt> -> argument tag="tagName". Displays previews of 
            recent posts with that tag.
        </ul>
        <h3>Making a Gallery</h3>
        <ol>
            <li>Make a "photo album"
            <li>upload media
            <li>add [gallery] to the post text
            <li>set a featured image
        </ol>';
}

function j3AddMetaBoxes ()
{
    $screens = array( 'post', 'page' );
    foreach ( $screens as $screen ) {
        add_meta_box("j3helpdiv", "J3 Theme Help", 'j3HelpBox', $screen, 'normal', 'default');
    }
}
add_action( 'add_meta_boxes', 'j3AddMetaBoxes');

/* Determine if the current page is an archive page for photo galleries
 */
function j3IsGalleryFormat($query = '')
{
    /* in the simple case, we could just check
        is_tax('post_format', 'post-format-gallery')
       however, if the user searched for both a category and gallery posts, 
       wordpress says
        is_category() 1
        is_tax('post_format', 'post-format-gallery') 
       I want the post_format gallery to override the category. */
    global $wp_query;
    if (! $query) {
        $query = $wp_query;
    }

    if ( $query->get("post_type")
        && $query->get("post_type") == 'photo_album') {
        return true;
    } elseif ( $query->get("post_format")
        && $query->get("post_format") == 'post-format-gallery') {
        return true;
    } else {
        return false;
    }
}

function j3StdPhotosQuery( ) {
    $tag_not_special = array(
        'taxonomy' => 'post_tag',
        'field' => 'slug',
        'terms' => array( 'isaac_album', 'GrandpasAlbums'),
        'operator' => 'NOT IN',
    );
    return $tag_not_special;
}


/* From
 * https://wordpress.stackexchange.com/questions/67003/is-there-a-reason-why-pages-are-not-publicly-queryable
 */
function fix_page_query() {
    if ( post_type_exists( 'page' ) ) {
        global $wp_post_types;
        $wp_post_types['page']->publicly_queryable = true;
    }
}
add_action( 'init', 'fix_page_query', 1 );

/* special archives */
/* http://www.billerickson.net/customize-the-wordpress-query/ */
function j3Query( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( j3IsGalleryFormat($query) ) {
        if (! is_tag() ) {
            $query->set( 'tax_query', array(j3StdPhotosQuery()));
        }
        $query->set( 'posts_per_page', 30);
    } elseif ( is_date()
        || (function_exists("j3_date_is_archive")
        && j3_date_is_archive( )) ) {
        // Display all posts on the same page
        $query->set( 'post_type', array( 'post', 'photo_album' ) );
        $query->set( 'posts_per_page', -1);
        $query->set('order', 'ASC');
        $tax_not_image = array( array(
                'taxonomy' => 'post_format',
                'field' => 'slug',
                'terms' => array( 'post-format-image' ),
                'operator' => 'NOT IN',
            ) );
        $query->set( 'tax_query', array($tax_not_image) );
    } elseif ( is_home() || is_category() || is_feed() || is_tag() ) {
        $taxOnlyStd = array( array(
                'taxonomy' => 'post_format',
                'field' => 'slug',
                'terms' => array( 'post-format-gallery',
                'post-format-image' ),
                'operator' => 'NOT IN',
            ) );
        $query->set( 'tax_query', array($taxOnlyStd) );
    } elseif ( $query->is_search ) {
        if (get_query_var('post_type', 'all') == 'attachment') {
            $query->set( 'post_status', array( 'publish', 'inherit' ) );
        }
    }
}
add_action( 'pre_get_posts', 'j3Query');

$j3InPostPreview = False;
function j3SetShortExcerpt() {
    global $j3InPostPreview;
    $j3InPostPreview = True;
}
function j3EndShortExcerpt() {
    global $j3InPostPreview;
    $j3InPostPreview = False;
}
function j3ArchiveExcerpt( $length ) {
    global $j3InPostPreview;
    if ($j3InPostPreview) {
        return 20;
    } else {
        return $length;
    }
}
add_filter('excerpt_length', 'j3ArchiveExcerpt');

function j3GalleryGetAttachments($attr)
{
    $post = get_post();

    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        if ( !$attr['orderby'] )
            unset( $attr['orderby'] );
    }

    extract(shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'ID',  
        'id'         => $post->ID,
        'include'    => '',
        'exclude'    => ''
    ), $attr));

    $id = intval($id);
    if ( 'RAND' == $order ) {
        $orderby = 'none';
    }

    if ( !empty($include) ) {
        $_attachments = get_posts( array(
            'include' => $include,
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'order' => $order,
            'orderby' => $orderby) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( !empty($exclude) ) {
        $attachments = get_children( array(
            'post_parent' => $id,
            'exclude' => $exclude,
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'order' => $order,
            'orderby' => $orderby) );
    } else {
        $attachments = get_children( array('post_parent' => $id,
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'order' => $order,
            'orderby' => $orderby) );
    }
    return $attachments;
}

function j3GalleryFilter($content, $attr, $instance)
{
    $post = get_post();
    extract(shortcode_atts(array(
        'id'         => $post->ID,
        'itemtag'    => 'dl',
        'icontag'    => 'dt',
        'captiontag' => 'dd',
        'size'       => 'thumbnail',
    ), $attr));

    $showOnly = get_option('j3GalleryShowOnly', -1);
    if (get_post_format() == "gallery" || get_post_type() == 'photo_album') {
        // show entire gallery on gallery pages
        $showOnly = -1; 
    }

    $attachments = j3GalleryGetAttachments($attr);

    if ( empty($attachments) ) {
        return '';
    }

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
        return $output;
    }

    $itemtag = tag_escape($itemtag);
    $captiontag = tag_escape($captiontag);
    $icontag = tag_escape($icontag);
    $valid_tags = wp_kses_allowed_html( 'post' );
    if ( ! isset( $valid_tags[ $itemtag ] ) )
        $itemtag = 'dl';
    if ( ! isset( $valid_tags[ $captiontag ] ) )
        $captiontag = 'dd';
    if ( ! isset( $valid_tags[ $icontag ] ) )
        $icontag = 'dt';

    $selector = "gallery-{$instance}";

    $gallery_style = $gallery_div = '';
    $size_class = sanitize_html_class( $size );
    $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-size-{$size_class}'>";
    $output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {
        $image_meta  = wp_get_attachment_metadata( $id );

        if ($i == $showOnly) {
            $output .= "<p class='collapseMore collapseShow' ";
            $output .= "onclick='j3Show(this)'>
                More ...
                </p>";
        }
        $link = wp_get_attachment_link($id, $size, false, false);

        $itemClass = 'gallery-item';
        if ($showOnly > 0 && $i >= $showOnly) {
            $itemClass .= " collapseHide";
        }

        $output .= "<{$itemtag} class='{$itemClass}' data-width='" . $image_meta['width'] . "'"
            . " data-height='" . $image_meta['height'] . "' >";
        $output .= "
            <{$icontag} class='gallery-icon'>
            $link
            </{$icontag}>";
        if ( $captiontag ) {
            // todo: instead populate the comments link in json using the
            // restful API.
            // Note: this only gets the comments link for short code
            // galleries. It doesn't add it to Blocks galleries.
            $output .= "
                <{$captiontag} class='wp-caption-text gallery-caption'>";
            if (trim($attachment->post_excerpt)) {
                $output .= "
                " . wptexturize($attachment->post_excerpt);
            }
            $output .= "
                <a href='" . get_permalink($id, false) . "'
                 class='attachment-permalink'>-- Comments&nbsp;(" .
                get_comments_number($id) . ")</a>
                </{$captiontag}>";
        }
        $output .= "</{$itemtag}>";
        ++$i;
    }
    if ($showOnly > 0 ) {
        $output .= "<p class='collapseLess collapseHide' ";
        $output .= "onclick='j3Hide(this)'>";
        $output .= "... Collapse </p>";
    }

    $output .= "
        <br style='clear: both;' />
        </div>\n";

    return $output;
}
add_filter('post_gallery', 'j3GalleryFilter', 10, 3);


function add_size_to_images($content) {
    $content = preg_replace_callback('/wp-image-(\\d*)[^\'"]*[\'"]/',
        function($match) {
            $orig = $match[0];
            $attachment_id = $match[1];
            $image_meta  = wp_get_attachment_metadata( $attachment_id );
            return $orig . ' data-width="' . $image_meta['width'] . '"'
                . ' data-height="' . $image_meta['height'] . '"';
        },
        $content);
    return $content;
}
// add at priority 10 before shortcodes have been expanded
add_filter( 'the_content', 'add_size_to_images', 10);


function search_in_menu($items, $args) {
    if ($args->theme_location == 'primary') {
        $items .= '<li class="menu-item">' . get_search_form( false ) . '</li>';
    }
    return $items;
}
add_filter( 'wp_nav_menu_items', 'search_in_menu', 10, 2 );

function vimeo_direct_link($content)
{
    $num_matches = preg_match('/vimeo.com\/video\/([^\'"]*)/',
        $content, $matches);
    if ($num_matches > 0) {
        $url = 'https://vimeo.com/' . $matches[1];
        return $url;
    }
    return NULL;
}

function youtube_direct_link($content)
{
    $num_matches = preg_match('/www.youtube.com\/embed\/([^\'"]*)/',
        $content, $matches);
    if ($num_matches > 0) {
        return 'https://www.youtube.com/watch?v='.$matches[1];
    }
    return NULL;
}

$show_cookie_bar = false;
function j3_video_shortcode( $atts, $content = NULL)
{
    if (empty($content) ) return "";

    $direct_url = vimeo_direct_link($content);
    if (empty($direct_url)) {
        $direct_url = youtube_direct_link($content);
    }
    if (!empty($direct_url)) {
        $alt_string = 'Click for video <a href="' . $direct_url . '">'
            . $direct_url . '</a>';
    } else if (is_feed()) {
        $alt_string = 'Embedded video on website';
    } else {
        $alt_string = "";
    }
    if (is_feed()) return $alt_string;

    global $show_cookie_bar;
    $show_cookie_bar = true;

    $return = '<div class="ratio16_9">'
        . do_shortcode('[cookie]' . $content . '[/cookie]')
        . '</div><br>' . $alt_string;
    return $return;
}
add_shortcode('vimeo', 'j3_video_shortcode');
add_shortcode('video', 'j3_video_shortcode');

function hide_cookies($content)
{
    global $show_cookie_bar;
    if ($show_cookie_bar) return $content;
    else return "";
}
add_filter('eu_cookie_law_frontend_banner', 'hide_cookies');
add_filter('eu_cookie_law_frontend_popup', 'hide_cookies');

# don't cache cookies for comments
remove_action( 'set_comment_cookies', 'wp_set_comment_cookies' );
