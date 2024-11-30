<?php

namespace Payflow\Base\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Payflow\Jobs\SyncTags;
use Payflow\Models\Tag;

trait HasTags
{
    /**
     * Get the tags
     */
    public function tags(): MorphToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->morphToMany(
            Tag::class,
            'taggable',
            "{$prefix}taggables"
        )->withTimestamps();
    }

    public function syncTags(Collection $tags)
    {
        SyncTags::dispatch($this, $tags);
    }
}
