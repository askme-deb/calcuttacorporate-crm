<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ListOfDocument extends Model
{
        protected $fillable = [
        'name',
        'parent_id',
    ];

    /**
     * Get the parent document (if any).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ListOfDocument::class, 'parent_id');
    }

    /**
     * Get the child documents.
     */
    public function children(): HasMany
    {
        return $this->hasMany(ListOfDocument::class, 'parent_id');
    }

     public function works()
    {
        return $this->belongsToMany(WorkMaster::class, 'document_work_master', 'list_of_document_id', 'work_master_id')->withTimestamps();
    }
}
