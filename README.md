# Travelbud System

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-blue)](https://www.php.net/releases/7_3_0.php)
[![Lumen Version](https://img.shields.io/badge/lumen-%3E%3D%205.0-yellow)](https://lumen.laravel.com/docs)

Travelbud System is a robust backend API system built with Lumen, a fast PHP micro-framework from Laravel. It offers a suite of APIs for weather information, translation services, geocoding, user management, and authentication.

## Features

- **Weather APIs**: Provides current weather, forecast, and historical weather data.
- **Translation APIs**: Supports language detection, language list retrieval, and text translation.
- **Geocoding APIs**: Offers geocoding by latitude/longitude, place ID, and address.
- **User Management**: Allows CRUD operations for managing users securely.
- **Authentication**: Implements JWT-based authentication for secure API access.

## Getting Started

### Requirements

- PHP >= 7.3
- Composer
- MySQL or SQLite (for database storage)
- Redis (optional, for caching)

### Contributors

Thanks to the following contributors who have helped to improve this project:

<div style="display: flex; flex-wrap: wrap; gap: 20px;">
    <div style="width: 200px; background-color: #f0f0f0; padding: 10px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <a href="https://github.com/maryelang" style="text-decoration: none; color: inherit;">
            <img src="https://avatars.githubusercontent.com/maryelang" alt="Marielle" style="border-radius: 50%; width: 100px; height: 100px; object-fit: cover;">
            <h4 style="margin-top: 10px;">Marielle</h4>
            <p style="font-size: 14px; color: #666;">Contributor</p>
        </a>
    </div>
    
    <div style="width: 200px; background-color: #f0f0f0; padding: 10px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <a href="https://github.com/JerFoxDyesYete" style="text-decoration: none; color: inherit;">
            <img src="https://avatars.githubusercontent.com/JerFoxDyesYete" alt="Jerry" style="border-radius: 50%; width: 100px; height: 100px; object-fit: cover;">
            <h4 style="margin-top: 10px;">Jerry</h4>
            <p style="font-size: 14px; color: #666;">Contributor</p>
        </a>
    </div>
    
    <div style="width: 200px; background-color: #f0f0f0; padding: 10px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <a href="https://github.com/jayson092301" style="text-decoration: none; color: inherit;">
            <img src="https://avatars.githubusercontent.com/jayson092301" alt="Daniel" style="border-radius: 50%; width: 100px; height: 100px; object-fit: cover;">
            <h4 style="margin-top: 10px;">Daniel</h4>
            <p style="font-size: 14px; color: #666;">Contributor</p>
        </a>
    </div>
    
    <!-- Add more contributors as needed -->
</div>

### Installation

```bash
git clone https://github.com/JerFoxDyesYete/travelbudv3.git
cd repository
composer install
cp .env.example .env
php artisan key:generate
# Update .env with your database and API credentials
php artisan migrate --seed
php -S localhost:8000 -t public
