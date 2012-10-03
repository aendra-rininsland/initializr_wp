<?php
/**
 * Initializr Adaptive functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'initializr_adaptive_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package Initializr
 * @subpackage Initializr_Adaptive
 * @since Initializr Adaptive 1.0
 */

add_action( 'after_setup_theme', 'initializr_adaptive_setup' );

if ( ! function_exists( 'initializr_adaptive_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override initializr_adaptive_setup() in a child theme, add your own initializr_adaptive_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, custom headers
 *  and backgrounds, and post formats.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Initializr 1.0
 */


function initializr_adaptive_setup(){
    /* Make Initializr Adaptive available for translation.
     * Translations can be added to the /languages/ directory.
     * If you're building a theme based on Initializr Adaptive, use a find and replace
     * to change 'initializr_adaptive' to the name of your theme in all the template files.
     */
    load_theme_textdomain( 'initializr_adaptive', get_template_directory() . '/languages' );

    // This theme styles the visual editor with editor-style.css to match the theme style.
    add_editor_style();
    
    // Add default posts and comments RSS feed links to <head>.
    add_theme_support( 'automatic-feed-links' );
    
    // This theme uses wp_nav_menu() in one location.
    register_nav_menu( 'primary', __( 'Primary Menu', 'initializr_adaptive' ) );

    // Add support for a variety of post formats
    add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );
    
}

endif; // initializr_adaptive_setup

 
/**
 * Registers CSS and Javascript
 */

if (!function_exists('initializr_adaptive_register_stuff')) {
    function initializr_adaptive_register_stuff(){    
        /* Javascript registrations are here. */
        wp_register_script('modernizr', get_template_directory_uri() . '/js/vendor/modernizr-2.6.1-respond-1.1.0.min.js');
        wp_register_script('initializr_adaptive_main_js', get_template_directory_uri() . '/js/main.js', array(), '1.0', true);
        
        /* CSS registrations are here. */
        wp_register_style('normalize', get_template_directory_uri() . '/css/normalize.min.css');
        wp_register_style('initializr_adaptive_main_css', get_template_directory_uri() . '/css/main.css');
        wp_register_style('initializr_adaptive_wp_css', get_template_directory_uri() . '/style.css');
    }
}

add_action('init', 'initializr_adaptive_register_stuff');

/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function initializr_adaptive_excerpt_length( $length ) {
    return 40;
}
add_filter( 'excerpt_length', 'initializr_adaptive_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 */
function initializr_adaptive_continue_reading_link() {
    return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'initializr_adaptive' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and initializr_adaptive_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function initializr_adaptive_auto_excerpt_more( $more ) {
    return ' &hellip;' . initializr_adaptive_continue_reading_link();
}
add_filter( 'excerpt_more', 'initializr_adaptive_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function initializr_adaptive_custom_excerpt_more( $output ) {
    if ( has_excerpt() && ! is_attachment() ) {
        $output .= initializr_adaptive_continue_reading_link();
    }
    return $output;
}
add_filter( 'get_the_excerpt', 'initializr_adaptive_custom_excerpt_more' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function initializr_adaptive_page_menu_args( $args ) {
    $args['show_home'] = true;
    return $args;
}
add_filter( 'wp_page_menu_args', 'initializr_adaptive_page_menu_args' );

/**
 * Register our sidebars and widgetized areas. Also register the default Epherma widget.
 *
 * @since Initializr Adaptive 1.0
 */
function initializr_adaptive_widgets_init() {

    register_sidebar( array(
        'name' => __( 'Main Sidebar', 'initializr_adaptive' ),
        'id' => 'sidebar-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'Showcase Sidebar', 'initializr_adaptive' ),
        'id' => 'sidebar-2',
        'description' => __( 'The sidebar for the optional Showcase Template', 'initializr_adaptive' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'Footer Area One', 'initializr_adaptive' ),
        'id' => 'sidebar-3',
        'description' => __( 'An optional widget area for your site footer', 'initializr_adaptive' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'Footer Area Two', 'initializr_adaptive' ),
        'id' => 'sidebar-4',
        'description' => __( 'An optional widget area for your site footer', 'initializr_adaptive' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'Footer Area Three', 'initializr_adaptive' ),
        'id' => 'sidebar-5',
        'description' => __( 'An optional widget area for your site footer', 'initializr_adaptive' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => "</aside>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
}
add_action( 'widgets_init', 'initializr_adaptive_widgets_init' );

if ( ! function_exists( 'initializr_adaptive_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function initializr_adaptive_content_nav( $nav_id ) {
    global $wp_query;

    if ( $wp_query->max_num_pages > 1 ) : ?>
        <nav id="<?php echo $nav_id; ?>">
            <h3 class="assistive-text"><?php _e( 'Post navigation', 'initializr_adaptive' ); ?></h3>
            <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'initializr_adaptive' ) ); ?></div>
            <div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'initializr_adaptive' ) ); ?></div>
        </nav><!-- #nav-above -->
    <?php endif;
}
endif; // initializr_adaptive_content_nav

/**
 * Return the URL for the first link found in the post content.
 *
 * @since Initializr Adaptive 1.0
 * @return string|bool URL or false when no link is present.
 */
function initializr_adaptive_url_grabber() {
    if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
        return false;

    return esc_url_raw( $matches[1] );
}

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function initializr_adaptive_footer_sidebar_class() {
    $count = 0;

    if ( is_active_sidebar( 'sidebar-3' ) )
        $count++;

    if ( is_active_sidebar( 'sidebar-4' ) )
        $count++;

    if ( is_active_sidebar( 'sidebar-5' ) )
        $count++;

    $class = '';

    switch ( $count ) {
        case '1':
            $class = 'one';
            break;
        case '2':
            $class = 'two';
            break;
        case '3':
            $class = 'three';
            break;
    }

    if ( $class )
        echo 'class="' . $class . '"';
}

if ( ! function_exists( 'initializr_adaptive_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own initializr_adaptive_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Initializr Adaptive 1.0
 */
function initializr_adaptive_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' :
    ?>
    <li class="post pingback">
        <p><?php _e( 'Pingback:', 'initializr_adaptive' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'initializr_adaptive' ), '<span class="edit-link">', '</span>' ); ?></p>
    <?php
            break;
        default :
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <div id="comment-<?php comment_ID(); ?>" class="comment">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php
                        $avatar_size = 68;
                        if ( '0' != $comment->comment_parent )
                            $avatar_size = 39;

                        echo get_avatar( $comment, $avatar_size );

                        /* translators: 1: comment author, 2: date and time */
                        printf( __( '%1$s on %2$s <span class="says">said:</span>', 'initializr_adaptive' ),
                            sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
                            sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
                                esc_url( get_comment_link( $comment->comment_ID ) ),
                                get_comment_time( 'c' ),
                                /* translators: 1: date, 2: time */
                                sprintf( __( '%1$s at %2$s', 'initializr_adaptive' ), get_comment_date(), get_comment_time() )
                            )
                        );
                    ?>

                    <?php edit_comment_link( __( 'Edit', 'initializr_adaptive' ), '<span class="edit-link">', '</span>' ); ?>
                </div><!-- .comment-author .vcard -->

                <?php if ( $comment->comment_approved == '0' ) : ?>
                    <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'initializr_adaptive' ); ?></em>
                    <br />
                <?php endif; ?>

            </footer>

            <div class="comment-content"><?php comment_text(); ?></div>

            <div class="reply">
                <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'initializr_adaptive' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div><!-- .reply -->
        </div><!-- #comment-## -->

    <?php
            break;
    endswitch;
}
endif; // ends check for initializr_adaptive_comment()

if ( ! function_exists( 'initializr_adaptive_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own initializr_adaptive_posted_on to override in a child theme
 *
 * @since Initializr Adaptive 1.0
 */
function initializr_adaptive_posted_on() {
    printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'initializr_adaptive' ),
        esc_url( get_permalink() ),
        esc_attr( get_the_time() ),
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() ),
        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
        esc_attr( sprintf( __( 'View all posts by %s', 'initializr_adaptive' ), get_the_author() ) ),
        get_the_author()
    );
}
endif;

/**
 * Adds two classes to the array of body classes.
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 *
 * @since Initializr Adaptive 1.0
 */
function initializr_adaptive_body_classes( $classes ) {

    if ( function_exists( 'is_multi_author' ) && ! is_multi_author() )
        $classes[] = 'single-author';

    if ( is_singular() && ! is_home() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) )
        $classes[] = 'singular';

    return $classes;
}
add_filter( 'body_class', 'initializr_adaptive_body_classes' );

