# AKILIMO API - Laravel Implementation

The AKILIMO API is a Standardized RESTful Web Service API built with Laravel that provides computational and retrieval capabilities for agricultural recommendations across various agronomic practices including fertilizer application, intercropping, scheduled planting and harvesting, and optimal planting practices.

## API Architecture

The AKILIMO API is structured into three distinct endpoints to accommodate different application requirements and user interaction levels:

### 1. Advanced Endpoint

Designed for comprehensive data collection scenarios, this endpoint accepts the most extensive set of parameters. It is optimized for applications with rich user interfaces such as mobile applications, web dashboards, and desktop software where detailed user input is feasible and beneficial.

**Use Cases:**
- Mobile agricultural advisory apps
- Web-based farm management systems
- Desktop agricultural software
- Research platforms requiring detailed input parameters

### 2. Intermediate Endpoint

This endpoint strikes a balance between data comprehensiveness and user experience, accepting a moderate number of input parameters. It is ideal for applications that require meaningful recommendations without overwhelming the user with extensive data entry requirements.

**Use Cases:**
- Chatbot integrations
- Survey-style applications
- SMS-based advisory services
- Progressive web applications

### 3. Basic Endpoint

The streamlined endpoint requires only essential information to generate valuable recommendations. It is specifically designed for constrained environments where minimal user input is preferred or technically necessary.

**Use Cases:**
- USSD applications
- Voice response systems (IVR)
- SMS services
- Basic mobile interfaces
- IoT device integrations

## Quick Start Guide

### Prerequisites

- PHP 8.3 or higher
- Composer dependency manager
- Laravel 10+ framework
- MySQL 8.0+ or PostgreSQL 13+

### Getting Started

1. **API Access Registration**
   Register for API access through the [user portal] to obtain your unique API key and authentication credentials.

2. **Documentation Review**
   Familiarize yourself with the comprehensive API documentation available on [GitHub] and interactive [Swagger] documentation.

3. **Endpoint Selection**
   Choose the appropriate API endpoint (Advanced, Intermediate, or Basic) based on your application's requirements and user experience goals.

4. **Laravel Integration**
   Implement the selected API endpoint into your Laravel application using the provided SDK or direct HTTP client integration.

## Laravel-Specific Implementation

### Installation

```bash
composer require akilimo/laravel-sdk
```

### Configuration

Add your API credentials to your Laravel `.env` file:

```env
AKILIMO_API_KEY=your_api_key_here
AKILIMO_BASE_URL=https://api.akilimo.org
AKILIMO_TIMEOUT=30
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Akilimo\LaravelSdk\AkilimoServiceProvider"
```

### Service Container Integration

The package automatically registers the AKILIMO service in Laravel's service container, making it available for dependency injection:

```php
use Akilimo\LaravelSdk\AkilimoService;

class RecommendationController extends Controller
{
    public function __construct(private AkilimoService $akilimo)
    {
    }
    
    public function getRecommendations(Request $request)
    {
        $recommendations = $this->akilimo
            ->advanced()
            ->recommendations($request->validated());
            
        return response()->json($recommendations);
    }
}
```

### Middleware Integration

Utilize Laravel middleware for API authentication and rate limiting:

```php
// In routes/api.php
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::post('/recommendations/advanced', [RecommendationController::class, 'advanced']);
    Route::post('/recommendations/intermediate', [RecommendationController::class, 'intermediate']);
    Route::post('/recommendations/basic', [RecommendationController::class, 'basic']);
});
```

### Form Request Validation

Create dedicated form request classes for each endpoint:

```php
php artisan make:request AdvancedRecommendationRequest
php artisan make:request IntermediateRecommendationRequest
php artisan make:request BasicRecommendationRequest
```

## Build Status & Quality Metrics

### Continuous Integration

**Tests**

[![Tests](https://github.com/IITA-AKILIMO/new-akilimo-api/actions/workflows/unit-test.yml/badge.svg)](https://github.com/IITA-AKILIMO/new-akilimo-api/actions/workflows/unit-test.yml)

**Docker Build**

[![Build](https://github.com/IITA-AKILIMO/new-akilimo-api/actions/workflows/docker-build.yml/badge.svg)](https://github.com/IITA-AKILIMO/new-akilimo-api/actions/workflows/docker-build.yml)

**Code Quality Assessment**

[![codebeat badge](https://codebeat.co/badges/ba1363f9-5713-458c-bb1d-05fd1bb8b0fa)](https://codebeat.co/projects/github-com-iita-akilimo-new-akilimo-api-develop)

## Development Environment Setup

### System Requirements

- **PHP Version:** 8.3 or higher with required extensions
- **Framework:** Laravel 10+ with Octane support for enhanced performance
- **Database:** MySQL 8.0+ or PostgreSQL 13+
- **Cache:** Redis 6+ for session and cache management
- **Queue:** Redis or database-driven queue for background processing

### Development Tools

- **Dependency Management:** Composer 2.5+
- **Asset Compilation:** Vite (Laravel's default)
- **Testing Framework:** PHPUnit 10+ with Laravel's testing utilities
- **Code Quality:** PHP CS Fixer, PHPStan, Larastan
- **API Documentation:** Swagger/OpenAPI 3.0

### Local Development Setup

1. **Clone and Install Dependencies**
```bash
git clone https://github.com/IITA-AKILIMO/akilimo-api.git
cd akilimo-api
composer install
npm install
```

2. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Setup**
```bash
php artisan migrate
php artisan db:seed
```

4. **Start Development Server**
```bash
php artisan serve
# Or with Laravel Sail for Docker environment
./vendor/bin/sail up
```

### Testing

Run the comprehensive test suite:

```bash
php artisan test
# Or with coverage
php artisan test --coverage
```

### Code Quality Checks

```bash
# PHP CS Fixer
./vendor/bin/php-cs-fixer fix

# PHPStan Static Analysis
./vendor/bin/phpstan analyse

# Laravel Pint (Code Styling)
./vendor/bin/pint
```

## Performance Considerations

- Implement API response caching using Laravel's cache system
- Utilize Laravel Octane for enhanced performance in production
- Configure proper database indexing for recommendation queries
- Implement queue-based processing for intensive computational tasks
- Use Laravel Horizon for queue monitoring and management
