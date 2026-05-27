# AI 使用日志 — Smart Restaurant POS

> 项目：AI 增强的餐饮 POS 系统（Laravel 13 + React 18 + OpenRouter）
> 时间：2026-05-26
> 作者：朱延俊

---

## 总体数据

| 指标 | 数值 |
|------|------|
| 项目总耗时 | ~8 小时（1 天） |
| 估计手写时间 | 2-3 周 |
| AI 加速比 | ~10x |
| AI 生成代码占比 | ~85% |
| 使用的主要 AI 工具 | Claude Code（Cursor 插件） |

---

## 各模块 AI 使用情况

### 1. 项目初始化（100% AI）
- Dockerfile、docker-compose.yml、.env 配置
- Laravel 13 + PHP 8.4 + MySQL 8 环境搭建
- **角色**：我提供方向 → AI 生成配置 → 我调试路径问题

### 2. 数据库设计（95% AI）
- 4 张表的 Migration（menus, orders, order_items, daily_reports）
- Model 定义（Menu, Order, OrderItem, DailyReport）
- Seeder（20 道菜单 + 222 条订单 + 542 条订单明细）
- **我做的**：Review 并修复 `STR_PAD_LEFT`  typo，确认 `onDelete('cascade')`
- **AI 常犯的错**：忘记 cascade 外键约束（已修复）

### 3. REST API（90% AI）
- MenuController、OrderController、DailyReportController
- API 路由设计（8 个端点）
- **我做的**：设计验证规则、确认事务逻辑
- **关键决策**：Order 创建使用 DB::transaction 保证数据一致性

### 4. 前端 POS 界面（90% AI）
- PosApp.jsx（React 组件）
- 菜单网格、分类过滤、购物车、桌位选择、订单提交
- 注文状況看板（4 状态 Kanban：待ち/調理中/提供済/会計済）
- **踩坑**：Vite 8 + rolldown 不支持 .js 文件中的 JSX，需改为 .jsx

### 5. AI 日报功能（85% AI）
- `report:daily` Artisan 命令
- OpenRouter API 集成（Claude 3 Haiku）
- Prompt 模板设计
- 日报前端页面（ReportsApp.jsx）
- **关键亮点**：成功生成日语营业日报

### 6. 前端构建（70% AI）
- Vite 配置、React 插件集成
- Blade 模板与 Vite 资源引用
- **踩坑**：@vite() 指令需要与 manifest.json 的 key 匹配（.jsx 而非 .js）

---

## AI 出错记录

### 错误 1：Vite 8 JSX 解析失败
- **现象**：`Unexpected JSX expression` 构建错误
- **原因**：Vite 8 使用 rolldown 作为打包器，默认不解析 .js 文件中的 JSX
- **解决**：将入口文件从 `.js` 改为 `.jsx`，并在 vite.config.js 和 Blade 中同步修改
- **教训**：新工具版本可能有 breaking changes，需要查文档

### 错误 2：Docker 路径转换问题
- **现象**：Git Bash 将 `/var/www/html` 转换为 `D:/Program Files/Git/var/www/html`
- **原因**：Git Bash 的 POSIX 路径转换
- **解决**：使用 `docker exec container bash -c 'command'` 或在 PowerShell 中执行

### 错误 3：Order Seeder Typo
- **现象**：`STR_PAD_STR_PAD_LEFT` 常量未定义
- **原因**：AI 生成的代码中有重复的 `STR_PAD_` 前缀
- **解决**：手动修正为 `STR_PAD_LEFT`

---

## 面试故事

**Q：AI 工具具体怎么用？**
> 我用 Claude Code（Cursor 插件）生成所有 PHP 和 React 代码。我的角色是：理解业务需求 → 设计 Prompt → 审查 AI 代码 → 修复 bug → 集成测试。AI 写了约 85% 的代码，我负责架构决策和质量把关。

**Q：如果 AI 出错怎么办？**
> 两个典型例子：1) Vite 8 的 JSX 解析问题 — AI 给出的配置在 Vite 7 能用，但 Vite 8 换了打包器。我通过阅读错误信息和查文档解决。2) AI 生成的 Seeder 有个 typo，`STR_PAD_STR_PAD_LEFT` 这种重复前缀。我在代码 review 时发现并修正。
