<?php

namespace App\Model;

use Illuminate\Support\Str;

class Town extends Base
{
  protected $table = 'towns';
  protected $fillable = [ 'uuid', 'name', 'url', 'city_id'];
  
  public function city() {
    return $this->belongsTo(City::class, 'city_id');
  }
  
  public static function all_list($city_id=null, $column='city_id') {
    $items = self::orderBy('name', 'asc');
    if($city_id) {
      if($column=='uuid') {
        $items = $items->whereHas('city', function($q) use ($city_id){
          $q->where('uuid', $city_id);
        });
      }else {
        $items = $items->where($column, $city_id);
      }
    }
    
    return $items->get();
  }
  
  protected static function boot()
  {
    parent::boot();
    
    static::creating(function ($model) {
      if(!$model->url) {
        $model->url = Str::slug($model->name);
      }
    });
  }
  
}
