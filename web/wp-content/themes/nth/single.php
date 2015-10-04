<?php get_header(); ?>

	<!-- main -->
	<main id="main">

		<div class="container">

			<section class="single-post">

			<?php if (have_posts()): while (have_posts()) : the_post(); ?>

				<!-- article -->
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<header>

						<!-- post title -->
				        <h1><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
				        <!-- /post title -->
				        <!-- post details -->
				        <span class="author">by <?php the_author_posts_link(); ?></span>
				        <time datetime="<?php the_time('Y-m-d H:i'); ?>"><?php the_time('F j, Y'); ?> <?php the_time('g:i a'); ?></time>
						<!-- /post details -->

				    </header>

					<!-- post thumbnail -->
					<?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php the_post_thumbnail(); // Fullsize image for the single post ?>
						</a>
					<?php endif; ?>
					<!-- /post thumbnail -->

					<?php the_content(); // Dynamic Content ?>

					<footer>
						<!-- <span class="comments"><?php comments_popup_link( __( 'Leave your thoughts', 'html5blank' ), __( '1 Comment', 'html5blank' ), __( '% Comments', 'html5blank' )); ?></span> -->

						<?php comments_template(); ?>

						<?php get_template_part('parts/_tags'); ?>

						<?php get_template_part('parts/_categories'); ?>

						<?php edit_post_link(); ?>

					</footer>

				</article>
				<!-- /article -->

			<?php endwhile; ?>

			<?php else: ?>

				<!-- article -->
				<article>

					<h1><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h1>

				</article>
				<!-- /article -->

			<?php endif; ?>

			</section>

			<?php get_sidebar(); ?>

		</div>

	</main>
	<!-- /main -->

<?php get_footer(); ?>
