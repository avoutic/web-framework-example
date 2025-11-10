# WebFramework Demo Application

This is a **demo/example application** showcasing the capabilities of [avoutic/web-framework](https://github.com/avoutic/web-framework). It demonstrates real-world usage of the framework's core features including authentication, user management, templating, and more.

## What's Demonstrated

This demo app showcases the following WebFramework capabilities:

- **Authentication & User Management**
  - User registration with email verification
  - Login/logout functionality
  - Password reset flow
  - Email change with verification
  - Password change
  - Session management

- **Security Features**
  - CSRF protection
  - Security headers middleware
  - Input validation
  - Password hashing and validation

- **Templating**
  - Latte template engine integration
  - Template inheritance
  - Message display system

- **Email Integration**
  - Postmark email service integration
  - Verification code emails
  - Transactional email sending

- **Database/ORM**
  - Entity/Repository pattern
  - CRUD operations (Create, Read, Update, Delete)
  - Database migrations
  - Entity collections
  - Foreign key relationships

- **Infrastructure**
  - Database integration (MySQL/MariaDB)
  - Redis caching
  - Dependency injection
  - Middleware stack
  - Route management

## Installation

Clone this repository and install dependencies:

```bash
git clone <repository-url>
cd web-framework-example-app
composer install
```

**Note:** This is a demo application. For starting a new project, use the skeleton:
```bash
composer create-project avoutic/web-framework-skeleton my-project
```

## Configuration

### Database and Cache

Start the MariaDB database and Redis cache using Docker:

```bash
docker-compose up -d
```

The database will be available at `localhost:3399` and the Redis server will be available at `localhost:3400`.

**Important:** Configuration files in `config/auth/` contain sensitive information. If you're using `env()` statements, you can safely commit them. Otherwise, add `config/auth/` to `.gitignore`.

### Postmark Email Service

This demo app uses Postmark for sending emails (verification codes, password resets, etc.).

1. **Get a Postmark API Key:**
   - Sign up at https://account.postmarkapp.com/sign_up
   - Create a server and get your API key from the server settings

2. **Configure the API Key:**
   - Edit `config/auth/postmark.php` and set your API key:
     ```php
     return env('POSTMARK_API_KEY', 'your-actual-api-key-here');
     ```
   - Or set the `POSTMARK_API_KEY` environment variable in the `.env` file

3. **Configure Sender Email:**
   - Edit `config/config.php` and update the `sender_core.default_sender` and `sender_core.assert_recipient` values
   - Or set the `POSTMARK_SENDER_EMAIL` and `ASSERT_RECIPIENT_EMAIL` environment variables in the `.env` file
   - **Important:** The sender email must be verified in your Postmark account

### Custom Render Service

This demo includes a custom `AppRenderService` that automatically:
- Includes messages from `MessageService` in all templates
- Provides CSRF tokens to templates
- Includes base URL for template use

This demonstrates how to extend the framework's rendering capabilities.

## Setup

1. **Start the database and cache:**
   ```bash
   docker-compose up -d
   ```

2. **Check database status and run migrations:**
   ```bash
   php framework db:status
   php framework db:migrate
   ```

   This will create the `posts` table needed for the Database/ORM demo.

3. **Run sanity checks:**
   ```bash
   php framework sanity:check
   ```

4. **Configure Postmark** (see Configuration section above)

## Running the Demo

Start the development server:

```bash
php -S localhost:8000 public/index.php
```

Then access the demo application at `http://localhost:8000`.

## Exploring the Demo

Once running, you can:

1. **Register a new account** - See the registration flow with email verification
2. **Login** - Experience the authentication system
3. **View your profile** - See authenticated user information
4. **Change password** - Test password management
5. **Change email** - See email verification flow
6. **Reset password** - Try the password reset functionality
7. **Create and manage posts** - See Database/ORM capabilities with full CRUD operations

All forms include validation, CSRF protection, and user-friendly error messages.

This demo also demonstrates the following capabilities:
- Database/ORM
- Multi-language support with translation files
- Logging
- Event Dispatching and Listening

## Project Structure

- `actions/` - Action classes handling HTTP requests
- `routes/` - Route definitions (Authenticated and Unauthenticated)
- `templates/` - Latte templates with base layout
- `src/` - Application code including custom services
- `config/` - Configuration files
- `definitions/` - Dependency injection definitions
- `migrations/` - Database migrations

## Learn More

For detailed documentation about WebFramework, visit:
- [WebFramework website](https://web-framework.com)