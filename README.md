# Remember The WordPress

## What's This?
WordPress用のPlugin。
設定した期間、WordPressを更新しないと1日1回アラートメール(テキスト)を飛ばします。
参考：http://help-me-hackers.com/tasks/93


## How to USE
・WordPressのPluginディレクトリにアップロードし有効化
・管理画面の"設定>Remember the WP"をクリック
・画面にて以下の値を修正
    a.アラート発生日数(default:7)
    　何日間更新されなかった場合にアラートメールを送るか
    b.送信先E-mailアドレス(default:設定画面の管理者)
    c.メールタイトル(default:Rmember the WordPress!!)
    d.メール本文(default:Blogの更新が滞っているようです。サイトの更新をお願いします。)

・エンコードはUTF-8です。他の文字エンコードでWordPressを運用している場合には、Pluginファイルを同じ文字エンコードに変更してください。


## 裏技
Pluginを停止すると、次回有効化した場合、全ての値を再度設定しなければなりませ
ん。
もし、何度も停止→有効化を繰り返す場合には、Pluginの以下のコードを削除してくださ
い。

remember_the_wordpress.php 78-83行目
===============================================================================
    delete_option('rtw_initialized');
    delete_option('rtw_terms');
    delete_option('rtw_message');
    delete_option('rtw_subject');
    delete_option('rtw_email');
===============================================================================
このコードを削除すると、Plugin停止時に、設定された値をoptionsテーブルに残しま
す。

## Author
Yuya Terajima(e2esound.com)
blog:http://www.e2esound.com/wp/2010/07/11/remember-the-wordpress/
E-mail:terra@e2esound.com
Twitter:http://twitter.com/terakuma
