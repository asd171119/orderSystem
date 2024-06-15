<?php
	namespace App\Http\Controllers;

	use App\Http\Controllers\Controller;
	use App\Constant\Constant;
	use App\DB\Menu;
	use Validator;
	use Image;
	use Log;

	class MenuController extends Controller
	{
		function get_menus()
		{
			$aMenus = Menu::where('IsDeleted', Constant::DB_FALSE)->get();

			$oConstant = new Constant();
			foreach ($aMenus as $nMenuKey => $aMenu)
			{
				$aMenus[$nMenuKey]['StatusStr'] = $oConstant->get_menuStatusStr($aMenu['Status']);
				$aMenus[$nMenuKey]['ImageSrc'] = $aMenu['Image'] != '' ? constant::MENU_IMAGE_PATH . $aMenu['Image'] : constant::COMMON_IMAGE_PATH . 'no-image.jpg';
			}

			$aData =
			[
				'mode'		=> 'font',
				'page'		=> 'menu_manger',
				'menus'		=> $aMenus,
			];

			return view('font.menu_manger', $aData);
		}

		function get_menu()
		{
			$sMenuToken = isset($_GET['token']) ? $_GET['token'] : '';

			$aMenu = Menu::where('Token', $sMenuToken)->where('IsDeleted', Constant::DB_FALSE)->first();

			$oConstant = new constant();
			if(!empty($aMenu))
			{
				$aMenu->ImageSrc = $aMenu['Image'] != '' ? constant::MENU_IMAGE_PATH . $aMenu['Image'] : constant::COMMON_IMAGE_PATH . 'no-image.jpg';
			}
			else
			{
				$aMenu = new Menu();

				$aMenu->ImageSrc = constant::COMMON_IMAGE_PATH . 'no-image.jpg';
				$aMenu->Image = '';
				$aMenu->Status = constant::MENU_STATUS_PUBLISH;
			}

			$aData =
			[
				'mode'				=> 'font',
				'page'				=> 'menu_manger',
				'menu'				=> $aMenu,
				'token'				=> $sMenuToken,
				'statusOptions'		=> $oConstant->get_menuStatusStr('All')
			];

			return view('font.menu_manger_edit', $aData);
		}

		function edit_menu()
		{
			$aParams = request()->all();

			// process image file
			if(isset($aParams['Image']) && gettype($aParams['Image']) == 'object')
			{
				$oImage = $aParams['Image'];
				$sExt = $oImage->getClientOriginalExtension();
				$sServerName = uniqid() . '.' . $sExt;
				$sDir = public_path(constant::MENU_IMAGE_PATH . $sServerName);
				$oImage = Image::make($oImage)->fit(300,300)->save($sDir);

				$aParams['Image'] = $sServerName;
			}

			if($aParams['Token'] == '')
			{
				$aParams['Token'] = $this->create_token(Menu::class, 'menu_');
				$aParams['OnSale'] = $aParams['OnSale'] == null ? 0 : $aParams['OnSale'];
				$aParams['Image'] = $aParams['Image'] == null ? '' : $aParams['Image'];

				Menu::create($aParams);
			}
			else
			{
				$aMenu = Menu::firstwhere('Token', $aParams['Token']);

				$aMenu->Name	= $aParams['Name'];
				$aMenu->Price	= $aParams['Price'];
				$aMenu->OnSale	= $aParams['OnSale'] != '' ? $aParams['OnSale'] : 0;
				$aMenu->Status	= $aParams['Status'];
				$aMenu->Image	= isset($aParams['Image']) ? $aParams['Image'] : '';

				$aMenu->save();
			}

			return redirect('/font/menu_manger');
		}

		function delete_menu()
		{
			$sMenuToken = isset($_GET['token']) ? $_GET['token'] : '';

			$aMenu = Menu::firstwhere('Token', $sMenuToken);

			$aMenu->IsDeleted = Constant::DB_TRUE;
			$aMenu->save();

			return redirect('/font/menu_manger');
		}

		function delete_menu_image()
		{
			$sMenuToken = isset($_GET['token']) ? $_GET['token'] : '';

			$aMenu = Menu::firstwhere('Token', $sMenuToken);

			unlink(public_path(Constant::MENU_IMAGE_PATH) . $aMenu->Image);

			$aMenu->Image = '';
			$aMenu->save();

			return redirect('/font/menu_manger_edit?token=' . $sMenuToken);
		}
	}

?>
