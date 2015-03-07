# マイエディター プラグイン

マイエディター プラグインは、ユーザー別に利用するエディターを設定できるbaserCMS専用のプラグインです。

- [Summary: Wiki](https://github.com/materializing/MyEditor/wiki)


## Installation

1. 圧縮ファイルを解凍後、BASERCMS/app/Plugin/MyEditor に配置します。
2. 管理システムのプラグイン管理に入り、表示されている マイエディター プラグイン をインストールして下さい。（「管理ユーザーのみ利用」の指定でOKです。）
3. プラグインのインストール後、ユーザー情報編集画面にアクセスすると、エディタタイプ設定項目が追加されてます。
4. ログイン中に表示されるツールバー内に、エディター切替えを表示する際はログインし直してください。

## Uses

* ユーザー別のユーザー情報編集画面で、利用するエディターの指定を行う事ができます。
* ログイン中に表示されるツールバー内で、エディターの切替えを行う事ができます。
* ログイン中に、/admin/my_editor/my_editors/init のURLを叩くと、アクセス権限を持たないユーザーグループに、アクセス権限を付与できます。


## Points

* 新規ユーザー追加時は、システム設定＞サイト基本設定 で指定されているエディターが基本値になります。
* インストール時は、システム設定＞サイト基本設定 で指定されているエディターが、ユーザー別のエディターとして設定されます。
* インストール後、ツールバー内にエディター切替えが表示されない場合はログインし直してください。
* インストール後、システム管理グループ以外のユーザーグループでログイン中、ツールバー内にエディター切替えが表示されない場合は、ユーザーグループのアクセス権限を確認し、マイエディター権限が付与されているか確認してください。
* Version 1.0.0 よりバージョンアップした場合、ログインし直すことで、ツールバー内にエディター切替えが表示されます。


## Bug reports, Discuss, Support

- Join online chat at [![Join the chat at https://gitter.im/materializing/MyEditor](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/materializing/MyEditor?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
- [Issue](https://github.com/materializing/MyEditor/issues)


## Editor Introduction

* CkEditor: baserCMS標準
* BurgerEditor: [http://burger.d-zero.com/](http://burger.d-zero.com/)
* AceEditor: [http://qiita.com/YusukeHirao/items/6e73bb828b9bdc6effa6](http://qiita.com/YusukeHirao/items/6e73bb828b9bdc6effa6)
* MarkdownEditor: [https://github.com/YusukeHirao/MarkdownEditor](https://github.com/YusukeHirao/MarkdownEditor)


## Thanks

- [http://basercms.net/](http://basercms.net/)
- [http://wiki.basercms.net/](http://wiki.basercms.net/)
- [http://cakephp.jp](http://cakephp.jp)
