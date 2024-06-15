<?php
	namespace App\Http\Controllers\client;

	use App\Http\Controllers\Controller;
	use App\Constant\Constant;
	use App\DB\Menu;
	use Log;

	class CMenuController extends Controller
	{
		function get_menus($sMode)
		{
			$aMenus = Menu::where(['IsDeleted'	=> Constant::DB_FALSE,
									'Status'	=> Constant::MENU_STATUS_PUBLISH])->get();

			$oConstant = new Constant();
			foreach ($aMenus as $nMenuKey => $aMenu)
			{
				$aMenus[$nMenuKey]['StatusStr'] = $oConstant->get_menuStatusStr($aMenu['Status']);
				$aMenus[$nMenuKey]['ImageSrc'] = $aMenu['Image'] != '' ? constant::MENU_IMAGE_PATH . $aMenu['Image'] : constant::COMMON_IMAGE_PATH . 'no-image.jpg';
			}

			$aData =
			[
				'menus'		=> $aMenus,
			];

			return view('client.' . $sMode, $aData);
		}

	}

?>
