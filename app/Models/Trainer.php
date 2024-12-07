<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Trainer extends Model
{
    use HasFactory;

    public function User_Register(){
        return $this->belongsTo(User_register::class, 'id_user_resgisters');
    }
    public function Apprentice(){
        return $this->hasMany('App\Models\Apprentice');
    }
    public function Followup(){
        return $this->hasMany('App\Models\Followup');
    }
    public function Log(){
        return $this->hasMany('App\Models\Log');
    }

    protected $fillable = ['number_of_monitoring_hours', 'month', 'number_of_trainees_assigned','network_knowledge','start_date','end_date', 'id_user_register'];

    protected $allowIncluded = ['User_Register','Apprentice','Followup','Log'];
    protected $allowFilter = ['id', 'number_of_monitoring_hours', 'month', 'number_of_trainees_assigned','network_knowledge','start_date','end_date', 'id_user_register'];
    protected $allowSort = ['id', 'number_of_monitoring_hours', 'month', 'number_of_trainees_assigned','network_knowledge','start_date','end_date', 'id_user_register'];

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

        foreach ($filters as $filter => $value) {
            if ($allowFilter->contains($filter)) {
                $query->where($filter, 'LIKE', '%' . $value . '%');
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

    public function scopeGetOrPaginate(Builder $query)
    {
        if (request('perPage')) {
            $perPage = intval(request('perPage'));

            if ($perPage) {
                return $query->paginate($perPage);
            }
        }
        return $query->get();
    }
}
