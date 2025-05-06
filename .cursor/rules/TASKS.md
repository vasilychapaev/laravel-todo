---
description:
globs:
alwaysApply: false
---

# Todo App Development Tasks

## 1. Project Setup
- [ ] Create new Laravel project
- [ ] Install and configure Breeze
- [ ] Set up SQLite database
- [ ] Configure environment variables
- [ ] Run initial migrations

## 2. Database Structure
- [ ] Create Todo model with migration
  - title (string)
  - description (text, nullable)
  - due_date (datetime, nullable)
  - status (enum: pending, in_progress, completed)
  - user_id (foreign key)
- [ ] Create necessary indexes
- [ ] Set up model relationships (User -> Todo)

## 3. Authentication & Authorization
- [ ] Configure Breeze authentication
- [ ] Set up user registration
- [ ] Implement login/logout functionality
- [ ] Add authorization policies for Todo CRUD operations

## 4. Todo CRUD Operations
- [ ] Create TodoController
- [ ] Implement index method (list all todos)
- [ ] Implement create method with form
- [ ] Implement store method
- [ ] Implement edit method
- [ ] Implement update method
- [ ] Implement delete method
- [ ] Add soft deletes functionality

## 5. Frontend Development
- [ ] Create main layout with Breeze
- [ ] Design todo list view
- [ ] Create todo creation form
- [ ] Create todo edit form
- [ ] Add status update functionality
- [ ] Implement due date picker
- [ ] Add sorting and filtering options

## 6. Testing
- [ ] Write feature tests for Todo CRUD
- [ ] Write unit tests for Todo model
- [ ] Test authorization policies
- [ ] Test form validation

## 7. Polish & Optimization
- [ ] Add form validation
- [ ] Implement flash messages
- [ ] Add loading states
- [ ] Optimize database queries
- [ ] Add pagination for todo list

## 8. Documentation
- [ ] Add README.md with setup instructions
- [ ] Document API endpoints
- [ ] Add inline code documentation
