<?php get_header(); ?>

<div class="page-content">

        <div class="gutenberg">
            <?php if (have_posts()):
                while (have_posts()):
                    the_post(); ?>

                    <?php the_content(); ?>

                <?php endwhile; endif; ?>

        </div>

</div>


<?php get_footer(); ?>