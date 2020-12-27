<?php

namespace App;

use Illuminate\Database\Eloquent\{
    Model,
    Relations\BelongsTo,
    Relations\HasMany,
};

class Category extends Model
{
    protected $fillable = [
        'user_id','title',
    ];
    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
    public function articles(): HasMany{
        return $this->hasMany(Article::class);
    }
}
