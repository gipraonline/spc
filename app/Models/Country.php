<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    protected $primaryKey = 'n_country_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'c_country_name',
        'c_iso_code_2',
        'c_iso_code_3',
        'c_address_format',
        'postcode_required',
        'status',
    ];
}
