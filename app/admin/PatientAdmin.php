<?php

namespace App\Admin;

use SilverStripe\Admin\ModelAdmin;

class PatientAdmin extends ModelAdmin
{
	private static $menu_title = 'Laboratory';

	private static $url_segment = 'patients-admin';

	private static $menu_icon = '';

	private static $managed_models = [];
}
