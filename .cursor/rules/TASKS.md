---
description:
globs:
alwaysApply: false
---

# Todo App Development Tasks

## 1. Project Setup
- [x] Create new Laravel project
- [x] Install and configure Breeze
- [x] Set up SQLite database
- [x] Configure environment variables
- [x] Run initial migrations

## 2. Database Structure
- [x] Create Todo model with migration
  - title (string)
  - description (text, nullable)
  - due_date (datetime, nullable)
  - status (enum: pending, in_progress, completed)
  - user_id (foreign key)
- [x] Create necessary indexes
- [x] Set up model relationships (User -> Todo)

## 3. Authentication & Authorization
- [x] Configure Breeze authentication
- [x] Set up user registration
- [x] Implement login/logout functionality
- [x] Add authorization policies for Todo CRUD operations

## 4. Todo CRUD Operations
- [x] Create TodoController
- [x] Implement index method (list all todos)
- [x] Implement create method with form
- [x] Implement store method
- [x] Implement edit method
- [x] Implement update method
- [x] Implement delete method
- [x] Add soft deletes functionality

## 5. Frontend Development
- [x] Create main layout with Breeze
- [x] Design todo list view
- [x] Create todo creation form
- [x] Create todo edit form
- [x] Add status update functionality
- [x] Implement due date picker
- [x] Add sorting and filtering options

## 6. Testing
- [ ] Write feature tests for Todo CRUD
- [ ] Write unit tests for Todo model
- [ ] Test authorization policies
- [ ] Test form validation

## 7. Polish & Optimization
- [x] Add form validation
- [x] Implement flash messages
- [x] Add loading states
- [ ] Optimize database queries
- [ ] Add pagination for todo list

## 8. Documentation
- [ ] Add README.md with setup instructions
- [ ] Document API endpoints
- [ ] Add inline code documentation
