# LearnAI

Student productivity and AI-powered learning portal built with Laravel 13.

## Stack

- Laravel 13.8 / PHP 8.3
- SQLite (dev) → MySQL/PostgreSQL (prod)
- Vite + vanilla JS or Alpine.js (no heavy SPA framework)
- Tailwind CSS
- Queue: Laravel queues (database driver dev, Redis prod)

## Purpose

Students search topics → get AI-structured learning content → track their progress. Focus on productivity, self-tracking, and structured discovery.

## Dev Commands

```bash
composer run dev       # starts server + queue + pail + vite concurrently
composer run test      # php artisan test
php artisan migrate    # run migrations
```

## Conventions

- Feature-based folder structure inside `app/` (not layer-based)
- Models stay thin — business logic in Services or Actions
- API routes versioned under `api/v1/`
- Form Requests for all validation
- Jobs for any AI API calls (async, retriable)
- No facades in tests — bind through container

## Key Domain Concepts

- **Topic** — a subject a student searches/learns about
- **LearningSession** — a tracked study session on a topic
- **Progress** — aggregated student progress per topic
- **Section** — content unit within a topic (intro, explanation, examples, quiz)
