<?php
/*
Plugin Name: Remember The WordPress
Plugin URI: http://github.com/e2esoundcom/Remember-The-WordPress
Description: If you forgot write a new post,this plugin send you E-mail.
Version: 0.1
Author: Yuya Terajima
Author URI: http://www.e2esound.com/
*/

/* When Activate Plugin
==================================================================== */
function rtw_activate() {
    $time = time();
    $terms = 7; //default terms
    $unixtime_per_day = 86400;
    $email = get_bloginfo('admin_email');
    $subject = "Remember the WordPress!!";
    $message = "Blogの更新が滞っているようです。サイトの更新をお願いします。";

    if(!get_option('rtw_initialized') || get_option('rtw_initialized') !== $time) {
        update_option('rtw_initialized',$time);
    }
    if(!get_option('rtw_terms')) {
        update_option('rtw_terms',$terms * $unixtime_per_day);
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
    wp_schedule_event($time + 86400, 'daily', 'rtw_cron');
}
register_activation_hook(__FILE__, 'rtw_activate');

function get_latest_post_time() {
    global $wpdb;
    $query = "SELECT post_date FROM ".$wpdb->posts." 
            WHERE post_status = 'publish' 
            ORDER BY `".$wpdb->posts."`.`post_date` DESC LIMIT 0,1";
    $query = $wpdb->prepare($query);
    return strtotime($wpdb->get_var($query));
    }

function rtw_sendmail()
{
    $subject  = get_option('rtw_subject');
    $mailbody = get_option('rtw_message');
    $to_email = get_option('rtw_email');
    
    mb_send_mail($to_email,$subject,$mailbody);
}

function rtw_compare_time(){
    $unixtime_per_day = 86400;
    $latest = get_latest_post_time();
    $terms = intval(get_option('rtw_terms')) + intval($latest);
    $time = time();

    if(intval($terms) < intval($time)){
        rtw_sendmail();
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
function rtw_add_admin_menu(){
    add_options_page('Remember The WordPress','Remember The WP','administrator',__FILE__,'rtw_add_admin_page');
}
add_action('admin_menu','rtw_add_admin_menu');

function rtw_add_admin_page(){    
    
    if(isset($_POST['posted']) === FALSE){
        $posted = FALSE;
    }elseif(isset($_POST['posted']) === TRUE){
        $posted = TRUE;
    }

    $unixtime_per_day = 86400;
    if($posted) {
        //Validation
        if(preg_match('/[1-3][0-9]|[1-9]/',intval($_POST['terms']) AND intval($_POST['terms']) <= 30)){
            update_option('rtw_terms',intval($_POST['terms'] * $unixtime_per_day));
            update_option('rtw_email',stripslashes($_POST['email']));
            update_option('rtw_subject',stripslashes($_POST['subject']));
            update_option('rtw_message',stripslashes($_POST['message']));
            $rtw_error = FALSE;
        }else{
            $rtw_error = TRUE;
        }
    }
?>

<?php 
    //Admin Page Start
    //Updated Message
    if($posted === TRUE AND $rtw_error === FALSE) : ?>
<div class="updated"><p><strong>設定を保存しました</strong></p></div>
<?php elseif($posted === TRUE AND $rtw_error === TRUE):?>
<div class="error"><p><strong>アラート発生日数は1-30の間の値を入力して下さい。</strong></p></div>
<?php endif; ?>

<div class="wrap">
	<h2>Remember The WordPress Settings</h2>
	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="posted" value="yes">
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="terms">アラート発生日数<label></th>
				<td>
                    <input name="terms" type="text" id="terms" value="<?php echo intval(get_option('rtw_terms') / $unixtime_per_day); ?>" class="regular-text code" /><br />
                    1-30までの数字を入力してください。
                </td>
            </tr>
			<tr valign="top">
				<th scope="row"><label for="email">送信先E-mailアドレス<label></th>
				<td>
					<input name="email" type="text" id="email" value="<?php echo htmlspecialchars(get_option('rtw_email'),ENT_QUOTES); ?>" class="regular-text code" /><br />
                    アラートメールの送信先E-mailアドレスを入力してください。
                </td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="terms">メールタイトル<label></th>
				<td>
					<input name="subject" type="text" id="subject" value="<?php echo htmlspecialchars(get_option('rtw_subject'),ENT_QUOTES); ?>" class="regular-text code" /><br />
                    送信メッセージの「タイトル」を入力してください。
                </td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="terms">メール本文<label></th>
				<td>
                <textarea name='message' id='message' cols='50' rows='10'><?php echo htmlspecialchars(get_option('rtw_message'),ENT_QUOTES); ?></textarea>
                    <br />
                    送信メッセージの「内容」を入力してください。
                </td>
            </tr>

            
		</table>

		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="変更を保存" />
		</p>
	</form>
</div>
<?php } ?>
