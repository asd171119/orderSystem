<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\DB\Table;
use Log;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	function create_token($oDB, $sPrefix)
	{
		$sToken = $this->generateUUID($sPrefix);

		$aData = $oDB::where('Token', $sToken)->get();

		if(count($aData) > 0)
		{
			$this->create_token($oDB, $sPrefix);
			return;
		}

		return $sToken;

	}

	function generateUUID($sPrefix = '')
	{
		$sStr = md5(uniqid(mt_rand(), true));
		$sUuid  = substr($sStr, 0, 8);
		$sUuid .= substr($sStr, 8, 4);
		$sUuid .= substr($sStr, 12, 4);
		$sUuid .= substr($sStr, 16, 4);
		$sUuid .= substr($sStr, 20, 12);

		return $sPrefix . $sUuid;
	}

	function switch()
	{
		$sMode = request('mode');
		$nType = request('type');

		switch ($sMode)
		{
			case 'back':
				return redirect('/back/order?mode=' . $sMode . '&type=' . $nType);
				break;

			default:
				return redirect('/font/order_manger?mode=' . $sMode);
				break;
		}
	}

	function key_array($aDatas, $sKey, $sMode = 'single')
	{
		$aReturnData = array();
		foreach ($aDatas as $nkey => $aData)
		{
			switch ($sMode)
			{
				case 'single':
					$aReturnData[$aData[$sKey]] = $aData;
					break;

				case 'multi':
					if(!isset($aReturnData[$aData[$sKey]])) $aReturnData[$aData[$sKey]] = array();
					array_push($aReturnData[$aData[$sKey]], $aData);
					break;
			}
		}

		return $aReturnData;
	}

}
