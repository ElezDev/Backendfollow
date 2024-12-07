<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Followup extends Model
{
    public function Trainer(){
        return $this->belongsTo('App\Models\Trainer');
    }
    use HasFactory;
    protected $fillable = [
        'type_of_agreement',
        'date',
        'name_of_immediate_boss',
        'email',
        'telephone',
        'observation',
        'id_trainer' // Corrigiendo el nombre del campo
    ];

    protected $allowIncluded = ['Trainer'];

    protected $allowFilter = ['id',  'type_of_agreement',
    'date',
    'name_of_immediate_boss',
    'email',
    'telephone',
    'observation',
    'id_trainer'];

    protected $allowSort = ['id', 'type_of_agreement',
    'date',
    'name_of_immediate_boss',
    'email',
    'telephone',
    'observation',
    'id_trainer'];

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

