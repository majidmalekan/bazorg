# Laravel Dynamic Caching with Repository Pattern

This project demonstrates how to implement a **Repository Pattern** with a **Read-Through Cache Strategy** in Laravel,
enabling the dynamic selection of cache drivers (Redis, File, Database). The implementation ensures efficient data
retrieval and caching, supporting scalable and maintainable code.

---

## Features

- **Repository Pattern**: Abstracts data access logic, ensuring clean architecture.
- **Read-Through Cache Strategy**: Retrieves data from cache when available, falls back to the database otherwise, and
  caches the result for future use.
- **Dynamic Cache Drivers**: Supports switching between Redis, File, and Database caching at runtime.
- **Feature Tests**: Comprehensive test coverage to validate the caching behavior and repository logic.

---

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd <project-directory>
   ```

2. Run Docker on your os

2. Install Vendor:
   ```bash
    composer install
   ```

3. Start the development environment:
   ```bash
   ./vendor/bin/sail up -d
   or
   ./vendor/bin/sail up 
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

- **Fetching Paginate records:**
  ```php
  $products = $productRepository->index();
  ```

- **Fetching a specific record:**
  ```php
  $product = $productRepository->find($id);
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

#### Endpoints:

```http
POST /change-cache-driver
GET api/v1/product
POST api/v1/product
GET api/v1/product/{id}
PUT api/v1/product/{id}
DELETE api/v1/admin/product/{id}
POST api/v1/register
POST api/v1/login
GET api/v1/me
PUT api/v1/user/{id}
```

### Testing the System

#### Run Feature Tests

The tests ensure proper functionality of the caching system and repository logic.

```bash
./vendor/bin/sail artisan test
```

#### Test Coverage:

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
   return $this->cache->remember($this->getTableName() . '_find_' . (auth('sanctum')->check() ? request()->user('sanctum')->id . $id : $id),
            (auth('sanctum')->check() ? env('CACHE_EXPIRE_TIME') : env('CACHE_EXPIRE_GENERAL_TIME')),
            function () use ($id) {
                return $this->model
                    ->query()
                    ->where('id', $id)
                    ->firstOrFail();
            });;
```

---

## Swagger Integration

This project uses **Swagger** for API documentation, making it easy to explore and test the API.

### Installation

1. Install `l5-swagger`:
   ```bash
   composer require darkaonline/l5-swagger
   ```

2. Publish the configuration:
   ```bash
   php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider"
   ```

3. Configure Swagger in `config/l5-swagger.php`:
    - Update the `default` settings such as `title`, `description`, and `version`.

4. Swagger Documentation yaml file:
   ```bash
   ./resources/swagger/openapi.yaml
   ```
5. Access the API Documentation:
   Visit `/api/documentation` in your browser to view the Swagger UI.
   ```
6. Access the API Documentation:
   Call `/api/v1/yaml-convert` in your browser to generate the Swagger json docs in `storage/api-docs/api-docs.json`.

### Customizing Swagger Documentation

- Add annotations of a route to the yaml file and then run the route

#### Example:

```yaml
paths:
    /:
        get:
            description: "Home page"
            responses:
                default:
                    description: "Welcome page"
    /api/v1/login:
        post:
            operationId: "login by email and password"
            tags:
                - "User Auth"
            summary: "User login by email and password"
            description: "login by email and password User Here"
            requestBody:
                content:
                    multipart/form-data:
                        schema:
                            type: object
                            required:
                                - email
                                - password
                            properties:
                                email:
                                    type: string
                                    example: "m@gmail.com"
                                password:
                                    type: string
                                    example: "123mM!"
            responses:
                '201':
                    description: "login Successfully"
                    content:
                        application/json:
                            schema:
                                type: object
                '200':
                    description: "login Successfully"
                    content:
                        application/json:
                            schema:
                                type: object
                '422':
                    description: "Unprocessable Entity"
                    content:
                        application/json:
                            schema:
                                type: object
                '400':
                    description: "Bad request"
                '404':
                    description: "Resource Not Found"
                '500':
                    description: "Server"
```

---

## Contribution

Feel free to contribute to this project by submitting pull requests or reporting issues.

---

## License

This project is licensed under the MIT License.
