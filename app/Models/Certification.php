<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\DeletingCertificationEvent;

class Certification extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        //'deleting' => DeletingEmployeeEvent::class,
    ];
    
    /**
     * Scope a query to only include active employees.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get the user details associated with the history
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    /**
     * Get the address details associated with the history
     */
    public function address()
    {
        return $this->belongsTo('App\Models\Address','address_id');
    }

    /**
     * Get the certificate details associated with the history
     */
    public function certificate()
    {
        return $this->belongsTo('App\Models\Certificate','certificate_id');
    }

    /**
     * Get the authority details associated with the history
     */
    public function authority()
    {
        return $this->belongsTo('App\Models\Authority','authority_id');
    }

    /**
     * Get the students details associated with the history
     */
    public function students()
    {
        return $this->belongsToMany('App\Models\Student', 'certification_detail')->as('certificationDetails');
    }
}
