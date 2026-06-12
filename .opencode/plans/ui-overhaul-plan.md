# UI Overhaul Plan — StudentLMS

## Scope
50+ files across 7 phases. Fix all UI breakdowns, apply consistent design system, add missing features to sidebar and dashboard.

---

## Phase 1: CSS Fixes (`resources/css/app.css`)
- Add 6 missing badge classes: `badge-pending`, `badge-submitted`, `badge-graded`, `badge-low`, `badge-medium`, `badge-high`
- Add `dark:` variants to ALL component classes (card, input, btn-*, sidebar-*, badge-*, glass-panel, app-sidebar, app-topbar, empty-state, link, section-title, prose-custom, eyebrow)
- Fix `tailwind.config.js` font family to match Manrope (loaded in CSS)

## Phase 2: Nested Form Fixes
- `flashcards/show.blade.php` (lines 88-91): Split update deck and delete deck forms — they're nested
- `todos/index.blade.php`: Check for any nested `<form>` issues

## Phase 3: Branding & Navigation
- Replace "LearnAI" → "StudentLSM" in:
  - `layouts/app.blade.php` (lines 7, 29)
  - `layouts/guest.blade.php` (lines 7, 14)
  - `layouts/navigation.blade.php` (line 2)
  - `welcome.blade.php` (lines 6, 16, 37, 135)
- Restore sidebar links for: Assignments, Exams, Grades, To-Do, Achievements, Habits
- Reorganize sidebar sections: Overview → Learning → Academic → Planning → Reflection → Lifestyle → Resources

## Phase 4: Color Migration (~28 files)
### 4a. Auth Views (4 files)
- `auth/login.blade.php`: Checkbox `text-indigo-600 focus:ring-indigo-500` → `text-cyan-600 focus:ring-cyan-500`; `text-gray-*` → `text-slate-*`
- `auth/register.blade.php`: Same pattern
- `auth/forgot-password.blade.php`: `text-gray-500` → `text-slate-500`
- `auth/confirm-password.blade.php`: Same

### 4b. New Feature Views (7 files)
- `assignments/index`, `assignments/create`, `assignments/show`, `assignments/edit`: Replace all `text-gray-900 dark:text-gray-100` → `text-slate-900 dark:text-slate-100`, `text-gray-500 dark:text-gray-400` → `text-slate-500 dark:text-slate-400`, etc.
- `exams/index`, `exams/show`, `exams/create`, `exams/edit`: Replace indigo → cyan badges and text
- `grades/index`, `grades/create`, `grades/edit`: Replace indigo checkbox → cyan, gray → slate
- `habits/index`, `habits/create`, `habits/edit`, `habits/history`: Replace indigo → cyan, gray → slate
- `achievements/index`: Replace purple → cyan, gray → slate
- `todos/index`: Replace indigo → cyan, gray → slate

### 4c. Existing Feature Views (8 files)
- `notes/create`, `notes/edit`: Checkbox indigo → cyan, `rounded-lg` → `rounded-2xl`, `border-gray-300` → `border-slate-200`
- `flashcards/index`, `flashcards/review`: Gray → slate, indigo → cyan
- `resources/index`: Purple → cyan, indigo → cyan, gray → slate
- `journal/index`: Indigo → cyan, gray → slate
- `focus/history`: Gray → slate
- `progress/index`: Indigo → cyan, gray → slate

### 4d. Profile Views (3 files)
- `profile/edit`: Gray → slate
- `profile/partials/update-profile-information-form`: Gray → slate
- `profile/partials/update-password-form`: Gray → slate
- `profile/partials/delete-user-form`: Gray → slate

### 4e. Topic Views (2 files)
- `topics/show`: Indigo → cyan, gray → slate
- `topics/create`, `topics/edit`: Gray → slate

### 4f. Components (5 files)
- `components/application-logo.blade.php`: Indigo → cyan
- `components/dropdown.blade.php`: Gray → slate, `rounded-xl` → `rounded-2xl`
- `components/dropdown-link.blade.php`: Gray → slate
- `components/input-label.blade.php`: Gray → slate
- `components/modal.blade.php`: Gray → slate, `rounded-lg` → `rounded-2xl`

## Phase 5: Rounding Consistency
- `components/modal.blade.php`: `rounded-lg` → `rounded-2xl`
- `components/dropdown.blade.php`: `rounded-xl` → `rounded-2xl`
- `student/flashcards/show.blade.php`: `rounded-lg` → `rounded-2xl`
- `student/notes/create.blade.php`: `rounded-lg` → `rounded-2xl`

## Phase 6: Dashboard Enhancement
- Restore 6-column stats row (Topics, Notes, Decks, Assignments, Study, CGPA)
- Add gateway cards for all 12 features
- Add widget sections: Due Assignments, Upcoming Exams, Pending Tasks, Today's Habits, Bookmarks
- Update DashboardController to pass new feature data

## Phase 7: Welcome Page & Polish
- Rewrite copy: Replace "LearnAI" → "StudentLMS", update feature descriptions to cover all 12 tools
- Add sections for Academic tracking, Habits, Achievements
- Remove AI references
- Fix purple outliers in achievements/resources views

---

## File Count Summary
| Phase | Files Modified |
|-------|---------------|
| 1. CSS | 2 (`app.css`, `tailwind.config.js`) |
| 2. Form fixes | 2 |
| 3. Branding & Nav | 5 |
| 4. Color migration | ~28 |
| 5. Rounding | 4 |
| 6. Dashboard | 2 (view + controller) |
| 7. Welcome & Polish | 3 |
| **Total** | **~46 files** |
