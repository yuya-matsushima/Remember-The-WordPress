<?php 
function rtw_add_admin_page() {
    //blog charset from Options table
    $blog_charset = get_option('blog_charset', 'UTF-8');

    $posted = (isset($_POST['posted'])) ? TRUE : FALSE;

    if($posted) {
        //Validation
        if(preg_match('/[1-3][0-9]|[1-9]/',intval($_POST['terms']) AND intval($_POST['terms']) <= 30)){
            update_option('rtw_terms',intval($_POST['terms'] * RTW_UNIXTIME_PER_DAY));
            update_option('rtw_email',stripslashes($_POST['email']));
            update_option('rtw_subject',stripslashes($_POST['subject']));
            update_option('rtw_message',stripslashes($_POST['message']));
            $rtw_error = FALSE;
        }else{
            $rtw_error = TRUE;
        }
    }
?>



<?php //Updated Message
if($posted === TRUE AND $rtw_error === FALSE):?>
    <div class="updated">
        <p>
            <strong>設定を保存しました</strong>
        </p>
    </div>

<?php elseif($posted === TRUE AND $rtw_error === TRUE):?>
    <div class="error">
        <p>
            <strong>アラート発生日数は1-30の間の値を入力して下さい。</strong>
        </p>
    </div>

<?php endif; //Admin  View
?>

<div class="wrap">
    <h2>Remember The WordPress Settings</h2>
        <form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
            <input type="hidden" name="posted" value="yes">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">
                            <label for="terms">アラート発生日数<label>
                        </th>
                        <td>
                            <input name="terms" type="text" id="terms" value="<?php echo intval(get_option('rtw_terms') / RTW_UNIXTIME_PER_DAY); ?>" class="regular-text code" /><br />
                    1-30までの数字を入力してください。
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="email">送信先E-mailアドレス<label>
                        </th>
                        <td>
                            <input name="email" type="text" id="email" value="<?php echo htmlspecialchars(get_option('rtw_email'), ENT_QUOTES,
 $blog_charset); ?>" class="regular-text code" /><br />
                    アラートメールの送信先E-mailアドレスを入力してください。
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="terms">メールタイトル<label>
                        </th>
                        <td>
                            <input name="subject" type="text" id="subject" value="<?php echo htmlspecialchars(get_option('rtw_subject'), ENT_QUOTES, $blog_charset); ?>" class="regular-text code" /><br />
                    送信メッセージの「タイトル」を入力してください。
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="terms">メール本文<label>
                        </th>
                        <td>
                            <textarea name='message' id='message' cols='50' rows='10'><?php echo htmlspecialchars(get_option('rtw_message'), ENT_QUOTES, $blog_charset); ?></textarea><br />
                    送信メッセージの「内容」を入力してください。
                        </td>
                    </tr>
                </table>

            <p class="submit">
                <input type="submit" name="Submit" class="button-primary" value="変更を保存" />
            </p>
    </form>
</div>
<?php }

/* End of file rtw_admin_view.php */ 

