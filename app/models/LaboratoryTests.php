<?php


namespace App\Model;

use App\Helpers\FieldHelper;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;

class LaboratoryTests extends DataObject{

    private static $table_name = 'LaboratoryTests';

    private static $db = [
        'Name' => 'Varchar(255)',
        'SortOrder' => 'Int',
    ];

    private static $has_one = [
    ];

    private static $many_many = [
    ];

    private static $indexes = [
    ];

    private static $default_sort = 'SortOrder';

    private static $defaults = [];

    private static $summary_fields = array(
        'Name' => 'Name',
    );

    public function getCMSFields(){
        $fields = parent::getCMSFields();
        $fields->removeByName(['SortOrder']);

        return $fields;
    }

    public function onAfterWrite(){
        parent::onAfterWrite();
    }

}
