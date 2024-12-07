<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Apprentice extends Model
{
    use HasFactory;
    public function User_register(){
        return $this->belongsTo('App\Models\User_register');
    }
    public function Contract(){
        return $this->belongsTo('App\Models\Contract');
    }
    public function Trainer(){
        return $this->belongsTo('App\Models\Trainer');
    }
    public function Log(){
        return $this->hasMany('App\Models\Log');
    }

    protected $fillable = ['id', 'academic_level', 'program', 'ficha', 'id_user_register', 'id_contract','id_trainer'];

    protected $allowIncluded = ['User_register', 'Contract', 'Trainer','Log'];

    protected $allowFilter = ['id', 'academic_level', 'program', 'ficha', 'id_user_register', 'id_contract','id_trainer'];

    protected $allowSort = ['id', 'academic_level', 'program', 'ficha', 'id_user_register', 'id_contract','id_trainer'];

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


