# Smart Restaurant POS 🍽️

AI 增强的餐饮 POS 系统。PHP 8.4 + Laravel 13 + React 18 + PostgreSQL + OpenRouter API。

本项目完全由 AI 工具（Claude Code）辅助开发，PHP/JS/React 代码约 85% 由 AI 生成，开发时间从估计的 2-3 周压缩至 1 天。

## 🌐 在线演示

**公网地址**：https://smart-restaurant-lite.onrender.com

| 页面 | URL | 说明 |
|------|-----|------|
| POS 界面 | [/pos](https://smart-restaurant-lite.onrender.com/pos) | 点餐 + 购物车 + 订单提交 |
| 日报页面 | [/reports](https://smart-restaurant-lite.onrender.com/reports) | AI 生成的日语营业日报 |
| 菜单 API | [/api/menus](https://smart-restaurant-lite.onrender.com/api/menus) | 菜单列表 JSON |
| 订单 API | [/api/orders](https://smart-restaurant-lite.onrender.com/api/orders) | 订单列表 JSON |

> 💡 直接点击上方链接即可体验，无需登录。

## 功能

- **POS 界面**：菜单展示、分类过滤、购物车、桌位选择、订单提交
- **注文状況看板**：Kanban 风格 4 状态管理（待ち/調理中/提供済/会計済）
- **AI 营业日报**：基于 OpenRouter API（Claude 3 Haiku）自动生成日语日报
- **REST API**：8 个端点，覆盖菜单、订单、报表全流程

## 技术栈

| 层 | 技术 |
|---|------|
| Backend | PHP 8.4, Laravel 13 |
| Frontend | React 18, Tailwind CSS 4, Vite 8 |
| Database | PostgreSQL (Render) / MySQL 8 (Docker) |
| AI | OpenRouter API (Claude 3 Haiku) |
| Infrastructure | Docker |

## 快速开始

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

## API 端点

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

## AI 辅助开发叙事

**核心卖点**：我没有手写一行 PHP 或 React，用 AI 工具全栈开发了可运行的系统。

关键文档：
- [AI 使用日志](docs/ai-usage-log.md) — AI 加速量化、出错故事
- [Prompt 迭代](docs/prompt-iterations.md) — AI 日报 Prompt 工程

## 面试亮点

1. **全栈能力**：前后端 + 数据库 + AI 集成，一个人完成
2. **AI 工具链**：Claude Code 作为主要开发工具，Prompt 迭代能力
3. **日语输出**：AI 日报直接生成日语，N2 水平验证
4. **CHIBIC 契合**：餐饮 POS 系统，与 CHIBIC 业务高度重叠
