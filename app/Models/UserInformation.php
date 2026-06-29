<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInformation extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_information';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'bio',
        'address',
        'contact_number',
        'gender',
        'birth_date'
    ];


    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}