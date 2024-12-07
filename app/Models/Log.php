<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    public function Trainer(){
        return $this->belongsTo('App\Models\Trainer');
    }
    public function Apprentice(){
        return $this->belongsTo('App\Models\Apprentice');
    }
    protected $fillable = [
        'number_log',
        'description',
        'date',
        'observation',
        'id_trainer',
        'id_apprentice'
    ];

    protected $allowIncluded = ['Trainer','Apprentice'];

    protected $allowFilter = ['id', 'number_log',
        'description',
        'date',
        'observation',
        'id_trainer',
        'id_apprentice'];

    protected $allowSort = ['id','number_log',
        'description',
        'date',
        'observation',
        'id_trainer',
        'id_apprentice'];

    public function scopeIncluded(Builder $query)
    {
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return;
        }

        $relations = explode(',', request('included'));
        $allowIncluded = collect($this->allowIncluded);

        foreach ($relations as $key => $relationship) {
            if (!$allowIncluded->contains($relationship)) {
                unset($relations[$key]);
            }
        }

        $query->with($relations);
    }

    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $field => $value) {
            if ($allowFilter->contains($field)) {
                $query->where($field, $value);
            }
        }
    }

    public function scopeSort(Builder $query)
    {
        if (empty($this->allowSort) || empty(request('sort'))) {
            return;
        }

        $sortFields = explode(',', request('sort'));
        $allowSort = collect($this->allowSort);

        foreach ($sortFields as $sortField) {
            $direction = 'asc';

            if (substr($sortField, 0, 1) === '-') {
                $direction = 'desc';
                $sortField = substr($sortField, 1);
            }

            if ($allowSort->contains($sortField)) {
                $query->orderBy($sortField, $direction);
            }
        }
    }
}

