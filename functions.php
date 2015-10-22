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

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size(160, 160, true ); // 160x160 pixels, crop mode

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => 'Primary Menu',
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

    wp_enqueue_script('j3Scripts', 
        $templateDir."/baseScripts.js",
        array(), "2.1", true);

    $styleDir = get_stylesheet_directory_uri();
    wp_register_style( 'j3BaseStyle', $styleDir . '/style.css', 
        array(), "2.14" );

    wp_enqueue_style('j3BaseStyle');
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

    // Register our setting so that $_POST handling is done for us and
    // our callback function just has to echo the <input>
    register_setting( 'general', 'j3SetPhotoPage' );
    register_setting( 'general', 'j3GalleryShowOnly' , "intval");
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

// large image size is 640x640 which is what the css limits the image to
function j3PostThumbnail( $size='large', $forceLink=false) {
    if ( has_post_thumbnail()) {
        if ( is_singular() && !$forceLink) {
            the_post_thumbnail( $size );
        } else {
            echo '<a href="';
            esc_url( the_permalink() );
            echo '" class="photoLink">';
            the_post_thumbnail( $size );
            echo '</a>';
        } // End is_singular()
    } // end has_post_thumbnail
}

function j3HouseTempImg () {
    echo '
                <div class="rightContent">
                    <div class="visualPage displayPhoto">
                        <a href="/house/" class="photoLink">
                            <img src="/house/oneWeekTemps-basic.png" alt="latest temperatures"/>
                        </a>
                        <p>
                        <a href="/house/">
                            Live temperature plots
                        </a>
                        </p>
                    </div> 
                </div> <!-- rightContent -->';
}


function j3TopicTitle() {
    if (is_category() ) {
        echo '<h1 class="topicTitle">';
        single_cat_title(); 
        echo '</h1>';
    }
    // not supporting tags, dates, etc right now
}

/* Fancy photo code */
function j3FancyPhoto () {
    $args = array(
        'post_type' => 'post',
        'tax_query' => array(
            array(
                'taxonomy' => 'post_format',
                'field' => 'slug',
                'terms' => array( 'post-format-image' )
            )
        ),
        'orderby' => 'rand',
        'posts_per_page' => 1
    );
    if (is_category() ) {
        $args["cat"] = get_query_var('cat');

        // See if this is house, and we should display a special heading
        $fullCat = get_category ($args["cat"]);
        if ($fullCat->slug == "house") {
            j3HouseTempImg();
            return;
        }
    }

    $query = new WP_Query( $args );
    if ($query->have_posts()) {
        $query->the_post();
        echo '<div class="rightContent">
            <div class="displayPhoto dualShadow">';
        j3PostThumbnail('large', true);
        echo '</div> 
            </div> <!-- rightContent -->';
    }
}

function j3FancyHeader() {
    if (is_single()) return;
    echo '<div class="hgroup">';
    j3FancyPhoto();
    j3TopicTitle();
    echo '</div> <!-- hgroup -->';
    return;
}

