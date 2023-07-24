<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganismOfAgreement extends Model
{
    use HasFactory;

    public static function create(OrganismOfAgreement $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(OrganismOfAgreement $model)
    {
        try{
            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function get_by_organism_id_agreement_id($organism_id, $agreement_id)
    {
        $model = OrganismOfAgreement::where([
            ['organism_id', '=', $organism_id],
            ['agreement_id', '=', $agreement_id],
            ['active', '=', true]
        ])->first();
        return $model;
    }

    public function organism()
    {
        return $this->belongsTo(Organism::class,'organism_id');
    }
}
