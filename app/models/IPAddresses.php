<?php

namespace App\Model;

use SilverStripe\ORM\DataObject;

class PatientFiles extends DataObject{

    private static $table_name    = 'PatientFiles';

    private static $db = [
        'Title' => 'Varchar(100)',
        'Year' => 'Varchar(10)'
    ];

    public function getCMSFields(){
        $fields = parent::getCMSFields();
        return $fields;
    }

}
