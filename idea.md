# LearnAI

LearnAI is a student productivity workspace that combines topic-based learning, study planning, notes, flashcards, focus tracking, journaling, and progress visibility in one Laravel app.

## Current Product Shape

- Landing page for first-time visitors
- Authenticated dashboard with study metrics and quick access
- Topic management with structured sections
- Markdown-style notes with tags and topic linking
- Flashcard decks and card review flow
- Study planner with goals and scheduled sessions
- Focus timer with manual logging and history
- Resource library with favourites and filtering
- Journal and progress tracking
- Bookmarks and recent activity surfaces

## What Already Exists In Code

- Laravel 13 app with Blade views and Tailwind CSS
- SQLite-ready local setup with migrations for the main learning modules
- User-owned data model for topics, sessions, goals, notes, flashcards, resources, journal entries, and focus sessions
- Dashboard aggregation for weekly study time, focus minutes, active goals, and recent activity
- CRUD flows for most student tools already wired through web routes

## Main Gaps Identified

- Branding is inconsistent between `LearnAI` and `StudentLMS`
- Landing page feels generic and undersells the product
- Shared UI system is functional but visually flat
- Project documentation still includes the default Laravel README
- Some text rendering shows encoding artifacts in the UI copy
- A few analytics flows need later review, especially auto-starting sessions on topic view

## Direction For This Update

- Unify the product name under `LearnAI`
- Improve the visual system so cards, headers, and actions feel consistent
- Redesign the landing page with stronger hierarchy, clearer messaging, and better trust signals
- Polish the most visible authenticated screens so the product feels cohesive after sign-in
- Refresh project docs to describe the actual app instead of the framework starter

## Suggested Next Product Steps

1. Add AI-assisted topic generation and study recommendations through queued jobs.
2. Introduce assignments, exams, habits, todos, and achievements into the actual UI since supporting models already exist.
3. Replace passive dashboard stats with actionable reminders and streaks.
4. Revisit learning-session tracking so analytics reflect intentional study, not just page visits.
5. Add tests around the planner, focus logging, and dashboard metrics.
