<?php

namespace alinemone\likeable\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeCounter extends Model
{
    use HasFactory;

    protected $table = 'like_counters';
    public $timestamps = false;
    protected $fillable = ['likeable_id', 'likeable_type', 'count'];

    public function likeable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Delete all counts of the given model, and recount them and insert new counts
     *
     * @param $modelClass
     * @throws Exception
     */
    public static function rebuild($modelClass): void
    {
        if(empty($modelClass)) {
            throw new Exception('$modelClass cannot be empty/null. Maybe set the $morphClass variable on your model.');
        }

        $builder = Like::query()
            ->select(\DB::raw('count(*) as count, likeable_type, likeable_id'))
            ->where('likeable_type', $modelClass)
            ->groupBy('likeable_id');

        $results = $builder->get();

        $inserts = $results->toArray();

        \DB::table((new static)->table)->insert($inserts);
    }
}
