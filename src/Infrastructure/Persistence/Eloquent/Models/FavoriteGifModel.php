<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FavoriteGifModel extends Model
{
    protected $table = 'favorite_gifs';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'gif_id',
        'alias',
        'title',
        'url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
