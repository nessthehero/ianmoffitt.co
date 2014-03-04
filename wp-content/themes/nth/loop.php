<?php if (have_posts()): while (have_posts()) : the_post(); ?>
	
	<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>	
		
		<header>

	        <h1><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>    
	        <span class="author">by <?php the_author_posts_link(); ?></span>

	        <time datetime="<?php the_time('Y-m-d H:i'); ?>"><?php the_time('F j, Y'); ?> <?php the_time('g:i a'); ?></time>		

	    </header>  

		<!-- post thumbnail -->
		<?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="the-thumbnail">
				<?php the_post_thumbnail(array(120,120)); // Declare pixel size you need inside the array ?>
			</a>
		<?php endif; ?>
		<!-- /post thumbnail -->		
		
		<?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>	

		<footer>
			<span class="comments">[<?php comments_popup_link( __( 'comment', 'html5blank' ), __( '1 comment', 'html5blank' ), __( '% comments', 'html5blank' )); ?>]</span>

			<?php the_tags("<div class='tag-container'><span class='tags-header'>Tags:</span><dd class='tags'><dl>", "</dl><dl>", "</dl></dd></div>"); ?>

			<div class="cat-container"><span class="cats-header">Categories:</span><dd class="categories">
            	<dl><?php the_category("</dl><dl>"); ?></dl>
            </dd></div>

			<?php edit_post_link(); ?>

		</footer>
		
	</article>
	<!-- /article -->
	
<?php endwhile; ?>

<?php else: ?>

	<!-- article -->
	<article class="post">
		<h1><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h1>
	</article>
	<!-- /article -->

<?php endif; ?>