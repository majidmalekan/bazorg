# Laravel Dynamic Caching with Repository Pattern

This project demonstrates how to implement a **Repository Pattern** with a **Read-Through Cache Strategy** in Laravel, enabling the dynamic selection of cache drivers (Redis, File, Database). The implementation ensures efficient data retrieval and caching, supporting scalable and maintainable code.

---

## Features

- **Repository Pattern**: Abstracts data access logic, ensuring clean architecture.
- **Read-Through Cache Strategy**: Retrieves data from cache when available, falls back to the database otherwise, and caches the result for future use.
- **Dynamic Cache Drivers**: Supports switching between Redis, File, and Database caching at runtime.
- **Unit and Feature Tests**: Comprehensive test coverage to validate the caching behavior and repository logic.

---

## Installation
1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd <project-directory>
   ```

2. Run Docker on your os:

2. Install Vendor:
   ```bash
    composer install
   ```

3. Start the development environment:
   ```bash
   ./vendor/bin/sail up -d
   ```

4. Configure the environment:
    - Copy the `.env.example` file to `.env`:
      ```bash
      cp .env.example .env
      ```
    - Set up the database connection and other environment variables in the `.env` file.
    - Configure the default cache driver in `.env` (e.g., `CACHE_DRIVER=redis`).

5. Run migrations:
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

6. Seed the database (optional):
   ```bash
   ./vendor/bin/sail artisan db:seed
   ```
---

## Usage

### Repository Integration

The project uses a repository structure to abstract data access logic. Here's how to interact with the repositories:

- **Fetching all records:**
  ```php
  $users = $userRepository->all();
  ```

- **Fetching a specific record:**
  ```php
  $user = $userRepository->find($id);
  ```

### Dynamic Cache Drivers

Switch between cache drivers (Redis, File, Database) dynamically using the `CacheContext` service.

#### Example:
```php
$cacheContext->useDriver('redis');
$cacheContext->put('key', 'value', now()->addMinutes(10));
$data = $cacheContext->get('key');
```

You can also switch the cache driver via an API endpoint:

#### Endpoint:
```http
POST /change-cache-driver
```

#### Request Body:
```json
{
  "driver": "redis" // or "file", "database"
}
```

### Testing the System

#### Run Unit and Feature Tests
The tests ensure proper functionality of the caching system and repository logic.

```bash
./vendor/bin/sail artisan test
```

#### Test Coverage:
- **Unit Tests**: Validate repository behavior with mocked caching.
- **Feature Tests**: Validate the full flow with real cache interaction.

---

## Folder Structure

### Key Directories and Files:

- `app/Contracts/CacheStrategy.php`: Defines the interface for cache strategies.
- `app/Services/Cache`: Contains the implementations for Redis, File, and Database caching.
- `app/Services/Cache/CacheContext.php`: Manages dynamic cache driver switching.
- `app/Repositories`: Contains the base repository and specific implementations (e.g., `UserRepository`).

---

## Customization

### Adding a New Cache Driver
To add a new caching strategy:

1. Create a new class implementing `CacheStrategy`.
2. Add the driver in the `CacheContext`'s `useDriver` method.

### Adjusting Cache Expiry
Modify the TTL (time-to-live) in the `remember` calls within the repository methods:
```php
$cacheKey = $this->getCacheKey('all');
return $this->cache->remember($cacheKey, now()->addMinutes(15), function () {
    return $this->model->all();
});
```

---

## Contribution

Feel free to contribute to this project by submitting pull requests or reporting issues.

---

## License

This project is licensed under the MIT License.
