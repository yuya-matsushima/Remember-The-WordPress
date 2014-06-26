# Remember The WordPress

__Remember The WordPress__ は [WordPress](http://wordpress.org/) の Plugin です。

ユーザの記事投稿, 記事編集の状態を監視し, 一定期間の更新がない場合には 1 日に 1 回アラートメール(テキスト)を送信します。

参考: [任意の期間、ブログが更新されないとメールが飛ぶwordpressのplugin - Help me, hackers!](http://help-me-hackers.com/tasks/93)

## 使い方

1. WordPress の `Plugin` ディレクトリにアップロード
2. 管理画面の Plugin ページで有効化
3. 管理画面の `設定` > `Remember the WP` をクリック
4. 表示されたページで次の内容を設定
    1. アラート発生日数(初期設定: `7`)
    　 - 何日間更新されなかった場合にアラートメールを送るか
    2. 送信先メールアドレス(初期設定: 設定画面の管理者メールアドレス)
    3. メールタイトル(初期設定: `Rmember the WordPress!!`)
    4. メール本文(初期設定: `Blogの更新が滞っているようです。サイトの更新をお願いします。`)

5. エンコードはUTF-8です。他の文字エンコードでWordPressを運用している場合には、Pluginファイルを同じ文字エンコードに変更してください。


## プラグインを停止しても前回の設定を残したい場合

Plugin 画面から Remember The WordPress を停止すると、次回有効化した場合に全ての値を再度設定しなければなりません。もし、何度も停止/有効化を繰り返す場合には、Plugin の次ののコードを削除してください。

    // remember_the_wordpress.php 78-83行目
    delete_option('rtw_initialized');
    delete_option('rtw_terms');
    delete_option('rtw_message');
    delete_option('rtw_subject');
    delete_option('rtw_email');

このコードを削除すると、Plugin 停止時に設定された値を残します。

## Author

- Name: Yuya Terajima(e2esound.com)
- Twitter: [@yterajima](https://twitter.com/yterajima)

