<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'news_id',
        'body',
    ];

    public function post(string $user_id, string $news_id, string $body)
    {
        Comment::create([
            'user_id' => $user_id,
            'news_id' => $news_id,
            'body' => $body,
        ]);
    }
}
