<?php
namespace App\Helpers;

use SilverStripe\Control\Director;

class GeneralHelper{
	
	public static function CamelName($name = null){
		if($name){
			$CamelCases = preg_split('/(?=[A-Z])/', $name);
			$ArrayTitles = array_filter($CamelCases, function($value) { return $value !== ''; });
			return implode(' ', $ArrayTitles);
		}
		else{
			return false;
		}
	}

	public static function GetExtendedClass($pclass = false){
		$children  = array();
		if($pclass){
			foreach( get_declared_classes() as $class ){
				if(is_subclass_of( $class, 'SilverStripe\Forms\Form'))
					$children[] = $class;
			}
		}
		return $children;
	}

	public static function CleanMobileNumber($MobileNumber=''){
		if (is_numeric($MobileNumber) AND $MobileNumber != '') {
			$country_code = '61';
			$trim_number = str_replace(' ', '', trim($MobileNumber));
			$code_part = substr($trim_number, 0, 4);
			if ($code_part === '0061') {
				$mobile = substr_replace($trim_number, '+', 0, 2);
			} elseif (substr($trim_number, 0, 1) === '0') {
				$mobile = substr_replace($trim_number, '+' . $country_code, 0, ($trim_number[0] == '0'));
			}
			if (isset($mobile)) return $mobile;
		}
		return $MobileNumber;
	}

	public static function checkDeviceAgent() {
		$tablet_browser = 0;
		$mobile_browser = 0;

		if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
			$tablet_browser++;
		}

		if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
			$mobile_browser++;
		}

		if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) OR ((isset($_SERVER['HTTP_X_WAP_PROFILE']) OR isset($_SERVER['HTTP_PROFILE'])))) {
			$mobile_browser++;
		}

		$mobile_ua = trim(strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4)));
		$mobile_agents = [
			'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
			'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
			'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
			'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
			'newt','noki','palm','pana','pant','phil','play','port','prox',
			'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
			'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
			'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
			'wapr','webc','winw','winw','xda ','xda-'
		];

		if (in_array($mobile_ua, $mobile_agents)) {
			$mobile_browser++;
		}

		if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
			$mobile_browser++;
			// Check for tablets on opera mini alternative headers
			$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
			if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
				$tablet_browser++;
			}
		}

		if ($tablet_browser > 0) {
			// do something for tablet devices
			return 'tablet_';
		} else if ($mobile_browser > 0) {
			// do something for mobile devices
			return 'mobile_';
		} else {
			// do something for everything else
			return '';
		}
	}

	public static function br_2_nl($text = '') {
		$text = preg_replace("/&nbsp;/", ' ', $text);
		$breaks = array("<br />","<br>","<br/>");  
		return str_ireplace($breaks, "\r\n", $text);
	}

    public static function log($file, $msg = ''){

        $dir = Director::baseFolder() . '/logs/';

        if ( !file_exists( $dir ) && !is_dir( $dir ) ) {
            mkdir( $dir );
        }
        //Log to CRM
        $myfile = fopen($dir . $file, "a+");
        $txt = "Date: " . Date('Y-m-d H:i:s') . "\n";
        $txt .= "IP: " . @$_SERVER['REMOTE_ADDR'] . " \n";
        $txt .= "URI: " . @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'] . " \n";
        $txt .= "Message: " . $msg . " \n";
        $txt .= "--------------------------------" . "\n";

        fwrite($myfile, $txt);
        fclose($myfile);
    }
}