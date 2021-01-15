# Laravel Todo List API


A simple API to manage todo lists built with Laravel.

## Installation

1. Clone the repo locally:
    ```sh
    git clone git@github.com:ishanvyas22/laravel-todo-list-api.git
    cd laravel-todo-list-api
    ```

2. Install dependencies
    ```sh
    composer install
    ```

3. Generate application key (if not already generated)
    ```sh
    php artisan key:generate
    ```

4. Run database migrations
    ```sh
    php artisan migrate
    ```

5. Run the dev server (the output will give the address):
    ```sh
    php artisan serve
    ```

## Running tests

```sh
php artisan test
```

## Pending

- [ ] Add basic validation in create, update task API call
- [ ] Take config for task status
- [ ] Global scope
- [ ] Split methods from `TaskController`
