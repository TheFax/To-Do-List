# To-Do List

A simple, fast, and user-friendly web-based To-Do List application built with PHP, SQLite, and Bootstrap. This project allows you to manage your daily tasks efficiently with a clean interface and intuitive controls.

## Eisenhower Matrix: Smarter Task Management

**This To-Do List is based on the Eisenhower Matrix (also known as the Eisenhower Decision Matrix), a proven productivity tool for prioritizing tasks.**

### What is the Eisenhower Matrix?
The Eisenhower Matrix is a time management method that helps you decide on and prioritize tasks by urgency and importance, sorting out less urgent and important tasks which you should either delegate or not do at all. It divides tasks into four categories:

- **Urgent and Important (Do):** Tasks you should do immediately.
- **Important, Not Urgent (Plan):** Tasks you should schedule to do later.
- **Urgent, Not Important (Delegate):** Tasks you should delegate if possible.
- **Not Urgent, Not Important (Eliminate):** Tasks you should consider removing, or extremely low priority.

This approach is named after Dwight D. Eisenhower, the 34th President of the United States, who was known for his productivity and decision-making skills.

### Why does this improve your To-Do List?
By categorizing your tasks using the Eisenhower Matrix, you:
- Focus on what truly matters, not just what seems urgent.
- Avoid wasting time on unimportant activities.
- Gain clarity and control over your priorities.
- Make your to-do list actionable and less overwhelming.

**This app lets you assign each task to one of these categories, making it easy to visually organize and reorder your day according to what really counts.**

## Features

- **Add, edit, and delete tasks** with title, description, and category.
- **Drag & drop** to reorder tasks visually; the new order is saved automatically.
- **Mark tasks as done** with a single click.
- **Responsive design**: works well on desktop and mobile devices.
- **AJAX-powered**: all main actions (add, delete, reorder) are instant, without page reloads.
- **Categories**: Organize tasks by importance and urgency.

## Installation

1. **Requirements:**
   - PHP 7.4 or higher
   - SQLite (enabled by default in most PHP installations)
   - A web server (e.g., Apache, XAMPP, LAMP Docker container or similar)

2. **Setup:**
   - Download or clone this repository into your web server's document root (e.g., `htdocs` for XAMPP) or in a subdirectory.
   - Make sure the web server has write permissions to the project folder (for SQLite database file creation and logging).
   - No database setup is required: the SQLite database will be created automatically on first use. You can delete it if you want, and it will be re-created (obviously you will loose your data).

3. **Run:**
   - Open your browser and navigate to the appropriate URL.
   - Start adding and managing your tasks!

## How It Works

- **Adding a Task:** Fill in the title, description, and select a category. Click "Add Task". The task appears instantly in the list. Description is not mandatory.
- **Editing a Task:** Click the pencil icon next to a task to edit its details.
- **Deleting a Task:** Click the trash icon and confirm. The task is removed immediately (it will be removed from the database).
- **Marking as Done:** Click the checkmark icon to mark a task as completed (it will remain into the database, marked as done).
- **Reordering Tasks:** Drag a row up or down. The new order is saved automatically.
- **Categories:**
  - Urgent and important
  - Important
  - Urgent
  - Not important

## Security & Error Handling
- All user input is sanitized.
- Errors are logged to a file (`php_errors.log`) and not shown to users in production.
- No user authentication is included: this is a simple, single-user tool.

## Disclaimer
This project is provided as-is, with no warranty of any kind. Use it at your own risk. It is intended for educational and personal productivity purposes.

## License
No specific license. You are free to use, modify, and share this code for personal or educational use.
