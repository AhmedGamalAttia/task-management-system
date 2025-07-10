# Task Management System API Setup Guide

## Prerequisites

- PHP 8.1+
- Composer 2.x
- MySQL 8.0+
- Git
- Postman (for testing)

## Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone <your-repository-url>
   ```

2. **Navigate to the project folder**
   ```bash
   cd task-management-system
   ```

3. **Install Dependencies**
   ```bash
   composer install
   ```

4. **Configure Environment**
   - Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```

   - Update `.env` with database credentials:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=task_management
     DB_USERNAME=root
     DB_PASSWORD=
     ```

   - Generate application key:
     ```bash
     php artisan key:generate
     ```

5. **Set Up Database**
   - Create MySQL database:
     ```sql
     CREATE DATABASE task_management;
     ```

   - Run migrations:
     ```bash
     php artisan migrate --force
     ```

   - Seed database:
     ```bash
     php artisan db:seed
     ```

6. **Run the Application**
   ```bash
   php artisan serve
   ```

7. **Access the API**

   Visit: [http://localhost:8000/api](http://localhost:8000/api)

8. **Test with Postman**

   - Import the Postman collection from the repository.
   - Set `base_url` to `http://localhost:8000/api/`.
   - Login using:
     ```json
     {
       "email": "manager@example.com",
       "password": "password"
     }
     ```
     or
     ```json
     {
       "email": "user@example.com",
       "password": "password"
     }
     ```

   - Use the token in the Authorization header as:
     ```
     Authorization: Bearer {{token}}
     ```

   - **Endpoints:**

     - `GET /api/tasks`
     - `POST /api/tasks`
     - `PUT /api/tasks/{id}`
     - `DELETE /api/tasks/{id}`
     - `PATCH /api/tasks/{id}/status`
     - `POST /api/tasks/{id}/dependencies`

## Troubleshooting

- **Database Issues:**  
  Verify `.env` database settings and ensure MySQL is running.

- **Authentication Errors:**  
  Make sure Laravel Sanctum is installed and `auth:sanctum` middleware is applied correctly.

- **Role Errors:**  
  Check user roles (`manager`, `user`) in the `users` table.
# task-management-system
