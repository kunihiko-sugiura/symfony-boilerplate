#Symfony BoilerPlate based on Symfony3 & standard bundle

# はじめに
Symfonyのアプリケーション構築の多くはコマンドラインベースですすめます。
なので以下に色々と記載しておきます。

# 予備知識として

PHPの標準コーディング規約のPSRに関しての情報です。
多くのPHPフレームワークがこの規格に乗っ取っているので目を通しておいた方がいいらしいです。
https://www.infiniteloop.co.jp/blog/2012/10/psrphp/
http://www.infiniteloop.co.jp/docs/psr/psr-0.html
http://www.infiniteloop.co.jp/docs/psr/psr-1-basic-coding-standard.html
http://www.infiniteloop.co.jp/docs/psr/psr-2-coding-style-guide.html

# Symfonyの公式ドキュメント（英語ですけどね）
https://symfony.com/doc/current/index.html

# 日本Symfonyユーザ会のドキュメント
version2系ですけど参考にはなると思います。
http://docs.symfony.gr.jp/

# 実行環境の種類（Environment）
SensioDistributionBundle , SensioGeneratorBundle により、dev/test/prod の切り分けが可能です。
未指定だと本番環境(app.php)で実行されます。
+ dev:各開発環境
app_dev.phpを指定して実行します。
+ test:本番環境に近いテスト環境
+ prod:本番環境

# コマンドラインツール
## path
プロジェクト内の以下のパスにコマンドラインツールがあります。
phpで実行します。
以下でコマンドの一覧が表示可能です。
```
php bin/console
```

## コマンドオプション表示
以下のようなpipe指定でコマンドの絞込みが可能です。
```
php bin/console list | grep migration
php bin/console list | grep doctrine
php bin/console list | grep generate
```

# アプリケーション初期化
## 環境依存設定
実行環境に依存する設定はparameters.ymlに記載します。
現状DBとメール送信の設定がありますがその他追加してもいいはずです。
以下のファイルを同じディレクトリにparameters.ymlとしてコピーします。
app/config/parameters.yml.dist
parameters.ymlはgitの管理に入れないように注意してください。開発環境の設定が本番に反映されることを防ぐためです。
項目追加したときはparameters.yml.distにも追加しましょう。symfonyが二つのファイルの差分をチェックして差異がある場合警告してくれるそうです。

## ディレクトリアクセス権限
Linuxとmacの場合以下のディレクトリに権限設定します。
```
rm -rf app/cache/*
rm -rf app/logs/*
rm -rf app/sessions/*

sudo chmod +a "_www allow delete,write,append,file_inherit,directory_inherit" var/cache var/logs var/sessions
sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" var/cache var/sessions 
```

## DB削除
これは必要があれば
```
$ php bin/console doctrine:database:drop --force
```

## DB新規作成
parameters.ymlの設定で作成を試みます。
```
$ php bin/console doctrine:database:create
```

## DBマイグレーション（DBのバージョン管理）の初期化
コマンドを実行するとデータベースに migration_versions テーブルが生成されます。
```
$ sudo php bin/console doctrine:migrations:status
```

### マイグレーション実行
DBを最新状態に変更
```
$ php bin/console doctrine:migrations:migrate
```

# アプリケーション構成
## Model
### 要約
DB接続にはDoctrine(ドクトリン)を使います。
さらに、エンティティクラスを定義し、エンティティクラスのマッピング情報から自動的にスキーマを作成する
DoctrineMigrationsを併用するとDB側の定義をバージョン管理できます。

### 利用Bundle
+ Doctrine
+ DoctrineMigrations

### テーブル定義変更の手順
以下の手順をルールとします。

1. Entity作成
以下のコマンドを叩きます。
```
$ php bin/console doctrine:generate:entity
```
+ The Entity shortcut name: <AppBundle:Entity名>
+ Configuration format (yml, xml, php, or annotation) [annotation]: <annotation>
+ New field name (press <return> to stop adding fields): 
IDはデフォルトで定義済み、field name, Field type, Field length, Is nullable,Unique などを指定