function j3MenuFilter($items, $args=array())
{
    $counter = array();
    foreach ($items as $menuItem) {
        if (!isset($counter[$menuItem->menu_item_parent])){
            $counter[$menuItem->menu_item_parent] = 0;
        }
        $counter[$menuItem->menu_item_parent] += 1;
    }
    foreach ($items as $menuItem) {
        $menuItem->classes[] = 
            'item-count-' . $counter[$menuItem->menu_item_parent];
    }
    return $items;
}
add_filter('wp_nav_menu_objects', 'j3MenuFilter');

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
        next_posts_link("Previous");
        echo '</div>';
    } else if ($defaultPrev != "") { 
        echo '<div class="navPrevious"><a href="' . $defaultPrev 
            .'">Previous</a></div>';
    }

    if ( get_previous_posts_link() ) {
        echo '<div class="navNext">';
        previous_posts_link("Next" );
        echo '</div>';
    } else if ($defaultNext != "") { 
        echo '<div class="navNext"><a href="' . $defaultNext 
            .'">Next</a></div>';
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
function j3IsNewYear()
{
    global $j3LastYear;
    $thisYear = get_the_date('Y');
    if ($j3LastYear === $thisYear) {
        return false;
    } else {
        $j3LastYear = $thisYear;
        return true;
    }
}

$j3LastMonth = "";
function j3IsNewMonth()
{
    global $j3LastMonth;
    $thisMonth = get_the_date('Y-m');
    if ($j3LastMonth === $thisMonth) {
        return false;
    } else {
        $j3LastMonth = $thisMonth;
        return true;
    }
}

$j3ArchiveHasYear = false;
function j3ArchiveDoYear()
{
    global $j3ArchiveHasYear;
    global $j3ArchiveHasMonth ;
    if (j3IsNewYear()) {
        if ($j3ArchiveHasYear) {
            echo '</div></div>
                </div><!-- hgroup-->
                <div class="hgroup hasPage">';
            $j3ArchiveHasMonth  = false;
        }
        echo '<h1 class="topicTitle">' . get_the_date('Y') . "</h1>";
        echo '<div class="rightContent visualPage history hasStack">';
    }
    $j3ArchiveHasYear = true;
}

$j3ArchiveHasMonth = false;
function j3ArchiveDoMonth()
{
    global $j3ArchiveHasMonth;
    if (j3IsNewMonth()) {
        if ($j3ArchiveHasMonth) {
            echo "</div>";
        }
        echo "<h1>" . get_the_date('F') . "</h1>";
        echo '<div class="month">';
    }
    $j3ArchiveHasMonth = true;
}

function j3FeedTime($theTime, $format='U', $gmt=false)
{
    /* RSS feed reader expect posts to not be backdated. Given them the time 
     * the post was written rather than it's official date. */
    if ( is_feed() ) {
        $post = get_post();
        if ($gmt) {
            $time = $post->post_modified_gmt;
        } else {
            $time = $post->post_modified;
        }

        return mysql2date($format, $time, false);
    } else {
        return $theTime;
    }
}
add_filter('get_post_time', 'j3FeedTime', 10, 3);

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
    
    $tagTerm = get_term_by('name', $args['tag'], 'post_tag');
    if (! $tagTerm ) {
        return "Bad tag specified";
    }

    $displayNum = 3;

    $taxOnlyStd = array( array(
            'taxonomy' => 'post_format',
            'field' => 'slug',
            'terms' => array( 'post-format-gallery', 'post-format-image' ),
            'operator' => 'NOT IN',
        ) );

    $query = new WP_Query(array(
        'tag' => $args['tag'],
        'posts_per_page' => $displayNum ,
        'tax_query' => $taxOnlyStd ));

    $result = "";
    // The Loop
    if ( $query->have_posts() ) {
        $result .= '<div class="trippleStack">';
        while ( $query->have_posts() ) {
                $query->the_post();
                ob_start();
                get_template_part( 'card', get_post_format() ); 
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

/* TODO : css formatting not quite right after removing date. Also check 
 * excerpt length. 
 * Also, document arguments */
function j3PostPreviewShortCode($atts) {
    $args = shortcode_atts( array(
        'post' => "",
        'page' => "",
        'posts' => "",
        'pages' => "",
        'start' => 1,
        'end' => 1,
    ), $atts );

    if ($args['post'] == "" and $args['page'] == ""
        and $args['posts'] == "" and $args['pages'] == "") {
        return "j3preview: must specify either 'post' or 'page' slug";
    }

    if ( $args['pages'] != "" ) {
        $pageNames = explode(",", $args['pages']);
    } else {
        $pageNames = array();
    }
    if ( $args['page'] != "" ) {
        array_push($pageNames, $args['page']);
    }
    if ( $args['posts'] != "" ) {
        $postNames = explode(",", $args['posts']);
    } else {
        $postNames = array();
    }
    if ( $args['post'] != "" ) {
        array_push($postNames, $args['post']);
    }

    $result = "";
    if ($args['start'] == 1) {
        $result .= '<div class="hasStack trippleStack">';
    }
    /* WP_Query accepts a list of post ids post__in, but it doesn't accept a 
     * list of post slugs. Since slugs are more user friendly, manually 
     * iterating over individual slugs. */
    foreach ($pageNames as $pageSlug) {
        $query = new WP_Query('pagename='.$pageSlug);
        if ( $query->have_posts() ) {
            $query->the_post();
            $result .= j3PagePreview($echo=False);
        } else {
            $result .= "Unrecognized page " . $pageSlug;
        }
    }

    foreach ($postNames as $postSlug) {
        $query = new WP_Query('name='.$postSlug);
        if ( $query->have_posts() ) {
            $query->the_post();
            ob_start();
            get_template_part( 'card', get_post_format() ); 
            $result .= ob_get_clean();
        } else {
            $result .= "Unrecognized post " . $postSlug;
        }
    }

    if ($args['end'] == 1) {
        $result .= "</div>";
    }

    /* Restore original Post Data */
    wp_reset_postdata(); 

    return $result;

}
add_shortcode('j3preview', 'j3PostPreviewShortCode');

function j3HelpBox () 
{
    echo '<h3>Shortcodes</h3>
        <ul>
            <li> <tt>j3recent</tt> -> argument tag="tagName". Displays previews of 
            recent posts with that tag.
            <li> <tt>j3preview</tt> -> post="postSlug", page="pageSlug", posts="postSlugA,postSlugB", pages="pageSlugA,pageSlugB", start=1, end=1<br>
            shows a preview of one or more pages or posts. The previews must be 
            contained in a div. By default the short code will generate the 
            start and end of that div. Either can be turned off for chaining 
            multiple calls to this shortcode.
        </ul>
        <h3>Making a Gallery</h3>
        <ol>
            <li>Make a post
            <li>upload media
            <li>add [gallery] to the post text
            <li>set a featured image
        </ol>';
}

function j3PostHideHtml($post)
{
    $currentlyHidden = get_post_meta($post->ID, "hidepost", true);
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'j3MetaBoxNonce', 'meta_box_nonce' );
?>
<input type="checkbox" name="hidepost"
<?php checked( $currentlyHidden, 'hide' ); ?> />
<label for="hidepost">Only show in archives</label>
<?php
}

function j3AddMetaBoxes ()
{
    $screens = array( 'post', 'page' );
    foreach ( $screens as $screen ) {
        add_meta_box("j3helpdiv", "J3 Theme Help", 'j3HelpBox', $screen, 'normal', 'default');
    }

    add_meta_box("j3hidepostdiv", "Visibility of Post", 'j3PostHideHtml', 'post', 'side');
}
add_action( 'add_meta_boxes', 'j3AddMetaBoxes');

function j3MetaBoxSave($post_id)
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] )
        || !wp_verify_nonce( $_POST['meta_box_nonce'], 'j3MetaBoxNonce' ) ) {
            return;
    }
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;

    $hidden = isset( $_POST['hidepost'] ) && $_POST['hidepost'] ? 'hide' : 'show';
    update_post_meta( $post_id, 'hidepost', $hidden );
}
add_action( 'save_post', 'j3MetaBoxSave');

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

    if ( $query->get("post_format") 
        && $query->get("post_format") == 'post-format-gallery') {
        return true;
    } else {
        return false;
    }
}

