<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProfileViews extends Model
{
    /**
     * @var string
     */
    public $table = 'profile_views';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'viewer_id'
    ];

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function viewer() : HasOne
    {
        return $this->hasOne(User::class, 'viewer_id', 'id');
    }
}