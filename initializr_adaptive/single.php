<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Initializr
 * @subpackage Initializr_Adaptive
 * @since Initializr 1.0
 */

get_header(); ?>

        <div class="main-container">
            <div class="main wrapper clearfix">
				<?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<nav id="nav-single">
						<h3 class="assistive-text"><?php _e( 'Post navigation', 'initializr_adaptive' ); ?></h3>
						<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'initializr_adaptive' ) ); ?></span>
						<span class="nav-next"><?php next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'initializr_adaptive' ) ); ?></span>
					</nav><!-- #nav-single -->

					<?php get_template_part( 'content', 'single' ); ?>

					<?php comments_template( '', true ); ?>
					
                </article><!-- #post-<?php the_ID(); ?> -->

				<?php endwhile; // end of the loop. ?>

<?php get_sidebar(); ?>                    

            </div> <!-- #main -->
        </div> <!-- #main-container -->
<?php get_footer(); ?>
