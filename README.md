# web-api-security-entry

## 概要

2022年12月29日 勉強会

テーマ:「WebAPIセキュリティ入門」

目的: SPAのバックエンドを構築する際にセキュリティ面で気をつけるべき基本的な事項を把握する

## サンプルのアプリケーションについて

- 会員制の記事投稿/閲覧サービス
  - [画面・APIの一覧](https://docs.google.com/spreadsheets/d/10D5yy6efv4NhDyhyBmvybTXzlywX2OmH0qLfZ8fhLQs/edit?usp=sharing)
- 構成
  - バックエンド:
    - APP: Laravel 9.x
    - DB: MySQL 8.0
  - フロントエンド: Vue.js 2.x

## 参考

- [安全なウェブサイトの作り方 | IPA](https://www.ipa.go.jp/security/vuln/websecurity.html)
- [徳丸浩「体系的に学ぶ 安全なWebアプリケーションの作り方 第2版」](https://www.sbcr.jp/product/4797393163/)
- [徳丸浩「SPAセキュリティ超入門」PHP Conference Japan 2022](https://fortee.jp/phpcon-2022/proposal/934a985e-fc87-4cab-8e13-99ea5b1b5ce1)
- [徳丸浩「SPAセキュリティ入門」PHP Conference Japan 2021](https://fortee.jp/phpcon-2021/proposal/2bcd3065-ef89-4b2d-96ec-bd5163257cef)

## 環境構築

### 要件

- Docker Desktop

.devcontainerを使用する場合は以下も必要

- Visual Studio Code
- Dev Containers（拡張機能）

### 手順

本リポジトリをローカルにクローン

```bash
git clone git@github.com:dt30harada/web-api-security-entry.git \
  && cd web-api-security-entry
```

.envを作成

```bash
cp .env.example .env
```

コンテナを起動

```bash
./vessel start
```

バックエンド用依存パッケージをインストール

```bash
./vessel composer i
```

DBマイグレーションを実行

```bash
./vessel artisan migrate
```

フロントエンド用依存パッケージをインストール

```bash
./vessel npm ci
```

アセットのビルド

```bash
./vessel npm run dev
```

サンプルアプリケーションにアクセス

```bash
open http://localhost:3001/login
```
