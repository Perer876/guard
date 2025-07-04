> [!IMPORTANT]
> Still in development, not ready for production use.

# Guard

Lightweight, enum-based definition, querying and mapping of permissions in PHP applications.

## Example

You can define permissions using an enum and specify which roles are granted to each permission.
```php
use Guard\GrantTo;
use Guard\Permission;

#[GrantTo(UserRole::Admin)]
enum ExamplePermission implements Permission
{
    #[GrantTo(UserRole::Member)]
    case ViewAny;

    #[GrantTo(UserRole::Member, UserRole::Viewer)]
    case View;

    #[GrantTo(UserRole::Member)]
    #[GrantTo(UserRole::Editor)]
    case Create;

    #[GrantTo(UserRole::Member, UserRole::Editor)]
    case Update;
    
    case Delete;
}
```
And then you can check if a user has a specific permission:
```php
$user->hasPermission(ExamplePermission::ViewAny);
```

## Installation

You can install the package via Composer:
```bash
composer require guard/permission
```

## Documentation

Coming soon.
