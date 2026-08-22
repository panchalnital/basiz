## Basiz Assignment
# PHP / MySQL / JS Assignments


4. Start PHP's built-in server for a quick test (no Apache needed):
   ```
   cd assignments
   php -S localhost:8000
   ```
5. Open `http://127.0.0.1/index.php` in your browser — it links to all 6 tasks.

## Structure
```
assignments/
├── index.php                     # Menu linking all tasks
├── task1_student/                # Student CRUD (HTML/JS/PHP/MySQL + AJAX)
│   ├── schema.sql
│   ├── db.php
│   ├── index.php                 # Form + list (AJAX add & fetch)
│   ├── add_student.php           # AJAX insert handler (server-side validation)
│   └── get_students.php          # AJAX fetch handler (returns JSON)
├── task2_todo/
│   └── index.html                # Todo app, data kept in a JS array only
├── task3_string_replace/
│   └── index.php                 # @placeholder@ replace in a template string
├── task4_days_between/
│   └── index.php                 # Days between two dates, number + words
├── task5_html_replace/
│   └── index.php                 # HTML find & replace with dynamic pairs
└── task6_triplet_sum/
    └── index.php                 # Triplet-sum finder (two-pointer, O(n^2))
```

<!-- ## Notes on each task
1. **Student Details** — client-side validation mirrors server-side validation in `add_student.php`. Age is auto-calculated in JS from DOB and re-verified/recalculated on the server. Mobile must be exactly 10 numeric digits. Adding a student uses `XMLHttpRequest` (AJAX) to POST to `add_student.php`, then the list reloads via AJAX from `get_students.php` — no page refresh.
2. **Todo List** — all data (`todos` array) lives purely in JavaScript memory; add/edit/delete/complete all mutate that array, then re-render. Nothing is persisted (per the "no database" requirement — this also means no `localStorage`, since the task must not use any storage/database).
3. **Placeholder Replace** — `str_replace()` with an array of `@Key@` search terms mapped to values; UI lets you edit the template and each value.
4. **Days Between Dates** — uses PHP `DateTime::diff()` for the day count and a recursive `numberToWords()` function (handles thousands/millions) for the word form. UI accepts `dd/mm/yyyy` dates.
5. **HTML Find & Replace** — dynamic list of find/replace pairs (add/remove rows) applied with `str_replace()` so only the text is swapped and surrounding HTML tags are untouched. Shows both the raw replaced HTML and a rendered preview.
6. **Triplet Sum** — sorts a copy of the array, then uses the classic two-pointer technique per outer element for O(n²) time. Returns the triplet or `false`. UI accepts a comma-separated array and a target value. -->
