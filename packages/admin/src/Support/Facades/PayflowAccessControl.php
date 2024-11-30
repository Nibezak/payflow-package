<?php

namespace Payflow\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection getRoles(bool $refresh = false)
 * @method static \Illuminate\Support\Collection getPermissions(bool $refresh = false)
 * @method static \Illuminate\Support\Collection getGroupedPermissions(bool $refresh = false)
 * @method static void useRoleAsAdmin(string|array $roleHandle)
 * @method static \Illuminate\Support\Collection getAdmin()
 * @method static \Illuminate\Support\Collection getRolesWithoutAdmin(bool $refresh = false)
 *
 * @see \Payflow\Admin\Auth\Manifest
 */
class PayflowAccessControl extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'payflow-access-control';
    }
}
