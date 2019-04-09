<?php
/**
 * @package j3-mail-chimp
 * @version 1.0
 */
/*
Plugin Name: J3 MailChimp
Plugin URI: https://github.com/wildcomputations/j3-theme
Description: boilerplate mailchimp html
Author: Emilie Phillips
Version: 1.0
Author URI: http://j3.org/

*/

/*****************************************************************
 * API Functions that themes can call                            *
 *****************************************************************/

function j3mc_enqueue_scripts()
{
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'j3mc', $plugin_url . 'style.css', '0.1' );
}
add_action( 'wp_enqueue_scripts', 'j3mc_enqueue_scripts' );

function j3mc_embed( $submit_url, $group_num, $list, $group_id,
    $robot_name, $submit_txt)
{
    return <<<HTML
<div id="mc_embed_signup">
<form action="{$submit_url}" method="post"
 id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
 class="validate" target="_blank" novalidate>
    <div id="mc_embed_signup_scroll">
	<input type="email" value="" name="EMAIL" class="required email"
	 placeholder="email address" id="mce-EMAIL">
	<input type="hidden" value="{$group_num}"
	 name="group[{$list}]"
	 id="mce-group[{$list}]-{$list}-{$group_id}">
        <div style="position: absolute; left: -5000px;" aria-hidden="true">
            <input type="text" name="{$robot_name}"
             tabindex="-1" value="">
        </div>
        <div class="clear">
            <input type="submit" value="{$submit_txt}"
             name="subscribe" id="mc-embedded-subscribe" class="button">
	</div>
    </div>
</form>
</div>
HTML;
}

function j3mc_embed_opt( $atts ) {
    return j3mc_embed($atts['submit_url'],
                      $atts['group_num'],
                      $atts['list'],
                      $atts['group_id'],
                      $atts['robot'],
                      $atts['submit']);
}
add_shortcode( 'j3mc', 'j3mc_embed_opt' );
