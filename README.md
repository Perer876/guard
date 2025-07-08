> [!IMPORTANT]
> Still in development, not feature complete.

# Guard

Lightweight, enum-based definition, querying and mapping of permissions in PHP applications.

## Usage

You can define roles and permissions using an enum. 

For example, you can define roles like this:
```php
<?php declare(strict_types=1);

namespace App\Permissions;

use Guard\Role;

enum UserRole: string implements Role
{
    case Admin = 'admin';
    case User = 'user';
    case Guest = 'guest';
}
```

Then, you can specify which roles are granted to each permission.

```php
<?php declare(strict_types=1);

namespace App\Permissions;

use Guard\GrantTo;
use Guard\Permission;

#[GrantTo(UserRole::Admin)] // <- Top level: It's granted to all permissions
enum TodoPermission implements Permission
{
    #[GrantTo(UserRole::Member)] // <- Fine-grained: It's granted to this specific permission
    case ViewAny;

    #[GrantTo(UserRole::Member, UserRole::Viewer)] // <- Multiple roles can be granted
    case View;

    #[GrantTo(UserRole::Member)]
    #[GrantTo(UserRole::Editor)]
    case Create;

    #[GrantTo(UserRole::Member, UserRole::Editor)]
    case Update;
    
    case Delete;
}
```
Before you can check permissions, you need to implement the `Subject` interface 
in your user model.
You also need to return the user's roles in a `getRoles` method and must add the 
`AsSubject` trait to the subject so you can check permissions.

```php
<?php declare(strict_types=1);

namespace App\Models;

use App\Permissions\UserRole;
use Guard\AsSubject;
use Guard\Subject;
use Illuminate\Foundation\Auth\User as Authenticatable;

final class User extends Authenticatable implements Subject
{
    use AsSubject;
    
    /** @return UserRole[] */
    public function getRoles(): array
    {
        // Return an array of roles for the user
        return [$this->role];
    }
    
    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
        ];
    }
    
    /* ... */
}
```

In a policy, for example, you can check the user model permissions like this:

```php
$user->hasPermission(TodoPermission::ViewAny);
```

## Installation

You can install the package via Composer:
```bash
composer require perer876/permission
```

## Documentation

Coming soon.
