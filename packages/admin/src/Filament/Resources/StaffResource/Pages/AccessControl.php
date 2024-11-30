<?php

namespace Payflow\Admin\Filament\Resources\StaffResource\Pages;

use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Payflow\Admin\Filament\Resources\StaffResource;
use Payflow\Admin\Support\Facades\PayflowAccessControl;
use Payflow\Admin\Support\Facades\PayflowPanel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * @property Collection $roles
 * @property Collection $permissions
 * @property Collection $groupedPermissions
 */
class AccessControl extends Page
{
    protected static string $resource = StaffResource::class;

    protected static string $view = 'payflowpanel::resources.staff-resource.pages.access-control';

    public array $state = [];

    private string $stateSeparator = '#';

    public function getTitle(): string|Htmlable
    {
        return __('payflowpanel::staff.acl.title');
    }

    public function mount()
    {
        foreach ($this->roles as $role) {
            /** @var Role $roleModel */
            $roleModel = Role::findByName($role->handle, PayflowPanel::getPanel()->getAuthGuard());

            $this->syncRolePermissions($roleModel);
        }
    }

    protected function syncRolePermissions(Model $role): void
    {
        $rolePermissions = $role->getAllPermissions()->pluck('name');

        foreach ($this->permissions as $permission) {
            data_set($this, $this->getStatePath($role->name, $permission->handle), $rolePermissions->contains($permission->handle));
        }
    }

    public function getStatePath(string $role, string $permission): string
    {
        return "state.{$role}{$this->stateSeparator}{$permission}";
    }

    public function updatedState($value, $path)
    {
        $result = $this->resolveRolePermissionFromPath($path);

        if (is_string($result)) {
            $error = $result;

            data_set($this, 'state.'.$path, false);

            Notification::make()
                ->title($error)
                ->danger()
                ->send();

            return;
        }

        [$role, $permission] = $result;
        /**
         * @var Role $role
         * @var Permission $permission
         * */
        if ($role && $permission) {
            if ($value == true) {
                $role->givePermissionTo($permission);
            } else {
                $role->revokePermissionTo($permission);

                $grouped = $this->groupedPermissions->first(fn ($gp) => $gp->handle == $permission->name);

                if (filled($grouped)) {
                    $role->revokePermissionTo($grouped->children->pluck('handle')->toArray());

                    foreach ($grouped->children->pluck('handle') as $perm) {
                        data_set($this, $this->getStatePath($role->name, $perm), false);
                    }
                }
            }

            Notification::make()
                ->title(__('payflowpanel::staff.acl.notification.updated'))
                ->success()
                ->send();
        }
    }

    private function resolveRolePermissionFromPath($path): string|array
    {
        $error = null;

        try {
            [$roleHandle, $permissionHandle] = explode($this->stateSeparator, $path);
        } catch (Exception $e) {
            $error = __('payflowpanel::staff.acl.notification.error');
        }

        if (blank($error)) {
            $registeredRole = $this->roles->first(fn ($r) => $roleHandle == $r->handle);
            $registeredPermission = $this->permissions->first(fn ($p) => $permissionHandle == $p->handle);

            try {
                $role = Role::findByName($roleHandle, PayflowPanel::getPanel()->getAuthGuard());
            } catch (Exception $e) {
                $role = null;
            }

            try {
                $permission = Permission::findByName($permissionHandle, PayflowPanel::getPanel()->getAuthGuard());
            } catch (Exception $e) {
                $permission = null;
            }

            if (blank($registeredRole) || blank($role)) {
                $error = __('payflowpanel::staff.acl.notification.no-role');
            }

            if (blank($registeredPermission) || blank($permission)) {
                $error = blank($error) ? __('payflowpanel::staff.acl.notification.no-permission') : __('payflowpanel::staff.acl.notification.no-role-permission');
            }
        }

        if (filled($error)) {
            return $error;
        } else {
            return [$role, $permission];
        }
    }

    public function getRolesProperty(): Collection
    {
        $admin = PayflowAccessControl::getAdmin();

        return PayflowAccessControl::getRoles()->reject(fn ($role) => $admin->contains($role->handle));
    }

    public function getPermissionsProperty(): Collection
    {
        return PayflowAccessControl::getPermissions();
    }

    public function getGroupedPermissionsProperty(): Collection
    {
        return PayflowAccessControl::getGroupedPermissions();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('add_role')
                ->label('payflowpanel::staff.action.add-role.label')
                ->translateLabel()
                ->form([
                    TextInput::make('name')
                        ->label('payflowpanel::staff.form.role.label')
                        ->translateLabel()
                        ->unique(table: Role::class)
                        ->required(),
                ])
                ->action(fn ($data) => $this->syncRolePermissions(Role::create([
                    'name' => $data['name'],
                    'guard_name' => PayflowPanel::getPanel()->getAuthGuard(),
                ])))
                ->modalWidth('md'),
        ];
    }

    public function deleteRoleAction(): Action
    {
        return Action::make('deleteRole')
            ->icon('heroicon-m-trash')
            ->color('danger')
            ->size(ActionSize::Small)
            ->tooltip(__('payflowpanel::staff.action.delete-role.label'))
            ->iconButton()
            ->requiresConfirmation()
            ->modalHeading(function (Page $livewire) {
                $arguments = Arr::last($livewire->mountedActionsArguments);

                if ($handle = $arguments['handle'] ?? null) {
                    $role = PayflowAccessControl::getRoles()->first(fn ($r) => $r->handle == $handle);

                    return __('payflowpanel::staff.action.delete-role.heading', ['role' => $role->transLabel]);
                }

                return null;
            })
            ->action(function ($arguments, Page $livewire) {
                $role = Role::findByName($arguments['handle']);
                $permissions = $role->getAllPermissions();

                foreach ($permissions as $permission) {
                    data_forget($livewire, $this->getStatePath($role->name, $permission->name));
                }

                $role->delete();
            });
    }
}
