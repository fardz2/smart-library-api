# Cara Menjalankan Proyek Laravel

Ikuti langkah-langkah berikut untuk menyiapkan dan menjalankan api laravel smart-library:

1. clone repository:

    ```bash
    git clone <my-cool-project>
    ```

2. run:

    ```bash
    composer install
    ```

3. run:

    ```bash
    cp .env.example .env
    ```

4. run:

    ```bash
    php artisan key:generate
    ```

5. run:

    ```bash
    php artisan migrate:refresh --seed
    ```

6. run:

    ```bash
    php artisan serve
    ```

Server dapat diakses di 127.0.0.1:8000
