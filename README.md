# Laravel Todo List API


A simple API to manage todo lists built with Laravel.

## ❤️ Support The Development
**Do you like this project? Support it by donating:**

<a href="https://www.buymeacoffee.com/ishanvyas" target="_blank">
    <img src="https://www.buymeacoffee.com/assets/img/custom_images/purple_img.png" alt="Buy Me A Coffee" style="height: 41px !important;width: 174px !important;box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;-webkit-box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;" >
</a>

<a href="https://www.patreon.com/ishanvyas">
    <img src="https://c5.patreon.com/external/logo/become_a_patron_button@2x.png" width="160">
</a>

**or** [Paypal me](https://paypal.me/IshanVyas?locale.x=en_GB)

**or** [![Contact me on Codementor](https://www.codementor.io/m-badges/isvyas/get-help.svg)](https://www.codementor.io/@isvyas?refer=badge)

### Follow me
- [GitHub](https://github.com/ishanvyas22)
- [Instagram](https://www.instagram.com/ishancodes)
- [LinkedIn](https://www.linkedin.com/in/ishan-vyas-314111112)
- [Twitter](https://twitter.com/ishanvyas22)

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
