# LearnAI — Agentic Development Kickstart Prompt

Use this prompt verbatim when starting development with an agentic AI (Cursor, Windsurf, Claude Code, etc.).

---

## Prompt

You are building **LearnAI**, a student-focused AI-powered learning portal built on **Laravel 13.8 / PHP 8.3**. The project skeleton already exists at the current working directory. Your job is to implement the foundational layer so further features can be added incrementally.

### Project Vision

Students visit the portal, search for any topic they want to learn, and the app uses an AI API to generate structured learning content for that topic. Students can track which topics they've studied, how much time they've spent, and monitor their own learning progress over time. The portal is productivity-first: it helps students stay accountable, not just consume content.

### Tech Constraints

- **Laravel 13.8**, PHP 8.3 — use modern PHP syntax (readonly, enums, match, fibers where applicable)
- **Database**: SQLite for local dev (already configured). Design migrations to be MySQL/PostgreSQL compatible.
- **Frontend**: Tailwind CSS + Alpine.js. No React/Vue. Blade templates. Vite already configured.
- **Auth**: Use Laravel's built-in auth scaffolding (Breeze preferred). Students must be authenticated to track progress.
- **AI calls**: All calls to external AI APIs must go through **Laravel Jobs** (queued, retriable). Never call AI synchronously in a controller.
- **Validation**: All input goes through **Form Request** classes. No inline `$request->validate()` in controllers.
- **Architecture**: Feature-based inside `app/` — group by domain (e.g., `app/Topics/`, `app/Progress/`), not by layer. Keep Models thin; put logic in Services or Actions.

### Domain Model — Implement These First

Build migrations, models, and relationships for:

1. **users** (already exists via Laravel default)
   - Add: `role` (enum: `student`, `admin`), default `student`

2. **topics**
   - `id`, `slug` (unique), `title`, `description` (nullable), `created_by` (FK users), `timestamps`

3. **topic_sections**
   - `id`, `topic_id` (FK), `type` (enum: `overview`, `explanation`, `examples`, `quiz`, `summary`), `content` (longText), `order` (int), `timestamps`
   - Sections are AI-generated chunks of a topic

4. **learning_sessions**
   - `id`, `user_id` (FK), `topic_id` (FK), `started_at`, `ended_at` (nullable), `duration_seconds` (nullable), `timestamps`
   - Tracks each time a student studies a topic

5. **topic_progress**
   - `id`, `user_id` (FK), `topic_id` (FK), `status` (enum: `not_started`, `in_progress`, `completed`), `completion_percentage` (int 0–100), `last_studied_at`, `timestamps`
   - Unique on `(user_id, topic_id)`

6. **bookmarks**
   - `id`, `user_id` (FK), `topic_id` (FK), `timestamps`
   - Unique on `(user_id, topic_id)`

### Core Features — Implement in This Order

#### 1. Auth
- Install Laravel Breeze (Blade + Alpine stack): `composer require laravel/breeze --dev && php artisan breeze:install`
- Students register/login. Redirect to dashboard after login.

#### 2. Topic Search & Creation
- `GET /topics/search?q=` — full-text search on `title` and `description` columns
- If no topic found for the query, show option "Generate topic with AI"
- `POST /topics/generate` — dispatches `GenerateTopicContentJob` (see AI section below), returns topic slug, redirects to topic show page with a loading state

#### 3. Topic Show Page (`GET /topics/{slug}`)
- Shows all `topic_sections` in order
- If sections are still being generated (job pending), show spinner/polling via Alpine.js (`setInterval` polling `GET /topics/{slug}/status` which returns JSON `{ready: bool}`)
- Start a `LearningSession` when student opens this page (create record with `started_at = now()`)
- End the session via `POST /topics/{slug}/session/end` (called by `beforeunload` JS event + a manual "Done studying" button)

#### 4. Student Dashboard (`GET /dashboard`)
- Authenticated students see:
  - Recent topics studied (last 5 `learning_sessions` with topic)
  - Total study time this week (sum of `duration_seconds` from `learning_sessions` where `started_at >= startOfWeek`)
  - Topics in progress vs completed counts
  - Bookmarked topics list

#### 5. Progress Tracking
- After each `LearningSession` ends, dispatch `UpdateTopicProgressJob` which recalculates `topic_progress.completion_percentage` and `status` for that user+topic
- Percentage logic: `(sections viewed / total sections) * 100` — track viewed sections via a `section_views` table (`user_id`, `section_id`, `timestamps`, unique)

#### 6. Bookmarks
- `POST /bookmarks/{topic}` — toggle (create if not exists, delete if exists)
- Returns JSON `{bookmarked: bool}` for Alpine.js to update UI without reload

### AI Integration

#### Job: `GenerateTopicContentJob`
- Accepts: `topic_id`, `user_id`
- Calls external AI API (use config `services.ai.key` and `services.ai.endpoint` — leave as env vars `AI_API_KEY`, `AI_ENDPOINT`)
- Prompt structure to send to AI:
  ```
  Generate structured educational content for the topic: "{topic_title}"
  
  Return JSON with this exact structure:
  {
    "description": "Brief 2-sentence overview",
    "sections": [
      {"type": "overview", "content": "..."},
      {"type": "explanation", "content": "..."},
      {"type": "examples", "content": "..."},
      {"type": "quiz", "content": "..."},
      {"type": "summary", "content": "..."}
    ]
  }
  ```
- On success: create `topic_sections` records, update `topics.description`
- On failure: retry up to 3 times (exponential backoff), then mark topic with `generation_failed` status (add `generation_status` enum column to `topics`: `pending`, `completed`, `failed`)
- Make the AI provider swappable — put the HTTP call behind a `AiContentService` interface with a concrete implementation, bound in a ServiceProvider

### Routes Structure

```
// Public
GET  /                    → welcome/landing page
GET  /topics/search       → search topics
GET  /topics/{slug}       → show topic + sections

// Auth required
GET  /dashboard           → student dashboard
POST /topics/generate     → trigger AI generation
GET  /topics/{slug}/status → JSON status for polling
POST /topics/{slug}/session/end → end learning session
POST /bookmarks/{topic}   → toggle bookmark
GET  /progress            → full progress overview page
```

### UI/UX Notes

- Use Tailwind. Keep it clean and minimal — students need focus, not clutter.
- Dashboard should feel like a productivity tool (think Notion/Linear aesthetic: whitespace, clear hierarchy).
- Topic sections render Markdown — use a PHP Markdown parser (`league/commonmark`) to render `content` field.
- Mobile-responsive from the start.

### What NOT to Do

- Do not add admin panels, subscription billing, social features, or anything not listed above — scope creep kills MVPs.
- Do not make AI calls synchronously — always via Jobs.
- Do not use Laravel Livewire — Alpine.js + Blade only.
- Do not write inline styles — Tailwind classes only.
- Do not add comments explaining what code does — only add comments for non-obvious WHY.

### First Steps (Do These In Order)

1. `composer require laravel/breeze league/commonmark --dev` (breeze dev-only)
2. `php artisan breeze:install` (select Blade + Alpine)
3. Write all migrations in order listed above
4. `php artisan migrate`
5. Create Models with relationships
6. Create `GenerateTopicContentJob` stub (leave AI HTTP call as TODO with interface)
7. Create `AiContentService` interface + `HttpAiContentService` implementation
8. Implement routes + controllers (thin — delegate to Services/Actions)
9. Build Blade views with Tailwind
10. Wire up Alpine.js for polling and bookmark toggle

---

*This prompt is living — update AGENT_KICKSTART_PROMPT.md as new features are scoped.*
