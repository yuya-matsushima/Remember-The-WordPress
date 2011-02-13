<?php
/*
Plugin Name: Remember The WordPress
Plugin URI: http://github.com/e2esoundcom/Remember-The-WordPress
Description: If you forgot write a new post,this plugin send you E-mail.
Version: 0.2.1
Author: Yuya Terajima
Author URI: http://www.e2esound.com/
*/
/*  Copyright 2010 Yuya Terajima/e2esound.com (email:terra@e2esound.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('RTW_UNIXTIME_PER_DAY', 86400);


/* When Activate Plugin
==================================================================== */
function rtw_activate() {

    $time = time();
    $terms = 7; //default terms
    $email = get_bloginfo('admin_email');
    $subject = "Remember the WordPress!!";
    $message = "Blogの更新が滞っているようです。サイトの更新をお願いします。";

    if(!get_option('rtw_initialized') || get_option('rtw_initialized') !== $time) {
        update_option('rtw_initialized',$time);
    }
    if(!get_option('rtw_terms')) {
        update_option('rtw_terms',$terms * RTW_UNIXTIME_PER_DAY);
    }
    if(!get_option('rtw_email')) {
        update_option('rtw_email',$email);
    }
    if(!get_option('rtw_subject')) {
        update_option('rtw_subject',$subject);
    }
    if(!get_option('rtw_message')) {
        update_option('rtw_message',$message);
    }
    wp_schedule_event($time + RTW_UNIXTIME_PER_DAY, 'daily', 'rtw_cron');
}
register_activation_hook(__FILE__, 'rtw_activate');

function get_latest_post_time() {
    global $wpdb;
    $query = "SELECT post_date FROM ".$wpdb->posts."
            WHERE post_status = 'publish' AND post_type = 'post'
            ORDER BY `".$wpdb->posts."`.`post_date` DESC LIMIT 0,1";
    $query = $wpdb->prepare($query);
    return strtotime($wpdb->get_var($query));
    }

function rtw_compare_time(){
    $latest = get_latest_post_time();
    $terms = intval(get_option('rtw_terms')) + intval($latest);

    if(intval($terms) < intval(time())) {
        $subject  = get_option('rtw_subject');
        $mailbody = get_option('rtw_message');
        $to_email = get_option('rtw_email');

        mb_send_mail($to_email,$subject,$mailbody);
    }
}
add_action('rtw_cron', 'rtw_compare_time');

/* When New Post or Edited Post.
==================================================================== */

function rtw_new_posted(){
    update_option('rtw_initialized',time());
    }
add_action('publish_post','rtw_new_posted');

/* When De-Activate Plugin
==================================================================== */

function rtw_deactivate() {
    wp_clear_scheduled_hook('rtw_cron');
    delete_option('rtw_initialized');
    delete_option('rtw_terms');
    delete_option('rtw_message');
    delete_option('rtw_subject');
    delete_option('rtw_email');
}
register_deactivation_hook(__FILE__, 'rtw_deactivate');


/* Admin
==================================================================== */
require_once 'rtw_admin_view.php';

function rtw_add_admin_menu(){
    add_options_page('Remember The WordPress','Remember The WP','administrator',__FILE__,'rtw_add_admin_page');
}
add_action('admin_menu','rtw_add_admin_menu');

/* End of file remember_the_wordpress.php */
