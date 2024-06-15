<?php
	namespace App\Http\Controllers\client;

	use App\Http\Controllers\Controller;
	use App\Constant\Constant;
	use App\DB\TableStatus;
	use App\DB\Table;
	use App\DB\Order;
	use App\DB\Menu;
	use App\DB\OrderDetail;
	use Carbon\Carbon;
	use Validator;
	use Log;

	class COrderController extends Controller
	{
		function verify_data()
		{
			$aParams = request()->all();

			session_start();

			if($aParams['verifyType'] == 'phone')
			{
				$oValid = Validator::make($aParams,
				[
					'Phone' => ['required', 'min:10']
				]);

				$_SESSION['Phone'] = $aParams['phone'];
				$_SESSION['Type'] = $aParams['md'];

				$aData = [];
				if($aParams['md'] == Constant::ORDER_TYPE_INTERNAL)
				{
					$aOrder = Order::where([
									['Phone', '=', $aParams['phone']],
									['Status', '=', Constant::ORDER_STATUS_RESERVE]
								])->get()->toArray();

					if(empty($aOrder))
					{
						$aTableStatus = TableStatus::where('Status', Constant::TABLE_STATUS_NONE)->get()->toArray();
						$aTableIDs = array_column($aTableStatus, 'TableID');
						$aTables = Table::whereIn('id', $aTableIDs)->get()->toArray();

						$aData['Status'] = 'table';
						$aData['Tables'] = $aTables;
						return view('client.verify', $aData);
					}
					else
					{
						$aTableStatus = TableStatus::where('OrderID', $aOrder[0]['id'])->first()->toArray();

						$_SESSION['OrderID'] = $aOrder[0]['id'];
						$_SESSION['TableID'] = $aTableStatus['TableID'];
						return redirect('client/order');
					}
				}
				else
				{
					$_SESSION['OrderID'] = Constant::DEFAULT_INT;
					$_SESSION['TableID'] = Constant::DEFAULT_INT;
					return redirect('client/order');
				}
			}
			else if($aParams['verifyType'] == 'table')
			{
				$_SESSION['TableID'] = $aParams['table'];
				$_SESSION['OrderID'] = Constant::DEFAULT_INT;
				return redirect('client/order');
			}
		}

		function check_order()
		{
			$aParams = request()->all();

			$aMenuIDs = $aOrders = [];
			foreach ($aParams as $nParamKey => $aParam)
			{
				if(preg_match("/menu-/i", $nParamKey) && $aParam != 0)
				{
					$nMenuID = (explode('-', $nParamKey))[1];
					array_push($aMenuIDs, $nMenuID);

					$aOrders[$nMenuID] = array(
						'MenuName'	=> '',
						'Price'		=> '',
						'Amount'	=> $aParam,
					);
				}
			}

			$aMenuTmps = Menu::whereIn('id', $aMenuIDs)->get()->toArray();
			$aMenus = $this->key_array($aMenuTmps, 'id');

			$nTotal = 0;
			foreach ($aOrders as $nMenuID => $aOrder)
			{
				$aOrders[$nMenuID]['MenuName']	= $aMenus[$nMenuID]['Name'];
				$aOrders[$nMenuID]['Price']		= $aMenus[$nMenuID]['Price'];

				$nTotal += (int)$aOrder['Amount'] * (int)$aMenus[$nMenuID]['Price'];
			}

			session_start();
			$_SESSION['OrderDatas'] = $aOrders;

			$aData =
			[
				'timestamp'	=> (new Carbon())->format('Y-m-d H:i:s'),
				'orders'	=> $aOrders,
				'total'		=> $nTotal,
			];

			return view('client.check', $aData);
		}

		function save_order()
		{
			session_start();

			$aData =
			[
				'status'	=> false,
				'msg'		=> ''
			];

			if(!isset($_SESSION['Type']))
			{
				$aData['msg'] = Constant::ERROR_PARAM . ': (Type)';
				return view('client.complete', $aData);
			}
			if(!isset($_SESSION['OrderDatas']) || count($_SESSION['OrderDatas']) == 0)
			{
				$aData['msg'] = Constant::ERROR_PARAM . ': 您沒有選擇任何餐點哦！ (OrderDatas)';
				return view('client.complete', $aData);
			}

			$bInsertOrder = $bUpdateOrder = $bInsertOrderDetail = $bUpdateTableStatus = false;
			if($_SESSION['Type'] == Constant::ORDER_TYPE_TAKEAWAY)
			{
				$bInsertOrder = true;
				$bInsertOrderDetail = true;
			}
			else if($_SESSION['Type'] == Constant::ORDER_TYPE_INTERNAL)
			{
				if(!isset($_SESSION['OrderID']))
				{
					$aData['msg'] = Constant::ERROR_PARAM . ': (OrderID)';
					return view('client.complete', $aData);
				}

				$bUpdateTableStatus = true;
				if($_SESSION['OrderID'] == Constant::DEFAULT_INT)
				{
					$bInsertOrder = true;
					$bInsertOrderDetail = true;
				}
				else
				{
					$bUpdateOrder = true;
					$bInsertOrderDetail = true;
				}
			}

			if($bInsertOrder)
			{
				$oOrder = new Order;
				$oOrder->Type = $_SESSION['Type'];
				$oOrder->Phone = $_SESSION['Phone'];
				$oOrder->Status = Constant::ORDER_STATUS_ESTABLISH;
				$oOrder->save();
			}

			if($bUpdateOrder)
			{
				$oOrder = Order::where('id', $_SESSION['OrderID'])->first();
				$oOrder->Status = Constant::ORDER_STATUS_ESTABLISH;
				$oOrder->save();
			}

			if($bInsertOrderDetail)
			{
				$aOrderDetails = [];
				foreach ($_SESSION['OrderDatas'] as $nMenuID => $aData)
				{
					array_push($aOrderDetails, array(
						'OrderID'		=> $oOrder->id,
						'MenuID'		=> $nMenuID,
						'Amount'		=> $aData['Amount'],
						'Status'		=> Constant::ORDER_DETAIL_STATUS_PREPARE,
						'created_at'	=> (new Carbon())->format('Y-m-d H:i:s'),
						'updated_at'		=> (new Carbon())->format('Y-m-d H:i:s'),
					));
				}

				OrderDetail::insert($aOrderDetails);
			}

			if($bUpdateTableStatus)
			{
				TableStatus::where('id', $_SESSION['TableID'])->update(['OrderID' => $oOrder->id, 'Status' => Constant::TABLE_STATUS_USED, 'Reserve' => Constant::DEFAULT_DATETIME]);
			}

			$aData['status'] = true;
			$aData['msg'] = '下單成功，您的商品正在準備中......' . "\n" . '我們將盡快為您送餐！';
			return view('client.complete', $aData);
		}
	}
?>
