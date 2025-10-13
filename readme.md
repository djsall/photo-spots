# Photo Spots

## Your self-hosted laravel application, to store your favorite photo spots

### Requirements:
- composer
- php8.3
- email service

### Installation:
- clone the repository
- `composer install`
- `cp .env.example .env`
- open up the `.env` file
  - set up the environment variables you want
  - add the email server details
  - change the `APP_URL` to the actual root url
- `php artisan key:generate`
- `php artisan storage:link`
- `php artisan db:seed`
  - this command will add the default tags
  - this command will also create an administrative user, with the credentials `test@example.com` and `password`. Please change this after the fact.
