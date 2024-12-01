# Payflow Installation Guide

Welcome to the **Payflow Installation Guide**! This document will walk you through the steps required to set up **Payflow** and integrate it with your Laravel project seamlessly.

---

## Prerequisites

- **A Laravel Project**: Ensure you have a Laravel project set up. If not, you can create one using the Laravel installer or Composer:
  ```bash
  composer create-project laravel/laravel your-project-name
  ```

---

## Installation Steps

### 1. Clone the Payflow Package

Inside your Laravel project directory, create a `packages` folder if it doesn't already exist:
```bash
mkdir packages
```

Next, clone the **Payflow Package** from GitHub into the `packages` folder:
```bash
git clone https://github.com/nibezak/payflow-package.git packages/payflow
```

---

### 2. Update `composer.json`

Update your Laravel project's `composer.json` file in the root directory to include the following:

```json
"repositories": [
    {
        "type": "path",
        "url": "packages/*",
        "symlink": true
    }
],
"require": {
    "payflow/dev": "*"
}
```

---

### 3. Install the Package

Run the following command in your terminal to install the package:
```bash
composer update
```

---

### 4. Register Payflow in `AppServiceProvider`

Open the `App\Providers\AppServiceProvider` file in your project and update it as follows:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Payflow\Admin\Support\Facades\PayflowPanel;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        PayflowPanel::register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
```

---

### 5. Run Payflow Installation

To finalize the installation, run the following Artisan command:
```bash
php artisan payflow:install
```

This will take you through a series of setup questions to configure your Payflow installation. During this process, you'll:
- Create a default admin user (if required)
- Seed initial data for your application

---

### 6. Access the Payflow Panel

Once the installation is complete, navigate to:
```
http://localhost/payflow
```

Log in using the credentials of the admin user you just created.

---

## That's It!

Your **Payflow** installation is now complete. Enjoy seamless payment integration and admin panel functionality. 🎉

If you encounter any issues or need further assistance, feel free to check the [Payflow Documentation](#) or raise an issue on the [GitHub Repository](https://github.com/nibezak/payflow-package).