function j3NotHiddenQueryArg()
{
    $notHidden = array( 
        'relation' => 'OR',
        array(
            'key' => 'hidepost',
            'value' => 'hide',
            'compare' => '!=',),
        array(
            'key' => 'hidepost',
            'compare' => 'NOT EXISTS',),
    );

    return $notHidden;
}

/* special archives */
/* http://www.billerickson.net/customize-the-wordpress-query/ */
function j3Query( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $photoPostFormat = false;
    $taxOnlyStd = array( array(
            'taxonomy' => 'post_format',
            'field' => 'slug',
            'terms' => array( 'post-format-gallery', 'post-format-image' ),
            'operator' => 'NOT IN',
        ) );
    if ( ! j3IsGalleryFormat($query) && ! is_search() ) {
        $query->set( 'tax_query', $taxOnlyStd );
    } else {
        $photoPostFormat = true;
    }

    if ( is_home() || is_category() || is_feed() ) {
        $query->set( 'orderby', 'ID' );
        $query->set( 'meta_query', j3NotHiddenQueryArg());
    } else if ( is_date() ) {
        // Display all posts on the same page, and do not display gallery or
        // image posts
        $query->set( 'posts_per_page', -1);
    } else if ( $photoPostFormat ) {
        $query->set( 'posts_per_page', 30);
    }
}
add_action( 'pre_get_posts', 'j3Query', 1 );

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

function j3ExcerptMore($more) {
    global $post;
    global $j3InPostPreview;
    if ($j3InPostPreview) {
        return ' [...]';
    } else {
        return ' [...] <a class="moretag" href="'. get_permalink($post->ID) . '"> Read More</a>';
    }
}
add_filter('excerpt_more', 'j3ExcerptMore');

function j3IsFancyPhoto() {
    return is_single() && get_post_format() == "image" ;
}

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

function j3GalleryFilter($content, $attr)
{
    // Give each gallery on the page a unique id
    static $instance = 0;
    $instance++;

    $post = get_post();
    extract(shortcode_atts(array(
        'id'         => $post->ID,
        'itemtag'    => 'dl',
        'icontag'    => 'dt',
        'captiontag' => 'dd',
        'size'       => 'thumbnail',
    ), $attr));

    $showOnly = get_option('j3GalleryShowOnly', -1);
    if (get_post_format() == "gallery" ) {
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
        if ($i == $showOnly) {
            $output .= "<{$itemtag} class='gallery-item collapseShow' ";
            $output .= "onclick='j3Show(this)'>";
            $output .= "
                <{$icontag} class='collapseMore'>
                <p>
                More ...
                </p>
                </{$icontag}>
                </{$itemtag}>";
        }
        $link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);

        $itemClass = 'gallery-item';
        if ($showOnly > 0 && $i >= $showOnly) {
            $itemClass .= " collapseHide";
        }

        $output .= "<{$itemtag} class='{$itemClass}'>";
        $output .= "
            <{$icontag} class='gallery-icon'>
            $link
            </{$icontag}>";
        if ( $captiontag && trim($attachment->post_excerpt) ) {
            $output .= "
                <{$captiontag} class='wp-caption-text gallery-caption'>
                " . wptexturize($attachment->post_excerpt) . "
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
add_filter('post_gallery', 'j3GalleryFilter', 10, 2);

function add_query_vars_filter( $vars ){
    $vars[] = "display_post";
    return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );
