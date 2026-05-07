<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class DoctorPost extends Model
{
    // protected $connection = 'tenant';
    protected $table = 'doctor_posts';

    protected $fillable = [
        'user_id','title','slug','category_id','type_id','cover_image','excerpt','body',
        'read_minutes','published_at','is_published',
        'meta_title','meta_description','meta_keywords','order_column','related_post_ids'
    ];

    protected $casts = [
        'meta_keywords' => 'array',
        'related_post_ids' => 'array',
        'published_at'  => 'datetime',
        'is_published'  => 'boolean',
    ];

    /* Scopes */
    public function scopePublished($q)
    {
        return $q->where('is_published', true)
                 ->whereNotNull('published_at')
                 ->where('published_at', '<=', now());
    }

    /* Relations */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function type()
    {
        return $this->belongsTo(PostType::class, 'type_id');
    }

    public function getRelatedPostsAttribute()
    {
        $ids = collect($this->related_post_ids)->filter()->map(fn ($id) => (int) $id)->values();

        if ($ids->isEmpty()) {
            return collect();
        }

        $posts = static::published()
            ->with(['category', 'type'])
            ->whereIn('id', $ids)
            ->where('id', '!=', $this->id)
            ->get()
            ->keyBy('id');

        return $ids->map(fn ($id) => $posts->get($id))->filter()->values();
    }

    /* Accessors */
    public function readTime(): Attribute
    {
        return Attribute::get(function () {
            if ($this->read_minutes) return $this->read_minutes;
            // fallback: rough estimate from body (200 words/min)
            $words = str_word_count(strip_tags((string)$this->body));
            return max(1, (int) ceil($words / 200));
        });
    }

    /* Helpers */
    public static function makeSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }
}
