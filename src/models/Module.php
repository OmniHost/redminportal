<?php namespace Redooor\Redminportal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Module extends Model {

    public function category()
    {
        return $this->belongsTo('Redooor\Redminportal\Category');
    }

    public function pricelists()
    {
        return $this->hasMany('Redooor\Redminportal\Pricelist');
    }

    public function images()
    {
        return $this->morphMany('Redooor\Redminportal\Image', 'imageable');
    }

    public function tags()
    {
        return $this->morphMany('Redooor\Redminportal\Tag', 'tagable');
    }
    
    public function translations()
    {
        return $this->morphMany('Redooor\Redminportal\Translation', 'translatable');
    }

    public function deleteAllImages()
    {
        $folder = 'assets/img/modules/';

        foreach ($this->images as $image)
        {
            // Delete physical file
            $filepath = $folder . $image->path;

            if( File::exists($filepath) ) {
                File::delete($filepath);
            }

            // Delete image model
            $image->delete();
        }
    }

    public function deleteAllTags()
    {
        foreach ($this->tags as $tag)
        {
            $tag->delete();
        }
    }
    
    public function delete()
    {
        // Delete all translations
        $this->translations()->delete();

        return parent::delete();
    }

}
