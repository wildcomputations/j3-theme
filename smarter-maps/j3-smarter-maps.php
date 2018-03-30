<?php
/**
 * @package j3-smarter-maps
 * @version 1.0
 */
/*
Plugin Name: Smarter Maps
Plugin URI: https://github.com/wildcomputations/j3-theme
Description: Better short code interface to responsive-maps-plugin
Author: Emilie Phillips
Version: 1.0
Author URI: http://j3.org/

[j3-maps point1="name|address|icon|long text"]
*/

function j3_smarter_maps($attrs)
{
    $short_names = array();
    $addresses = array();
    $icons = array();
    $html = array();
    $forward_attrs = array();
    foreach ($attrs as $key => $value) {
        if (strncmp($key, "point", 5)) {
            $forward_attrs[$key] = $value;
        } else {
            $pieces = explode("|", $value);
            if (count($pieces) != 4) {
                print_r($pieces);
                echo $key . " formatted wrong. Should be "
                    .'"name|address|icon|long text"';
                return;
            }
            $short_names[] = $pieces[0];
            $addresses[] = $pieces[1];
            $icons[] = $pieces[2];
            $html[] = $pieces[3];
        }
    }
    $marker_links = "";
    for ($i = 0; $i < count($short_names); $i++) {
        $marker_links .= '<a href="javascript: openMarker(1, '
            . ($i + 1) .');">' . $short_names[$i] . '</a><br>';
    }
    $addresses_flat = join(" | ", $addresses);
    $icons_flat = join(" | ", $icons);
    $html_flat = join(" | ", $html);
    $forward_attrs["address"] = $addresses_flat;
    $forward_attrs["description"] = $html_flat;
    $forward_attrs["icon"] = $icons_flat;
    echo resmap_shortcode($forward_attrs);
    echo "<br>" . $marker_links;
}
add_shortcode( 'j3-map', 'j3_smarter_maps' );
