<div class="flex"  id="nextpreviouslinks">
  <?php if (strlen(get_previous_post()->post_title) > 0): ?>
    
  <div class="prev-post ">
      <p>Previous</p>
    <div class="arrow flex flex-vertical-center">
      
      <svg width="20" height="12" viewBox="0 0 20 12" fill="none" xmlns="http://www.w3.org/2000/svg">
<path opacity="0.8" d="M18.5 6.8C18.9418 6.8 19.3 6.44183 19.3 6C19.3 5.55817 18.9418 5.2 18.5 5.2V6.8ZM0.434315 5.43431C0.121895 5.74673 0.121895 6.25327 0.434315 6.56569L5.52548 11.6569C5.8379 11.9693 6.34443 11.9693 6.65685 11.6569C6.96927 11.3444 6.96927 10.8379 6.65685 10.5255L2.13137 6L6.65685 1.47452C6.96927 1.1621 6.96927 0.655565 6.65685 0.343146C6.34443 0.0307264 5.8379 0.0307264 5.52548 0.343146L0.434315 5.43431ZM18.5 5.2L1 5.2L1 6.8L18.5 6.8V5.2Z" fill="#65c18d"/>
</svg>
        
        <?php previous_post_link( '%link', '%title'); ?>
    </div>
    
  </div>
  <?php else: ?>
    <div class="prev-post ">
      <p>Previous</p>
    <div class="arrow flex flex-vertical-center">
      
      <svg width="20" height="12" viewBox="0 0 20 12" fill="none" xmlns="http://www.w3.org/2000/svg">
<path opacity="0.8" d="M18.5 6.8C18.9418 6.8 19.3 6.44183 19.3 6C19.3 5.55817 18.9418 5.2 18.5 5.2V6.8ZM0.434315 5.43431C0.121895 5.74673 0.121895 6.25327 0.434315 6.56569L5.52548 11.6569C5.8379 11.9693 6.34443 11.9693 6.65685 11.6569C6.96927 11.3444 6.96927 10.8379 6.65685 10.5255L2.13137 6L6.65685 1.47452C6.96927 1.1621 6.96927 0.655565 6.65685 0.343146C6.34443 0.0307264 5.8379 0.0307264 5.52548 0.343146L0.434315 5.43431ZM18.5 5.2L1 5.2L1 6.8L18.5 6.8V5.2Z" fill="#65c18d"/>
</svg>
        
         <?php 
// WP_Query arguments
$args = array(
  'post_type'              => array( 'team' ),
  'post_status'            => array( 'publish' ),
  'order'                  => 'DESC',
  'orderby'                => 'date',
  'posts_per_page'         => '1',
   'no_found_rows' => true
);

// The Query
$query = new WP_Query( $args );

// The Loop
if ( $query->have_posts() ) {
  while ( $query->have_posts() ) {
    $query->the_post();
    echo "<a href='". get_the_permalink() ."'>". get_the_title() ."</a>";
  }
}

// Restore original Post Data
wp_reset_postdata();
          ?>
    </div>
    
  </div>
  <?php endif; ?>


  <?php if (strlen(get_next_post()->post_title) > 0): ?>
  <div class="next-post">
    <p>Next</p>
    <div class="arrow flex flex-vertical-center">
        
         <?php next_post_link( '%link', '%title' ); ?>
        <svg width="20" height="12" viewBox="0 0 20 12" fill="none" xmlns="http://www.w3.org/2000/svg">
<path opacity="0.8" d="M1 5.2C0.558172 5.2 0.200001 5.55817 0.200001 6C0.200001 6.44183 0.558172 6.8 1 6.8L1 5.2ZM19.0657 6.56569C19.3781 6.25327 19.3781 5.74673 19.0657 5.43431L13.9745 0.343145C13.6621 0.030726 13.1556 0.030726 12.8431 0.343145C12.5307 0.655565 12.5307 1.1621 12.8431 1.47452L17.3686 6L12.8431 10.5255C12.5307 10.8379 12.5307 11.3444 12.8431 11.6569C13.1556 11.9693 13.6621 11.9693 13.9745 11.6569L19.0657 6.56569ZM1 6.8L18.5 6.8L18.5 5.2L1 5.2L1 6.8Z" fill="#65c18d"/>
</svg>
    </div>
   
  </div>
  <?php else: ?>
  <div class="next-post">
    <p>Next</p>
    <div class="arrow flex flex-vertical-center">
        
         <?php 
// WP_Query arguments
$args = array(
  'post_type'              => array( 'team' ),
  'post_status'            => array( 'publish' ),
  'order'                  => 'ASC',
  'orderby'                => 'date',
  'posts_per_page'         => '1',
   'no_found_rows' => true
);

// The Query
$query = new WP_Query( $args );

// The Loop
if ( $query->have_posts() ) {
  while ( $query->have_posts() ) {
    $query->the_post();
    echo "<a href='". get_the_permalink() ."'>". get_the_title() ."</a>";
  }
}

// Restore original Post Data
wp_reset_postdata();
          ?>
        <svg width="20" height="12" viewBox="0 0 20 12" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path opacity="0.8" d="M1 5.2C0.558172 5.2 0.200001 5.55817 0.200001 6C0.200001 6.44183 0.558172 6.8 1 6.8L1 5.2ZM19.0657 6.56569C19.3781 6.25327 19.3781 5.74673 19.0657 5.43431L13.9745 0.343145C13.6621 0.030726 13.1556 0.030726 12.8431 0.343145C12.5307 0.655565 12.5307 1.1621 12.8431 1.47452L17.3686 6L12.8431 10.5255C12.5307 10.8379 12.5307 11.3444 12.8431 11.6569C13.1556 11.9693 13.6621 11.9693 13.9745 11.6569L19.0657 6.56569ZM1 6.8L18.5 6.8L18.5 5.2L1 5.2L1 6.8Z" fill="#65c18d"/>
        </svg>
    </div>
   
  </div>
  <?php endif; ?>
</div>