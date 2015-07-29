<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. 
 *
 * @package j3Custom
 */

if ( post_password_required() ) {
	return;
}
?>


<?php if ( have_comments() || comments_open() ) {
?>
<div class="hgroup hasPage">
    <div class="rightContent">
        <div class="visualPage commentBlock">
<?php if ( have_comments() ) {
    echo '<h1>Comments</h1>';
    wp_list_comments(); 
}
    comment_form();
}
?>
        </div> <!-- commentBlock -->
    </div> <!-- rightContent -->
</div> <!-- hgroup -->
