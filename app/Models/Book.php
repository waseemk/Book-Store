<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'isbn', 'author_id', 'genre_id', 'description', 'image', 'published', 'publisher_id', 'status'];

    protected $casts = [
        'published' => 'date:Y-m-d',
    ];

    /**
     * Get the author associated with the book.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
    /**
     * Get the genre associated with the book.
     */
    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }
    /**
     * Get the publisher associated with the book.
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }
}
