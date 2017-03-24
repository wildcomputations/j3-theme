<?php 

function j3PostHideHtml($post)
{
    $currentlyHidden = get_post_meta($post->ID, "hidepost", true);
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'j3MetaBoxHide', 'meta_box_nonce' );
?>
<input type="checkbox" name="hidepost"
<?php checked( $currentlyHidden, 'hide' ); ?> />
<label for="hidepost">Only show in archives</label>
<?php
}

