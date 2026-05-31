# Smart Restaurant POS 🍽️

<!-- ============================================================ -->
<!-- LANGUAGE NAVIGATION                                          -->
<!-- ============================================================ -->
<div align="center">

**[🇨🇳 中文](#-中文版) | [🇺🇸 English](#-english-version) | [🇯🇵 日本語](#-日本語版)**

</div>

---

<!-- ============================================================ -->
<!-- 🇨🇳 CHINESE VERSION                                         -->
<!-- ============================================================ -->

<a id="zh"></a>
## 🇨🇳 中文版

AI 增强的餐饮 POS 系统。PHP 8.4 + Laravel 13 + React 18 + PostgreSQL + OpenRouter API。

> 本项目完全由 AI 工具（Claude Code）辅助开发，PHP/JS/React 代码约 85% 由 AI 生成，开发时间从估计的 2-3 周压缩至 **1 天**。

### 🌐 在线演示

**公网地址**：https://smart-restaurant-lite.onrender.com

| 页面 | URL | 说明 |
|------|-----|------|
| POS 界面 | [/pos](https://smart-restaurant-lite.onrender.com/pos) | 点餐 + 购物车 + 订单提交 |
| 日报页面 | [/reports](https://smart-restaurant-lite.onrender.com/reports) | AI 生成的日语营业日报 |
| 菜单 API | [/api/menus](https://smart-restaurant-lite.onrender.com/api/menus) | 菜单列表 JSON |
| 订单 API | [/api/orders](https://smart-restaurant-lite.onrender.com/api/orders) | 订单列表 JSON |

> 💡 直接点击上方链接即可体验，无需登录。

### 功能

- **POS 界面**：菜单展示、分类过滤、购物车、桌位选择、订单提交
- **注文状況看板**：Kanban 风格 4 状态管理（待ち/調理中/提供済/会計済）
- **AI 营业日报**：基于 OpenRouter API（Claude 3 Haiku）自动生成日语日报
- **REST API**：8 个端点，覆盖菜单、订单、报表全流程

### 技术栈

| 层 | 技术 |
|---|------|
| Backend | PHP 8.4, Laravel 13 |
| Frontend | React 18, Tailwind CSS 4, Vite 8 |
| Database | PostgreSQL (Render) / MySQL 8 (Docker) |
| AI | OpenRouter API (Claude 3 Haiku) |
| Infrastructure | Docker |

### 快速开始

```bash
# 1. 启动 Docker
docker-compose up -d

# 2. 安装 PHP 依赖
docker exec srl-app composer install

# 3. 配置 .env（数据库和 OpenRouter API Key）
cp .env.example .env
docker exec srl-app php artisan key:generate

# 4. 数据库迁移 + 填充
docker exec srl-app php artisan migrate:fresh --seed

# 5. 构建前端
npm install
npm run build

# 6. 生成 AI 日报
docker exec srl-app php artisan report:daily
```

访问：
- POS 界面：http://localhost:8000/pos
- 日报页面：http://localhost:8000/reports
- API：http://localhost:8000/api/menus

### API 端点

| 方法 | 路径 | 说明 |
|------|------|------|
| GET | /api/menus | 菜单列表 + 分类 |
| GET | /api/orders | 订单列表（支持 ?status=&date=） |
| POST | /api/orders | 创建订单 |
| GET | /api/orders/{id} | 订单详情 |
| PATCH | /api/orders/{id}/status | 更新订单状态 |
| GET | /api/reports | 日报列表 |
| GET | /api/reports/latest | 最新日报 |
| POST | /api/reports/generate | 生成日报 |

### AI 辅助开发叙事

**核心卖点**：我没有手写一行 PHP 或 React，用 AI 工具全栈开发了可运行的系统。

关键文档：
- [AI 使用日志](docs/ai-usage-log.md) — AI 加速量化、出错故事
- [Prompt 迭代](docs/prompt-iterations.md) — AI 日报 Prompt 工程

### 亮点

1. **全栈能力**：前后端 + 数据库 + AI 集成，一个人完成
2. **AI 工具链**：Claude Code 作为主要开发工具，Prompt 迭代能力
3. **日语输出**：AI 日报直接生成日语，N2 水平验证

---

<!-- ============================================================ -->
<!-- 🇺🇸 ENGLISH VERSION                                         -->
<!-- ============================================================ -->

<a id="en"></a>
## 🇺🇸 English Version

AI-powered restaurant POS system. Built with PHP 8.4 + Laravel 13 + React 18 + PostgreSQL + OpenRouter API.

> Developed entirely with AI assistance (Claude Code). Approximately 85% of PHP/JS/React code was AI-generated. Development time compressed from an estimated 2–3 weeks to **1 day**.

### 🌐 Live Demo

**Public URL**: https://smart-restaurant-lite.onrender.com

| Page | URL | Description |
|------|-----|-------------|
| POS Interface | [/pos](https://smart-restaurant-lite.onrender.com/pos) | Ordering + cart + order submission |
| Reports | [/reports](https://smart-restaurant-lite.onrender.com/reports) | AI-generated daily business reports (Japanese) |
| Menus API | [/api/menus](https://smart-restaurant-lite.onrender.com/api/menus) | Menu list JSON |
| Orders API | [/api/orders](https://smart-restaurant-lite.onrender.com/api/orders) | Order list JSON |

> 💡 Click the links above to try it live — no login required.

### Features

- **POS Interface**: Menu display, category filtering, cart, table selection, order submission
- **Order Kanban Board**: 4-state pipeline management (pending → preparing → served → paid)
- **AI Daily Reports**: Auto-generated Japanese business reports via OpenRouter API (Claude 3 Haiku)
- **REST API**: 8 endpoints covering menus, orders, and reports

### Tech Stack

| Layer | Technology |
|---|------|
| Backend | PHP 8.4, Laravel 13 |
| Frontend | React 18, Tailwind CSS 4, Vite 8 |
| Database | PostgreSQL (Render) / MySQL 8 (Docker) |
| AI | OpenRouter API (Claude 3 Haiku) |
| Infrastructure | Docker |

### Quick Start

```bash
# 1. Start Docker
docker-compose up -d

# 2. Install PHP dependencies
docker exec srl-app composer install

# 3. Configure .env (database + OpenRouter API Key)
cp .env.example .env
docker exec srl-app php artisan key:generate

# 4. Migrate + seed database
docker exec srl-app php artisan migrate:fresh --seed

# 5. Build frontend
npm install
npm run build

# 6. Generate AI daily report
docker exec srl-app php artisan report:daily
```

Access:
- POS interface: http://localhost:8000/pos
- Reports page: http://localhost:8000/reports
- API: http://localhost:8000/api/menus

### API Endpoints

| Method | Path | Description |
|------|------|------|
| GET | /api/menus | Menu list + categories |
| GET | /api/orders | Order list (?status=&date= supported) |
| POST | /api/orders | Create order |
| GET | /api/orders/{id} | Order detail |
| PATCH | /api/orders/{id}/status | Update order status |
| GET | /api/reports | Report list |
| GET | /api/reports/latest | Latest report |
| POST | /api/reports/generate | Generate report |

### AI-Assisted Development Narrative

**Core pitch**: I didn't write a single line of PHP or React by hand — AI tools built a fully functional full-stack system.

Key documents:
- [AI Usage Log](docs/ai-usage-log.md) — Quantified AI acceleration, debugging stories
- [Prompt Iterations](docs/prompt-iterations.md) — AI report prompt engineering

### Highlights

1. **Full-stack capability**: FE + BE + Database + AI integration, solo-built
2. **AI toolchain**: Claude Code as primary development tool, prompt iteration skills
3. **Japanese output**: AI generates business reports in Japanese

---

<!-- ============================================================ -->
<!-- 🇯🇵 JAPANESE VERSION                                        -->
<!-- ============================================================ -->

<a id="ja"></a>
## 🇯🇵 日本語版

AI 搭載のレストラン POS システム。PHP 8.4 + Laravel 13 + React 18 + PostgreSQL + OpenRouter API で構築。

> 本プロジェクトは AI ツール（Claude Code）のみで開発しました。PHP/JS/React コードの約 85% が AI 生成です。開発期間を見積もり 2-3 週間から **1 日** に短縮しました。

### 🌐 ライブデモ

**公開 URL**：https://smart-restaurant-lite.onrender.com

| ページ | URL | 説明 |
|------|-----|------|
| POS 画面 | [/pos](https://smart-restaurant-lite.onrender.com/pos) | 注文 + カート + 注文送信 |
| 日報ページ | [/reports](https://smart-restaurant-lite.onrender.com/reports) | AI が生成する営業日報（日本語） |
| メニュー API | [/api/menus](https://smart-restaurant-lite.onrender.com/api/menus) | メニュー一覧 JSON |
| 注文 API | [/api/orders](https://smart-restaurant-lite.onrender.com/api/orders) | 注文一覧 JSON |

> 💡 上記リンクをクリックするだけで体験できます。ログイン不要です。

### 機能

- **POS 画面**：メニュー表示、カテゴリ絞り込み、カート、テーブル番号選択、注文送信
- **注文状況ボード**：Kanban 方式 4 ステータス管理（待ち/調理中/提供済/会計済）
- **AI 営業日報**：OpenRouter API（Claude 3 Haiku）による日本語日報の自動生成
- **REST API**：8 エンドポイント、メニュー・注文・レポートをカバー

### 技術スタック

| レイヤー | 技術 |
|---|------|
| バックエンド | PHP 8.4, Laravel 13 |
| フロントエンド | React 18, Tailwind CSS 4, Vite 8 |
| データベース | PostgreSQL（Render）/ MySQL 8（Docker） |
| AI | OpenRouter API（Claude 3 Haiku） |
| インフラ | Docker |

### クイックスタート

```bash
# 1. Docker 起動
docker-compose up -d

# 2. PHP 依存パッケージのインストール
docker exec srl-app composer install

# 3. .env 設定（データベース + OpenRouter API Key）
cp .env.example .env
docker exec srl-app php artisan key:generate

# 4. マイグレーション + シード
docker exec srl-app php artisan migrate:fresh --seed

# 5. フロントエンドのビルド
npm install
npm run build

# 6. AI 日報の生成
docker exec srl-app php artisan report:daily
```

アクセス：
- POS 画面：http://localhost:8000/pos
- 日報ページ：http://localhost:8000/reports
- API：http://localhost:8000/api/menus

### API エンドポイント

| メソッド | パス | 説明 |
|------|------|------|
| GET | /api/menus | メニュー一覧 + カテゴリ |
| GET | /api/orders | 注文一覧（?status=&date= 対応） |
| POST | /api/orders | 注文作成 |
| GET | /api/orders/{id} | 注文詳細 |
| PATCH | /api/orders/{id}/status | 注文ステータス更新 |
| GET | /api/reports | 日報一覧 |
| GET | /api/reports/latest | 最新日報 |
| POST | /api/reports/generate | 日報生成 |

### AI 支援開発について

**セールスポイント**：PHP や React のコードを一行も手書きせず、AI ツールだけでフルスタックシステムを開発しました。

参考ドキュメント：
- [AI 使用ログ](docs/ai-usage-log.md) — AI 加速器の定量分析、デバッグ事例
- [Prompt 反復](docs/prompt-iterations.md) — AI 日報用 Prompt エンジニアリング

### ポイント

1. **フルスタック能力**：フロントエンド + バックエンド + データベース + AI 統合
2. **AI ツールチェーン**：Claude Code を主要開発ツールとして使用、Prompt 改善能力
3. **日本語出力**：AI が日本語で営業日報を生成
