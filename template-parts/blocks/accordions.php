<?php
// Create id attribute allowing for custom "anchor" value.
$id = 'accordions-' . $block['id'];

// Create class attribute allowing for custom "className" and "align" values.
$className = 'accordions';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}
$accordions = get_field('accordions');
?>
<?php if( $accordions ): $i = 0; ?>
<div class="accordions-wrapper" id="<?php echo $id; ?>">

   <?php foreach( $accordions as $a ): ?>
    <div class="accordion">
        <div class="accordion-title"><?php echo $a['title']; ?></div>
        <div class="accordion-content">
            <?php echo $a['text']; ?>
        </div>
    </div>   
    <?php endforeach; ?>

</div>

<script>
 /*   jQuery(document).ready(function(){
        jQuery('.accordion-title').click( function() {
            jQuery(this).toggleClass('opened');
                       jQuery(this).next().slideToggle().stop();
                        jQuery(this).parent().siblings().find('.accordion-content').slideUp();
                       jQuery(this).parent().siblings().find('.accordion-title').removeClass('opened');
        });//accordion
});//ready*/
</script>

<?php endif; ?>
