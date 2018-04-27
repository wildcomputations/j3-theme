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

if (comments_open()) {
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $name = __( 'Name', 'domainreference' );
    $email = __( 'Email', 'domainreference' );
    $fields = array(
        'author' =>
        '<p class="comment-form-author"><label class="aligned" for="author">' . $name .
        ( $req ? '<span class="required">*</span>' : '' ) . '</label>' .
        '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
        '" size="30"' . $aria_req . ' /></p>',

        'email' =>
        '<p class="comment-form-email"><label class="aligned" for="email">' . $email .
        ( $req ? '<span class="required">*</span>' : '' ) . '</label>' .
        '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
        '" size="30"' . $aria_req . ' /> <span class="comment-notes">for notification of replies.</span></p>',
    );
    $policy_slug = get_option('j3SetCommentsPolicy');
    if ($policy_slug) {
        $policy_text = '<a href="'
            . get_permalink( get_page_by_path( $policy_slug ) )
            . '" class="comment-notes">Comment Policy</a>';
    } else {
        $policy_text = '';
    }
    $args =  array(
        'title_reply' => 'What do you think?',
        'title_reply_before' => '<h1 id="reply-title" class="comment-reply-title">',
        'title_reply_after' => '</h1>',
        'comment_notes_before' => $policy_text,
        'fields' => $fields,
    );
    comment_form($args);
}

if (have_comments()) {
    echo '<div class="commentBlock">';
    echo '    <h1>Comments</h1>';
    wp_list_comments(); 
    echo '</div> <!-- commentBlock -->';
}
