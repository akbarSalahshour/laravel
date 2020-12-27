<?php

namespace App;

use Illuminate\Database\Eloquent\{
    Model,
    Relations\BelongsTo,
};

class Article extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'title','description','visited',
    ];
    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
    public function category():BelongsTo{
        return $this->belongsTo(Category::class);
    }
}
