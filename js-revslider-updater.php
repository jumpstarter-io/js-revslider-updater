<?php
/**
 * Plugin Name: Jumpstarter Revslider Updater
 * Plugin URI: https://github.com/jumpstarter-io/
 * Description: Updates Revslider image urls on init if siteurl has changed.
 * Author: Jumpstarter
 * Author URI: https://jumpstarter.io/
 * License: Public Domain
 */

// Don't load directly.
if (!defined("ABSPATH"))
    die("-1");

add_action("init", function() {
    global $wpdb;
    $url_from = get_option("revslider_siteurl_old");
    $url_to = get_option("siteurl");
    if ($url_from === $url_to) {
        // No change.
        return;
    }
    // Get all revsliders from wp_revslider_sliders.
    $sliders = $wpdb->get_results("SELECT id from wp_revslider_sliders");
    // Update all sliders with the new information
    foreach ($sliders as $slider) {
        $options = array("url_from" => $url_from, "url_to" => $url_to, "sliderid" => $slider->id);
        $rev_slider = new RevSlider();
        $rev_slider->replaceImageUrlsFromData($options);
    }
    update_option("revslider_siteurl_old", $url_to);
});
