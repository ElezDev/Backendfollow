<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Message extends Model
{
    use HasFactory;

    public function User_register(){
        return $this->belongsTo('App\Models\User_register');
    }

    protected $fillable = ['message', 'description','id_user_register'];
    protected $allowIncluded = ['User_register'];
    protected $allowFilter = ['id', 'message', 'description','id_user_register'];
    protected $allowSort = ['id', 'message', 'description','id_user_register'];
    
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

        // Ejecutamos el query con las relaciones permitidas
        $query->with($relations);
    }
     //return $relations;
    // return $this->allowIncluded;
    public function scopeFilter(Builder $query)
    {

        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);

        foreach ($filters as $filter => $value) {

            if ($allowFilter->contains($filter)) {

                $query->where($filter, 'LIKE', '%' . $value . '%');//nos retorna todos los registros que conincidad, asi sea en una porcion del texto
            }
        }
    }
    public function scopeSort(Builder $query)
{
    if (empty($this->allowSort) || empty(request('sort'))) {
        return; // Salimos si no hay nada que ordenar
    }


    $sortFields = explode(',', request('sort'));

    // Colocamos en una colección lo que tiene $allowSort
    $allowSort = collect($this->allowSort);

    foreach ($sortFields as $sortField) {

        $direction = 'asc';

        if (substr($sortField, 0, 1) === '-') {
            $direction = 'desc';
            $sortField = substr($sortField, 1);
        }

        // Si el campo de ordenamiento está permitido, lo agregamos a la consulta
        if ($allowSort->contains($sortField)) {
            $query->orderBy($sortField, $direction); // Ejecutamos la query con la dirección deseada
        }
    }


}

public function scopeGetOrPaginate(Builder $query) {
    if (request('perPage')) {
        $perPage = intval(request('perPage'));

        if ($perPage) {
            return $query->paginate($perPage);
        }
    }
    return $query->get();
}



}
