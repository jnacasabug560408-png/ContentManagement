<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = ['title', 'slug', 'body', 'user_id', 'category_id', 'status', 'published_at', 'views'];

   protected $casts = [
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'published_at' => 'datetime', 
    ];
        public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'content_tag');
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function approvedComments()
    {
        return $this->hasMany(Comment::class, 'post_id')->where('status', 'approved');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByAuthor($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}