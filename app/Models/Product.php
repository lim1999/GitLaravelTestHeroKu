<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use CrudTrait;
    use SoftDeletes;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'products';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    protected $fillable = ['name','rent_price','list_price','sold_price','sale_price'];
    // protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function categories(){
        return $this->belongsTo(\App\Models\Category::class,'category_id');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getCodeAttribute(){
        return '0000'.$this->id;
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setImageAttribute($value)
    {
        $attribute_name = "image";
        $disk = "uploads";
        $destination_path = "category-profiles";
        if ($value==null) {
            \Storage::disk($disk)->delete($this->{$attribute_name});
            $this->attributes[$attribute_name] = null;
        }
        if (starts_with($value, 'data:image')){
            $image = \Image::make($value)->encode('jpg', 90);
            $filename = md5($value.time()).'.jpg';
            \Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());
            $this->attributes[$attribute_name] = $destination_path.'/'.$filename;
        }
    }
}
