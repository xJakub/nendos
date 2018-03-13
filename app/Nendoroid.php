<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Nendoroid
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $number
 * @property string $name
 * @property string $series
 * @property string $release_date
 * @property string $announcement_date
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nendoroid whereAnnouncementDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nendoroid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nendoroid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nendoroid whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nendoroid whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nendoroid whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nendoroid whereSeries($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nendoroid whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $official_url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nendoroid whereOfficialUrl($value)
 * @property string $slug
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nendoroid findSimilarSlugs($attribute, $config, $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Nendoroid whereSlug($value)
 */
class Nendoroid extends Model
{
    use Sluggable;

    public function getImagePath($index) {
        $slug = str_slug($this->number);
        return "img/nendoroids/{$slug}/{$index}.jpg";
    }

    public function getAvailableImages() {
        $slug = str_slug($this->number);
        $files = glob(base_path() . "/public/img/nendoroids/{$slug}/*.jpg");
        $result = [];
        foreach ($files as $file) {
            $result[] = basename($file, '.jpg');
        }
        return $result;
    }

    public function getLocalImagePath($index) {
        $path = $this->getImagePath($index);
        return base_path(). "/public/{$path}";
    }

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'number'
            ]
        ];
    }

    public function getRouteKeyName() {
        return 'slug';
    }

    public function getCleanedName() {
        $name = $this->name;
        $name = str_replace('Nendoroid ', '', $name);
        $name = preg_replace("' \([^\)]+\)$'", '', $name);
        return $name;
    }

    public function getLink() {
        $nameSlug = str_slug($this->getCleanedName());
        return route('nendoroid', [$this, $nameSlug]);
    }
}
