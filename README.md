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
### Contributors

Thanks to the following contributors who have helped to improve this project:

| Avatar | Contributor |
|--------|-------------|
| <img src="https://avatars.githubusercontent.com/maryelang" width="50" style="border-radius: 50%;"> <br> [Marielle](https://github.com/maryelang) | Marielle |
| <img src="https://avatars.githubusercontent.com/JerFoxDyesYete" width="50" style="border-radius: 50%;"> <br> [Jerry](https://github.com/JerFoxDyesYete) | Jerry |
| <img src="https://avatars.githubusercontent.com/jayson092301" width="50" style="border-radius: 50%;"> <br> [Daniel](https://github.com/jayson092301) | Daniel |
| <img src="https://avatars.githubusercontent.com/Avahayop03" width="50" style="border-radius: 50%;"> <br> [Avah](https://github.com/Avahayop03) | Avah |
| <img src="https://avatars.githubusercontent.com/MarkRey13" width="50" style="border-radius: 50%;"> <br> [Mark Rey](https://github.com/MarkRey13) | Mark Rey |
| <img src="https://avatars.githubusercontent.com/" width="50" style="border-radius: 50%;"> <br> [Juhiver](https://github.com/) | Juhiver |


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
