<?php

namespace App\Helpers;

use SilverStripe\Forms\TextField;
use SilverStripe\Forms\ListboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\HiddenField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\TimeField;
use SilverStripe\Forms\FileField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Forms\HeaderField;
use App\Helpers\GeneralHelper;
use SilverStripe\Forms\SelectionGroup;
use SilverStripe\Dev\Debug;
use SilverStripe\Dev\Backtrace;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\NumericField;

use SilverStripe\ORM\SS_List;
use SilverStripe\Assets\Upload;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\PasswordField;

class FieldHelper{

	public static function Required($array = array()){
		return new RequiredFields($array);
	}

	public static function Text($name = null, $title = null, $description = null){
	    if($description)
            return TextField::create($name, $title ?: GeneralHelper::CamelName($name))->setDescription($description);

		return TextField::create($name, $title ?: GeneralHelper::CamelName($name));
	}

	public static function Dropdown($name = null, $title = null, $list = null, $template_name = 'Default'){
		if ($template_name == false) {
			return DropdownField::create($name, $title ?: GeneralHelper::CamelName($name), $list);
		} else {
			return DropdownField::create($name, $title ?: GeneralHelper::CamelName($name), $list)->setEmptyString($template_name);
		}
	}

	public static function Upload($name = null, $title = null, $description = null, $baseFolder = null, SS_List $items = null){
		if ($items instanceof SS_List) {
	    	$field = UploadField::create($name, $title ?: GeneralHelper::CamelName($name), $items);
		} else {
	    	$field = UploadField::create($name, $title ?: GeneralHelper::CamelName($name));
		}

		if ($baseFolder != null) {
	    	$Upload = Upload::create();
	    	$field->setUpload($Upload);
	    	$field->setFolderName($baseFolder);
		}

	    if($description) {
	        $field->setDescription($description);
	    }

		return $field;
	}

	public static function file($name = null, $title = null, $description = null){
		return new FileField($name, $title, $description);
	}

	public static function Accordion(&$fields, $name = null, $title = null, $list = array(), $heading_level = 4){
		foreach($list as $field){
			$fields->removeByName($field->Name);
		}
		return ToggleCompositeField::create($name, $title ?: GeneralHelper::CamelName($name), $list)->setHeadingLevel($heading_level);
	}

	public static function ListBox($name, $title, $list, $value = false){
		return ListboxField::create($name, $title ?: GeneralHelper::CamelName($name), $list, $value);
	}

    public static function OptionsetField($name, $title, $list, $value = false){
        return OptionsetField::create($name, $title ?: GeneralHelper::CamelName($name), $list, $value);
    }

	public static function TreeDropdown($name = null, $title = null, $class = null){
		return TreeDropdownField::create($name, $title ?: GeneralHelper::CamelName($name), $class)->setEmptyString('(Select one)');
	}

	public static function Checkbox($name = null, $title = null, $value = null){
		return CheckboxField::create($name, $title ?: GeneralHelper::CamelName($name), $value);
	}

	public static function HTMLEditor($name = null, $title = null, $object = null){
		$default_rows = 5;
		/*set the rows base on the content*/
		if (!is_null($object)) {
			if (trim($object->{$name}) != '') {
				$breaks = ["/>","<br>","</"];
				${$name} = str_ireplace($breaks, "\r\n", $object->{$name});
				$rows = substr_count(strip_tags(${$name}), "\n");
				if ($rows > 20 OR strlen($object->{$name}) > 100) {
					$default_rows = (ceil(strlen($object->{$name}) / 80)) + 1;
				} else {
					$default_rows = $rows;
				}
				if ($default_rows <= 5) $default_rows += 3;
			}
		}
		return HTMLEditorField::create($name, $title ?: GeneralHelper::CamelName($name))->setRows($default_rows);
	}

	public static function Textarea($name = null, $title = null, $object = null){
		$default_rows = 4;
		/*set the rows base on the content*/
		if (!is_null($object)) {
			if (trim($object->{$name}) != '') {
				$rows = substr_count($object->{$name}, PHP_EOL);
				if ($rows >= 0 OR strlen($object->{$name}) > 100) {
					$default_rows = (ceil(strlen($object->{$name}) / 80)) + 1;
				} elseif ($rows >= 0) {
					$default_rows = $rows + 1;
				}
			}
		}
		return TextareaField::create($name, $title ?: GeneralHelper::CamelName($name))->setRows($default_rows);
	}

	public static function Hidden($name = null, $title = null){
		return HiddenField::create($name, $title ?: GeneralHelper::CamelName($name));
	}

	public static function Date($name = null, $title = null){
		return DateField::create($name, $title ?: GeneralHelper::CamelName($name));
	}

	public static function Time($name = null, $title = null){
		return TimeField::create($name, $title ?: GeneralHelper::CamelName($name));
	}

	public static function HTMLText($value = ''){
		return DBHTMLText::create()->setValue($value);
	}

    public static function HeaderField($name = null, $title = null, $level=4){
        return HeaderField::create($name, $title ?: GeneralHelper::CamelName($name), $level);
    }

    public static function SelectionGroup($name = null, $title = null){
        return SelectionGroup::create($name, $title ?: GeneralHelper::CamelName($name));
    }

	public static function Input($field_type='text', $name = null, $title = null, $description = null){
		switch ($field_type) {
			case 'email':
			$InputField = EmailField::create($name, $title ?: GeneralHelper::CamelName($name));
			break;

			case 'number':
			$InputField = NumericField::create($name, $title ?: GeneralHelper::CamelName($name))->setHTML5(true);
			break;

			case 'password':
			$InputField = PasswordField::create($name, $title ?: GeneralHelper::CamelName($name));
			break;

			default: /*text*/
			$InputField = TextField::create($name, $title ?: GeneralHelper::CamelName($name));
			break;
		}

		if($description) {
			$InputField->setDescription($description);
		}
		return $InputField;
	}

	public static function Password($name = null, $title = null, $placeholder = false){
		$input = PasswordField::create($name, $title ?: GeneralHelper::CamelName($name));
		if($placeholder){
			$input->setAttribute('placeholder', $placeholder);
		}

		return $input;
	}

    public static function Wrap($field = null){
        return Wrapper::create($field);
    }

	public static function RedirectLinkFields(&$fields, $root_tab='Root.Main', $before_field=null, $newtab_value=0){
		/*NOTE: 'RedirectLink', 'RedirectType', 'PageID', 'ExternalLink', 'ButtonText', 'NewTab' must be declared in db of this Page*/
		if (!is_null($fields)) {
			$fields->removeByName(['RedirectLink', 'RedirectType', 'PageID', 'ExternalLink', 'ButtonText', 'NewTab']);

			$fields->addFieldsToTab($root_tab, [
				HeaderField::create('RedirectLink', 'Redirect Link', 4),
				SelectionGroup::create('RedirectType', [
					"Page" => TreeDropdownField::create('PageID', '', SiteTree::class)->setEmptyString('(Select Page)'),
					"External" => TextField::create('ExternalLink', '')->setAttribute('placeholder', 'Address URL')
				]),
				CheckboxField::create('NewTab', 'Open in New Tab?', $newtab_value),
				TextField::create('ButtonText', 'Button Text')->setDescription('Leave it <strong>BLANK</strong> for no button show')
			], $before_field);
		}
	}

}
