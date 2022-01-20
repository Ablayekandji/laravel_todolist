<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    //
    protected $fillable = [
        'name', 'description', 'done'
    ];

    public function user()
    {
        # code...
        return $this->belongsTo('App\User', 'creator_id');
    }
    /**
     * recuperer id de  l'utilisateur a affecter le todo
     */

    public function todoAffectedTo()
    {
        # code...
        return $this->belongsTo('App\User', 'affectedTo_id');
    }

    /**
     * recuperer id de  l'utilisateur qui affecte le todo
     */

    public function todoAffectedBy()
    {
        # code...
        return $this->belongsTo('App\User', 'affectedBy_id');
    }
}
