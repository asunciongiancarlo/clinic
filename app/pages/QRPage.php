<?php

use SilverStripe\Control\Controller;
use SilverStripe\View\Requirements;
use App\Model\Patients;
use SilverStripe\Security\Security;


class QRPage extends Page{

    private static $db = [
    ];

    private static $has_many = [
    ];

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        return $fields;
    }

    public function memberLogIn()
    {
        $member = Security::getCurrentUser();

        if($member){
            return true;
        }else{
            return false;
        }
    }

    public function getPatient()
    {
        if(isset($_GET['patient_id'])){
            return Patients::get()->filter(['UniqueID'=>$_GET['patient_id']])->first();
        }

        return false;
    }

}

class QRPageController extends PageController{

    private static $allowed_actions = ['verify'];

    public $IndependentRequirements = true;

    public function init(){

        parent::init();
        Requirements::clear();

        $css = [
            '/css/app.css'
        ];

        Requirements::combine_files($this->dataRecord->ClassName . '.css', $css);

        $js = [
            '/js/jquery-3.6.0.slim.min.js',
            '/js/app.js',
            '/js/jquery.qrcode.js',
            '/js/qrcode.js',
            //'/js/qr.js',
        ];
        Requirements::combine_files($this->dataRecord->ClassName . '.js', $js);

    }

    public function index()
    {
        return $this;
    }


}
