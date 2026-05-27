# Smart Restaurant Lite — Claude Code Project Guide

## Project Overview
A lightweight restaurant POS SaaS built with Laravel 10 + MySQL 8 + React 18.
Target: CHIBIC SYSTEM internship application (generative AI engineer role).

## Tech Stack
- Backend: PHP 8.2 + Laravel 10
- Database: MySQL 8
- Frontend: React 18 + Tailwind CSS (via Vite)
- AI: OpenRouter API (LLM calls for daily reports)
- Infrastructure: Docker Compose

## Domain Vocabulary (Japanese restaurant)
- 注文 (order), メニュー (menu), 売上 (revenue)
- 営業日報 (daily business report)
- シフト (shift schedule)
- POSレジ (POS register)
- 在庫 (inventory)

## Coding Standards
- Follow Laravel PSR-12 coding standards
- Japanese comments for domain logic, English for technical comments
- All API responses use Laravel API Resources
- All database queries use Eloquent ORM (no raw SQL in controllers)
- All AI-generated code must be reviewed before committing

## AI Usage Rules
- Log every AI-generated prompt in docs/ai-usage-log.md
- Track time saved vs manual development
- Record AI errors and fixes (become interview war stories)

## Database Schema
- menus: id, name_jp, name_en, price, category, is_active
- orders: id, table_number, status (pending/preparing/served/paid), total_amount
- order_items: id, order_id, menu_id, quantity, subtotal
- daily_reports: id, report_date, total_revenue, order_count, avg_order_value, top_items_json, ai_summary_jp, raw_json

## Key Features to Implement
1. POS Order CRUD (menus, orders, order_items)
2. AI Daily Report Generator (Artisan command + OpenRouter API)
3. Natural Language Query API (/api/ask endpoint)
