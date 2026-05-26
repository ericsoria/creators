# Authentication

The MVP uses Laravel Sanctum for internal API token authentication.

## Mode

The foundation is designed for token-based internal API access. OAuth2 and external client credentials are out of scope until third-party integrations exist.

## Roles

Users support four internal roles:

- `admin`
- `manager`
- `operator`
- `viewer`

The role model is intentionally simple for MVP. Future capabilities should keep authorization behind middleware, gates, policies, or Form Requests so a more granular permission system can replace the role internals later.

## Initial Admin User

The database seeder creates an initial admin user from environment configuration:

```txt
ADMIN_USER_NAME="Admin User"
ADMIN_USER_EMAIL=admin@example.com
ADMIN_USER_PASSWORD=password
```

The default password is suitable only for local development. Real environments must set a secure password through environment variables.
