<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Companion extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = 'companions';
    protected $guarded = [];

    public function visit()
    {
        return $this->belongsTo(VisitingDetails::class, 'visit_id', 'id');
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function registerMediaCollections(): void
    {
        // TODO: Implement registerMediaCollections() method.
        $this->addMediaCollection('companion')
            ->singleFile();
    }

    public function getImagesAttribute()
    {
        if (!empty($this->getFirstMediaUrl('companion'))) {
            return asset($this->getFirstMediaUrl('companion'));
        }
        return asset('assets/img/default/user.png');
    }
}
