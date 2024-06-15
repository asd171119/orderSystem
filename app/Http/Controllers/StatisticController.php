<?php
	namespace App\Http\Controllers;

	use App\Http\Controllers\Controller;
	use App\Constant\Constant;
	use App\DB\Menu;
	use App\DB\Order;
	use App\DB\OrderDetail;
	use DateTime;
	use Log;

	class StatisticController extends Controller
	{
		function get_statistic()
		{
			$aParams = request()->all();

			$aStatistic = array();

			$sFrom = isset($aParams['from']) ? (new DateTime($aParams['from']))->format('Y-m-d') . ' 00:00:00' :(new DateTime('now'))->format('Y-m-') . '01 00:00:00';
			$sTill = isset($aParams['till']) ? (new DateTime($aParams['till']))->format('Y-m-d') . ' 23:59:59' :(new DateTime('now'))->format('Y-m-t') . ' 23:59:59';

			$aMenuTmps = Menu::all()->toArray();
			$aMenus = array();
			foreach ($aMenuTmps as $nMenuTmpKey => $aMenuTmp)
			{
				$aMenus[$aMenuTmp['id']] = $aMenuTmp;

				$aStatistic[$aMenuTmp['Name']]['count'] = 0;
				$aStatistic[$aMenuTmp['Name']]['percentage'] = 0;
			}

			$aOrderTmps = Order::where('Status', constant::ORDER_STATUS_FINISH)->get(['ID'])->toArray();
			$aOrders = array();
			foreach ($aOrderTmps as $nOrderTmpKey => $aOrderTmp)
			{
				array_push($aOrders, $aOrderTmp['ID']);
			}

			$aOrderDetails = OrderDetail::where([
				['Status', '=', constant::ORDER_DETAIL_STATUS_FINISH],
				['created_at', '>', $sFrom],
				['updated_at', '<', $sTill],
			])->whereIn('OrderID', $aOrders)->get()->toArray();

			$aOrderDetailAmount = array_column($aOrderDetails, 'Amount');
			$nTotalCount = array_sum($aOrderDetailAmount);

			foreach ($aOrderDetails as $nOrderDetailKey => $aOrderDetail)
			{
				$sMenuName = $aMenus[$aOrderDetail['MenuID']]['Name'];

				$aStatistic[$sMenuName]['count'] += (int) $aOrderDetail['Amount'];
				$aStatistic[$sMenuName]['percentage'] = intval($aStatistic[$sMenuName]['count'] / $nTotalCount * 100);
			}

			$aData =
			[
				'mode'			=> 'font',
				'page'			=> 'statistic',
				'statistics'	=> $aStatistic,
				'range'			=> (new DateTime($sFrom))->format('Y-m-d') . ' ~ ' . (new DateTime($sTill))->format('Y-m-d'),
			];

			return view('font.statistic', $aData);
		}
	}

?>
