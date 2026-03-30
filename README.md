# 🚀 PHP Laravel 12 - Eloquent Status Enum Task Manager

This project demonstrates how to use **Laravel 12 Enums with Eloquent Model Casting** to manage task statuses in a clean and maintainable way.

The application is a **Task Management Dashboard** where users can:

* Create tasks
* Update task status dynamically
* Delete tasks
* Manage statuses using **PHP Enums**

The UI is built using **Tailwind CSS** to provide a modern dashboard experience.

---

# ✨ Features

* ✅ Task Creation
* ✅ Task Status Management
* ✅ Status Update with Dropdown
* ✅ Delete Tasks
* ✅ PHP Enum Integration
* ✅ Eloquent Enum Casting
* ✅ Clean Tailwind Dashboard
* ✅ Laravel 12 Compatible
* ✅ Modern UI/UX

---

# 🛠 Tech Stack

| Technology | Description                  |
| ---------- | ---------------------------- |
| Framework  | Laravel 12                   |
| Language   | PHP 8.2+                     |
| Database   | MySQL / SQLite               |
| Frontend   | Blade                        |
| Styling    | Tailwind CSS                 |
| Concept    | PHP Enums + Eloquent Casting |

---

# 📦 Project Installation

Follow these steps to run the project locally.

---

# 1️⃣ Clone the Repository

```bash
composer create-project laravel/laravel PHP_Laravel12_Eloquent_Status
cd PHP_Laravel12_Eloquent_Status
```

---

# 2️⃣ Install Dependencies

```bash
composer install
```

---

# 3️⃣ Setup Environment

```bash
cp .env.example .env
```

---

# 4️⃣ Configure Database

Open `.env` file and set your database credentials.

Example:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

---

# 5️⃣ Run Migration

```bash
php artisan migrate
```

This will create the **tasks table**.

---

# 6️⃣ Run Application

```bash
php artisan serve
```

Now open your browser:

```
http://127.0.0.1:8000
```

---

# 📂 Project Structure

```
app
 ├── Enums
 │    └── TaskStatus.php
 │
 ├── Models
 │    └── Task.php
 │
 ├── Http
 │    └── Controllers
 │         └── TaskController.php

database
 └── migrations
      └── create_tasks_table.php

resources
 └── views
      └── tasks
           └── index.blade.php

routes
 └── web.php
```

---

# 🧠 Core Concepts Used

## 1️⃣ PHP Enum

Enums define fixed values for task status.

File:

```
app/Enums/TaskStatus.php
```

```php
enum TaskStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
}
```

---

# 2️⃣ Eloquent Enum Casting

Laravel automatically converts database values into **Enum objects**.

File:

```
app/Models/Task.php
```

```php
protected $casts = [
    'status' => TaskStatus::class
];
```

This means:

Database value
`pending`

Becomes

```
TaskStatus::PENDING
```

inside Laravel.

---

# 3️⃣ Task CRUD Controller

File:

```
app/Http/Controllers/TaskController.php
```

Controller handles:

* Listing tasks
* Creating tasks
* Updating status
* Deleting tasks

---

# 4️⃣ Routes

File:

```
routes/web.php
```

```php
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
```

---

# 🖥️ Dashboard UI

The dashboard includes:

### Create Task Form

Users can quickly create tasks.

### Task Table

Displays all tasks with:

* Title
* Status Dropdown
* Delete Button

### Status Colors

| Status      | Color |
| ----------- | ----- |
| Pending     | Amber |
| In Progress | Blue  |
| Completed   | Green |

---

# 📸 UI Preview

Dashboard contains:

* Task creation form
* Status dropdown selector
* Delete action button
* Responsive table layout

---

# 🔥 Why Use Enums?

Enums make your code:

* Safer
* More readable
* Less error-prone

Instead of using strings like:

```
"pending"
"in_progress"
"completed"
```

You use:

```
TaskStatus::PENDING
TaskStatus::IN_PROGRESS
TaskStatus::COMPLETED
```

---

# 🚀 Future Improvements

Possible features to add:

* Task Due Dates
* Task Priority
* User Authentication
* Task Assignments
* REST API version
* Pagination
* Search tasks
* Export to Excel

---

# Output
<img width="761" height="368" alt="image" src="https://github.com/user-attachments/assets/3ae20654-dc89-45f9-8a9f-ef2d6dc3cd04" />

---

# ⭐ Support

If you like this project, please **give it a star on GitHub** ⭐
