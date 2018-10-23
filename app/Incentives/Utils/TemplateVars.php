<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Model;
use App\Incentives\Compute\Vars;

class TemplateVars extends Model
{
    protected $fillable = ['template_id','var_id'];



    /**
     * Returns associated client
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function var()
    {
      return $this->belongsTo(Vars::class);
    }
}
