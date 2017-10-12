<?
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
class RBS_Payment extends CModule 
{
	const MODULE_ID = 'rbs.payment';
	var $MODULE_ID = 'rbs.payment';
	var $module_name;
	var $module_description;
	var $partner_name;
	var $partner_uri;

	function rbs_payment() 
	{
		require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/rbs.payment/config.php");
		
		$module_name = GetMessage('RBS_PAYMENT_MODULE_NAME');
		$module_description = $mess['module_description'];
		$partner_name = $mess['partner_name'];
		$partner_uri = $mess['partner_uri'];
		$this->MODULE_NAME = $module_name;
		/**
		 * Выбор кодировки в зависимости от payment/settings
		 */
		if(ENCODING) {
// 			$this->MODULE_NAME = $module_name;
			$this->MODULE_DESCRIPTION = $module_description;
			$this->PARTNER_NAME = $partner_name;
			$this->PARTNER_URI = $partner_uri;
		} else {
// 			$this->MODULE_NAME = iconv("utf-8", "windows-1251", $module_name);
			$this->MODULE_DESCRIPTION = iconv("utf-8", "windows-1251", $module_description);
			$this->PARTNER_NAME = iconv("utf-8", "windows-1251", $partner_name);
			$this->PARTNER_URI = iconv("utf-8", "windows-1251", $partner_uri);
		}
		
		$this->MODULE_VERSION = VERSION;
		$this->MODULE_VERSION_DATE = VERSION_DATE;
	}
	
	function InstallFiles($arParams = array()) 
	{
		CopyDirFiles(
			$_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/rbs.payment/install/sale_payment/payment/",
			$_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/include/sale_payment/payment/"
		);
		CopyDirFiles(
			$_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/rbs.payment/install/sale/payment",
			$_SERVER["DOCUMENT_ROOT"]."/sale/payment/"
		);
		CopyDirFiles(
			$_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/rbs.payment/ajax.php',
			$_SERVER['DOCUMENT_ROOT'].'/rbs.payment/ajax.php'
		);
	}

	function UnInstallFiles() 
	{
		DeleteDirFilesEx("/bitrix/php_interface/include/sale_payment/payment");
		DeleteDirFilesEx("/sale/payment/");
		DeleteDirFilesEx("/rbs.payment/ajax.php");
	}

	function DoInstall() 
	{
		$this->InstallFiles();
		RegisterModule(self::MODULE_ID);
	}

	function DoUninstall() 
	{
		UnRegisterModule(self::MODULE_ID);
		$this->UnInstallFiles();
	}
}