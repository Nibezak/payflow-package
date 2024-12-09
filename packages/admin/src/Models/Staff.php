<?php
namespace Payflow\Admin\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Payflow\Admin\Database\Factories\StaffFactory;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Payflow\Admin\Models\Tenant; // Corrected namespace for the Tenant model
use Illuminate\Support\Str; // Import Str facade for generating random strings

class Staff extends Authenticatable implements FilamentUser, HasName
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',  // Add tenant_id to the fillable array
        'firstname',
        'lastname',
        'admin',
        'email',
        'password',
    ];

    protected $guard_name = 'staff';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Append attributes to the model.
     *
     * @var array
     */
    protected $appends = ['fullName'];

    /**
     * Create a new instance of the Model.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('payflow.database.table_prefix').$this->getTable());

        if ($connection = config('payflow.database.connection')) {
            $this->setConnection($connection);
        }
    }

    /**
     * Retrieve the model for a bound value.
     *
     * Currently Livewire doesn't support route bindings for
     * soft deleted models so we need to rewire it here.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->resolveSoftDeletableRouteBinding($value, $field);
    }

    /**
     * Apply the basic search scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $term
     * @return void
     */
    public function scopeSearch($query, $term)
    {
        if ($term) {
            $parts = explode(' ', $term);

            foreach ($parts as $part) {
                $query->whereAny(['email', 'firstname', 'lastname'], 'LIKE', "%$part%");
            }
        }
    }

    /**
     * Get staff member's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentName(): string
    {
        return $this->fullName;
    }

    /**
     * Boot method to ensure tenant_id is set when creating staff.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($staff) {
            // If no tenant_id is set, create a new tenant and associate with the staff
            if (!$staff->tenant_id) {
                // Loop until a unique tenant name is found
                do {
                    // Generate a unique alphanumeric name (e.g., "A1B2C3D4E5")
                    $tenantName = Str::random(10);
                } while (Tenant::where('name', $tenantName)->exists()); // Check if name already exists

                $tenant = Tenant::create([
                    'name' => $tenantName, // Unique alphanumeric name
                    'domain' => $tenantName . '.payflow.dev' // Ensure this is correct for subdomain handling
                ]);

                // Assign the generated tenant_id to the staff
                $staff->tenant_id = $tenant->id;
            }
        });
    }
}