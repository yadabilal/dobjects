<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class File extends Model
{
  use SoftDeletes;
  const NO_FILE = 'NO_FILE';

  protected $table = 'files';
  protected $fillable = [
    'uuid', 'user_id', 'model_name', 'model_id',
    'path', 'mime_type', 'size','shorting',
    'extension', 'original_name'];

  protected static function boot()
  {
    parent::boot();
    static::creating(function ($model) {
      $model->uuid = (string) Str::uuid();
    });
  }
  // Receiver
  public function user() {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function url() {
      return url('uploads/'.$this->path);
  }

  public static function by_uuid($uuid) {
    return self::where('uuid', $uuid)->first();
  }

  public static function image_ext() {
    return [
      'jpg',
      'png',
      'jpeg',
      'JPG',
      'PNG',
      'JPEG'
    ];
  }
  public static function upload($files, $model=null) {
    try{
        $models = [];
        foreach ($files as $file) {
            $data['user_id'] = \auth()->id();
            $data['mime_type'] = $file->getMimeType();
            $data['size'] = $file->getSize();
            $data['extension'] = $file->getClientOriginalExtension();
            $data['original_name'] = $file->getClientOriginalName();
            $data['model_name'] = @get_class($model) ? : null;
            $path = @$model->store_path  ? : 'undefineds';
            $shorting = 1;

            if(@$model->id) {
               $lastFile =  File::where('model_id', $model->id)
                    ->where('model_name', get_class($model))
                    ->orderBy('shorting', 'desc')
                    ->first();
                $data['model_id'] = $model->id;
                $shorting = $lastFile ? $lastFile->shorting+1 : $shorting;
            }
            $data['shorting'] = $shorting;

            $data['path'] = $file->store($path,['disk' => 'public']);

            $models[] = self::create($data);
        }

      return $models;
    }catch (\Exception $e) {
      Log::info('Dosya Yükleme hatası ->'.$e->getMessage());
    }
  }

}
