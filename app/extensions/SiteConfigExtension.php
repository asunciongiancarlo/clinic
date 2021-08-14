<?php
namespace App\Extensions;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use App\Helpers\FieldHelper;
use SilverStripe\Assets\Image;
use SilverStripe\Dev\Debug;
use SilverStripe\Assets\File;

class SiteConfigExtension extends DataExtension
{
	private static $db = [
	];

	private static $has_one = [
	    'HomePageBanner' =>  File::class
	];

    public function updateCMSFields(FieldList $fields)
    {
		$fields->addFieldsToTab('Root.Main', [
            FieldHelper::Upload("HomePageBanner", "Banner"),
		]);
	}

}
