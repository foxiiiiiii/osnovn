<?php


namespace AmbExpress\ViewModels;


use NumberFormatter;

class CurrencyUpdateViewModel
{
	public static function GetCurrentValue( $currencyType = 'USD' )
	{
		$currency_codes = array('R01235'=>'USD', 'R01239'=>'EUR');
		//$currency_file = $site_dir.'inc/currency.xml';
//		if(file_exists($currency_file)) {
//			// проверим на старость файла с валютой и пересоздадим если > 12 часов
//			if (abs(time() - filemtime($currency_file)) > 43200) {
//				@unlink($currency_file);
//				exit;
//				$currency = file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp');
//				file_put_contents($currency_file, $currency);
//			}
//			$currency = file_get_contents($currency_file);
//		}
//		else {
        try {
            $currency = @file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp');
        }
        catch (Exception $e) {
            return '-';
        }

			//file_put_contents($currency_file, $currency);
		//}

		$val = '';
		$xmlObj = @simplexml_load_string($currency);
		// что-то прочиталось в струкутуру?
		if (!is_object($xmlObj))
		{
			// может файл кривой -- замочим его
//			if(file_exists($currency_file)) {
//				@unlink($currency_file);
//				// тут по идее надо что-то сделать...?
//
//			}
			return '-';
		}
		// ищем нужный курс
		foreach ($xmlObj->children() as $node)
		{
			$currId = (string)$node->attributes()['ID'];

			if(array_key_exists($currId, $currency_codes) && ($currency_codes[$currId] === $currencyType))
			{
				$val = (string)$node->Value;
				$val = str_replace(',', '.', $val);
				$val = number_format( (float) $val, 2);
				break;
			}

		}
		return $val;
	}
}