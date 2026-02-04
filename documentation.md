# Google Authentication Integration Documentation

This document outlines the steps taken to integrate Google Sign-In and Sign-Up functionality into the Fiona's Style e-commerce platform using Laravel Socialite.

## 1. Prerequisites
- Laravel 11.x
- Composer
- Google Cloud Console Project

## 2. Implementation Steps

### Step 1: Install Laravel Socialite
Run the following command in your terminal:
```bash
composer require laravel/socialite
```

### Step 2: Database Migration
A new migration was created to add `google_id` and `avatar` columns to the `users` table.
- **File**: `database/migrations/xxxx_xx_xx_xxxxxx_add_google_id_to_users_table.php`
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('google_id')->nullable()->after('email');
    $table->string('avatar')->nullable()->after('google_id');
});
```
Run the migration:
```bash
php artisan migrate
```

### Step 3: Configure Services
Added Google credentials to `config/services.php`:
```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URL'),
],
```

### Step 4: Environment Variables
Add the following keys to your `.env` file:
```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URL=http://your-domain.com/auth/google/callback
```

### Step 5: Implementation of SocialiteController
Created `app/Http/Controllers/Auth/SocialiteController.php` to handle the redirection and callback logic.
- **Redirection**: `redirectToGoogle()` sends the user to Google.
- **Callback**: `handleGoogleCallback()` processes the user data returned by Google.
    - If the user exists (linked by `google_id` or `email`), they are logged in.
    - If the user is new, a new `User` record is created (role: Customer), and a linked `Customer` record is initialized.

### Step 6: Define Routes
Added routes to `routes/web.php`:
```php
use App\Http\Controllers\Auth\SocialiteController;

Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
```

### Step 7: Update Views
Added "Sign in with Google" buttons to:
- `resources/views/frontend/auth/login.blade.php`
- `resources/views/frontend/auth/register.blade.php`

## 3. Usage
1. Go to the Login or Register page.
2. Click on the "Google" button.
3. Authenticate with your Google account.
4. You will be redirected to the customer dashboard upon successful login/registration.

## 4. Notes
- The `phone` field in the `customers` table will be empty for users who register via Google. They should be prompted to update their profile after the first login.
- Ensure that the Redirect URL in the Google Cloud Console exactly matches the `GOOGLE_REDIRECT_URL` in your `.env`.