以下のPathにファイルが生成される。のでgetter/setterなどの定義を必要な時に作り込む。
src/AppBundle/Entity/<Entity名>.php

#### 各テーブル共通項目
以下のカラムは共通して定義してください。
@Gedmo\Timestampableのアノテーションによりdoctrine/ormを使用してデータ操作する時に自動的に作成日、更新日を設定できます。

```
// Entityクラスで以下をuseする。
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @var \DateTime
 * @Gedmo\Timestampable(on="create")
 * @ORM\Column(name="created_at", type="datetime")
 */
private $createdAt;

/**
 * @var \DateTime
 * @Gedmo\Timestampable(on="update")
 * @ORM\Column(name="updated_at", type="datetime")
 */
private $updatedAt;

/**
 * @var \DateTime
 * @ORM\Column(name="deleted_at", type="datetime")
 */
private $deletedAt;
```

#### getter/setterの自動生成
Entity修正したときはとりあえず実行しとけば、作ってくれる。
```
php bin/console doctrine:generate:entities AppBundle/Entity/User
```

2. 作成したEntityからDBマイグレーションファイル（DBの実体とエンティティの差分をSQL化したもの）を作成
Version up/downを自動で定義してくれます。
```
php bin/console doctrine:migrations:diff
```
以下の形式でファイルが作成されます。
app/DoctrineMigrations/VersionYYYYMMDDHHMISS.php

3. Migrationを実行してDBに反映
バージョン指定無しだと最新のバージョンまで実行します。
```
$ php bin/console doctrine:migrations:migrate
$ php bin/console doctrine:migrations:migrate <version no>
```
以下をつけると実行確認をスキップ可能
--no-interaction

### Entityに対してcrud( create, read, update, delete )のController/Actionを自動生成
試してないけど自動で生成してくれるらしい！
```
php bin/console doctrine:generate:crud
```


## Controller
### ディレクトリPath
```
src/AppBundle/Controller
```

### 基本クラスの構成
ある程度、controllerの共通化処理が発生することを見越して、継承関係を作っておきました。
+ BaseController.php
Admin,Client用のコントローラーの元となるクラスです。
全画面用の共通処理をかけます。

+ AdminController.php
Admin用のコントローラーの元となるクラスです。
Admin用の共通処理をかけます。

+ ClientController.php
Client用のコントローラーの元となるクラスです。
Client用の共通処理をかけます。

### 作成
```
php bin/console generate:controller
```
+ Controller name::コントローラー名を指定。 バンドル名:コントローラ名 で指定。<AppBundle:Index>
+ Routing format::annotationを使用。デフォルト設定なので空で良い。
+ Template format::twigを使用。デフォルト設定なので空で良い。
+ New action name::アクションメソッドの雛形を生成する場合は名前を指定<???Action>の形式
+ Do you confirm generation?::作成の確認。デフォルトでyesになる。


# View
## ディレクトリPath
```
src/AppBundle/Resources/views
```

## Bundle
+ Twig


# Bundleについて
「Bundle=単品でも機能する部品」と捉えるべし。変更しないで移植できないBundleや他のBundleに依存しているBundleは、Bundleとはいえない。
だそうです。
なので自作の共通処理系はまとめて別のbundleにするのが良いかもしれません。
膨れてきたらさらに細分化してもいいと思います。模索しましょう。

### オリジナルのbundleの作り方
```
php bin/console generate:bundle  --namespace=Acme/SecurityBundle
```


# Log
## Bundle
+ Monolog

## ディレクトリPath
以下ディレクトリに出力されます。
```
var/logs
```


# その他
## Symfonyのキャッシュクリア
コード修正したのにアプリケーションに反映されない場合は、キャッシュが働いているのでクリアしましょう。
```
php bin/console cache:clear
php bin/console cache:clear --env=prod
```

## パスワード変換ツール
```
php bin/console security:encode-password
```
