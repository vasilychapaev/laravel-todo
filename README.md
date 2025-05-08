# Todo App

A simple and elegant Todo application built with Laravel and Breeze.

## Features

- User authentication and authorization
- CRUD operations for todos
- Todo status management (pending, in progress, completed)
- Due date tracking
- Filtering and sorting
- Search functionality
- Pagination
- Soft deletes
- Responsive design

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js and NPM
- SQLite (or MySQL/PostgreSQL)

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd todo-app
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install NPM dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure database in `.env`:
```bash
DB_CONNECTION=sqlite
```

7. Run migrations:
```bash
php artisan migrate
```

8. Build assets:
```bash
npm run build
```

9. Start the development server:
```bash
php artisan serve
```

## Development

1. Start Vite development server:
```bash
npm run dev
```

2. Run tests:
```bash
php artisan test
```

## API Documentation

### Authentication

All API endpoints require authentication using Laravel Sanctum.

### Endpoints

#### List Todos
```
GET /api/todos
```

Query Parameters:
- `status`: Filter by status (pending, in_progress, completed)
- `date_filter`: Filter by date (today, overdue, upcoming)
- `search`: Search in title and description
- `per_page`: Number of items per page (default: 10)

#### Create Todo
```
POST /api/todos
```

Request Body:
```json
{
    "title": "Todo title",
    "description": "Todo description",
    "due_date": "2024-03-22 12:00:00",
    "status": "pending"
}
```

#### Update Todo
```
PUT /api/todos/{id}
```

Request Body:
```json
{
    "title": "Updated title",
    "description": "Updated description",
    "due_date": "2024-03-22 12:00:00",
    "status": "in_progress"
}
```

#### Delete Todo
```
DELETE /api/todos/{id}
```

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License.


:) 