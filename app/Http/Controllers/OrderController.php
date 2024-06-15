<?php
	namespace App\Http\Controllers;

	use App\Http\Controllers\Controller;
	use App\Constant\Constant;
	use App\DB\Table;
	use App\DB\TableStatus;
	use App\DB\Order;
	use App\DB\OrderDetail;
	use App\DB\Menu;
	use Carbon\Carbon;
	use Validator;
	use Batch;
	use Log;

	class OrderController extends Controller
	{
		var $oConstant;
		function __construct()
		{
			$this->oConstant = new constant();
		}

		function get_data()
		{
			$this->checkTableStatus();

			$aTbaleTmps = Table::all()->toArray();
			$aTables = $this->key_array($aTbaleTmps, 'id');

			$aTableStatus = TableStatus::all()->toArray();

			$aOrderTmps = Order::whereIn('Status', [$this->oConstant::ORDER_STATUS_ESTABLISH, $this->oConstant::ORDER_STATUS_PAY])->get()->toArray();
			$aOrders = $this->key_array($aOrderTmps, 'id');
			$aOrderIDs = array_column($aOrderTmps, 'id');

			$aOrderDetailTmps = OrderDetail::whereIn('OrderID', $aOrderIDs)->get()->toArray();
			$aMenuIDs = array_column($aOrderDetailTmps, 'MenuID');

			$aMenuTmps = Menu::whereIn('id', $aMenuIDs)->get()->toArray();
			$aMenus = $this->key_array($aMenuTmps, 'id');

			$aOrderDetails = $aOrderTotals = array();
			foreach ($aOrderDetailTmps as $nOrderDetailTmpKey => $aOrderDetailTmp)
			{
				if(!isset($aOrderDetails[$aOrderDetailTmp['OrderID']])) $aOrderDetails[$aOrderDetailTmp['OrderID']] = array();
				$aOrderDetails[$aOrderDetailTmp['OrderID']][$aOrderDetailTmp['id']]['MenuName'] = $aMenus[$aOrderDetailTmp['MenuID']]['Name'];
				$aOrderDetails[$aOrderDetailTmp['OrderID']][$aOrderDetailTmp['id']]['Price'] = $aMenus[$aOrderDetailTmp['MenuID']]['Price'];
				$aOrderDetails[$aOrderDetailTmp['OrderID']][$aOrderDetailTmp['id']]['Amount'] = $aOrderDetailTmp['Amount'];
				$aOrderDetails[$aOrderDetailTmp['OrderID']][$aOrderDetailTmp['id']]['Status'] = $aOrderDetailTmp['Status'];
				$aOrderDetails[$aOrderDetailTmp['OrderID']][$aOrderDetailTmp['id']]['StatusStr'] = $this->oConstant->get_OrderDetailStatusStr($aOrderDetailTmp['Status']);

				if(!isset($aOrderTotals[$aOrderDetailTmp['OrderID']])) $aOrderTotals[$aOrderDetailTmp['OrderID']] = 0;
				$aOrderTotals[$aOrderDetailTmp['OrderID']] += (int)$aMenus[$aOrderDetailTmp['MenuID']]['Price'] * (int)$aOrderDetailTmp['Amount'];
			}

			foreach ($aTableStatus as $nTableStatusKey => $aTableData)
			{
				$aTableStatus[$nTableStatusKey]['TableName'] = $aTables[$aTableData['TableID']]['Name'];
				$aTableStatus[$nTableStatusKey]['TablePeople'] = $aTables[$aTableData['TableID']]['People'];
				$aTableStatus[$nTableStatusKey]['StatusStr'] = $this->oConstant->get_tableStatusStr($aTableData['Status']);
				$aTableStatus[$nTableStatusKey]['ReserveStr'] = Carbon::parse($aTableData['Reserve'])->format('Y-m-d H:i');
				$aTableStatus[$nTableStatusKey]['OrderID'] = '';
				$aTableStatus[$nTableStatusKey]['Phone'] = '';

				if(isset($aOrders[$aTableData['OrderID']]))
				{
					$aTableStatus[$nTableStatusKey]['OrderID'] = 'O_' . $aTableData['OrderID'] . Carbon::parse($aOrders[$aTableData['OrderID']]['created_at'])->format('YmdHis');
					$aTableStatus[$nTableStatusKey]['Phone'] = $aOrders[$aTableData['OrderID']]['Phone'];
				}
			}

			$aOrderDatas = $aTakeawayDatas = array();
			foreach ($aOrders as $nOrderID => $aOrder)
			{
				$sOrderID = 'O_' . $aOrder['id'] . Carbon::parse($aOrder['created_at'])->format('YmdHis');
				$aOrderDatas[$sOrderID] = array(
					'ID'			=> $aOrder['id'],
					'Status'		=> $aOrder['Status'],
					'StatusStr'		=> $this->oConstant->get_OrderStatusStr($aOrder['Status']),
					'Details'		=> isset($aOrderDetails[$nOrderID]) ? $aOrderDetails[$nOrderID] : [],
					'Total'			=> isset($aOrderTotals[$nOrderID]) ? $aOrderTotals[$nOrderID] : 0,
				);

				if($aOrder['Type'] == $this->oConstant::ORDER_TYPE_TAKEAWAY)
				{
					$aTakeawayDatas[$sOrderID] = array(
						'ID'			=> $aOrder['id'],
						'Status'		=> $aOrder['Status'],
						'StatusStr'		=> $this->oConstant->get_OrderStatusStr($aOrder['Status']),
						'Details'		=> isset($aOrderDetails[$nOrderID]) ? $aOrderDetails[$nOrderID] : [],
						'Total'			=> isset($aOrderTotals[$nOrderID]) ? $aOrderTotals[$nOrderID] : 0,
					);
				}
			}

			$aData =
			[
				'mode'			=> 'font',
				'page'			=> 'order_manger',
				'tableStatus'	=> $aTableStatus,
				'orderDatas'	=> $aOrderDatas,
				'takeawayDatas'	=> $aTakeawayDatas,
			];

			return view('font.order_manger', $aData);
		}

		function reserve()
		{
			$aParams = request()->all();

			$oTableStatus = TableStatus::firstwhere('id', $aParams['TableStatusID']);

			if($oTableStatus->OrderID == 0)
			{
				$oOrder = new Order();
				$oOrder->Type = $this->oConstant::ORDER_TYPE_INTERNAL;
				$oOrder->Phone = $aParams['Phone'];
				$oOrder->Status = $this->oConstant::ORDER_STATUS_RESERVE;
				$oOrder->save();
			}

			$oTableStatus->OrderID = $oOrder->id;
			$oTableStatus->Status = $this->oConstant::TABLE_STATUS_BOOK;
			$oTableStatus->Reserve = $aParams['Reserve'];

			$oTableStatus->save();

			return redirect('/font/order_manger');
		}

		function cancel_reserve()
		{
			$aParams = request()->all();

			$oTableStatus = TableStatus::firstwhere('id', $aParams['TableStatusID']);

			$oOrder = Order::firstwhere('id', $oTableStatus->OrderID);
			$oOrder->delete();

			$oTableStatus->Status = $this->oConstant::TABLE_STATUS_NONE;
			$oTableStatus->reserve = $this->oConstant::DEFAULT_DATETIME;
			$oTableStatus->OrderID = $this->oConstant::DEFAULT_INT;
			$oTableStatus->save();

			return redirect('/font/order_manger');
		}

		function table_finish()
		{
			$aParams = request()->all();

			$aTableStatus = TableStatus::firstwhere('id', $aParams['TableStatusID']);

			Order::where('id', $aTableStatus['OrderID'])->update(['Status' => $this->oConstant::ORDER_STATUS_FINISH]);

			$aTableStatus->OrderID = $this->oConstant::DEFAULT_INT;
			$aTableStatus->Status = $this->oConstant::TABLE_STATUS_NONE;
			$aTableStatus->save();

			return redirect('/font/order_manger');
		}

		function checkTableStatus()
		{
			$aTableStatus = TableStatus::where('Status', $this->oConstant::TABLE_STATUS_BOOK)->orWhere('Status', $this->oConstant::TABLE_STATUS_SERVE)->get()->toarray();

			$sNow = (new Carbon())->format('Y-m-d H:i:s');
			foreach ($aTableStatus as $nTableStatusKey => $aTable)
			{
				$aBooks = $aServes = array();
				switch ($aTable['Status'])
				{
					case $this->oConstant::TABLE_STATUS_BOOK:
						$sDeadline = Carbon::parse($aTable['Reserve'])->format('Y-m-d H:i:s');
						$bUpdateStatus = $sDeadline < $sNow ? true : false;

						if($bUpdateStatus) array_push($aBooks, $aTable['id']);
						break;

					case $this->oConstant::TABLE_STATUS_SERVE:

						$sDeadline = Carbon::parse($aTable['Reserve'])->addMinutes($this->oConstant::KEEP_TIME)->format('Y-m-d H:i:s');
						$bUpdateStatus = $sDeadline < $sNow ? true : false;

						if($bUpdateStatus)
						{
							array_push($aServes, $aTable['id']);

							Order::where('id', $aTable['OrderID'])->delete();
						}
						break;
				}
			}

			if(!empty($aBooks)) TableStatus::whereIn('id', $aBooks)->update(['Status' => $this->oConstant::TABLE_STATUS_SERVE]);
			if(!empty($aServes))
			{
				TableStatus::whereIn('id', $aServes)->update(['Status' => $this->oConstant::TABLE_STATUS_NONE, 'Reserve' => $this->oConstant::DEFAULT_DATETIME, 'OrderID' => $this->oConstant::DEFAULT_INT]);
			}
		}

		function edit_order_detail()
		{
			$aParams = request()->all();

			$aEditDatas = json_decode(preg_replace("/'/", '"', $aParams['editData']), true);

			foreach ($aEditDatas as $nEditDataKey => $aEditData)
			{
				if($aEditData['Amount'] == 0)
				{
					unset($aEditDatas[$nEditDataKey]['Amount']);

					$aEditDatas[$nEditDataKey]['Status'] = $this->oConstant::ORDER_DETAIL_STATUS_CANCEL;
				}
			}

			Log::notice(print_r($aEditDatas, true));

			Batch::update(new OrderDetail, $aEditDatas, 'ID');

			return redirect('/font/order_manger');
		}

		function check_orderDetailStatus()
		{
			$aParams = request()->all();

			$bStatus = true;

			$aOrderDetails = OrderDetail::where('OrderID', $aParams['OrderID'])->get()->toArray();
			foreach ($aOrderDetails as $nOrderDetailKey => $aOrderDetail)
			{
				if($aOrderDetail['Status'] == $this->oConstant::ORDER_DETAIL_STATUS_PREPARE)
				{
					$bStatus = false;
					break;
				}
			}

			return response($bStatus);
		}

		function order_manger_pay()
		{
			$aParams = request()->all();

			$oOrder = Order::firstwhere('id', $aParams['oid']);
			if($oOrder->Type == $this->oConstant::ORDER_TYPE_TAKEAWAY)
			{
				$oOrder->Status = $this->oConstant::ORDER_STATUS_FINISH;
			}
			else
			{
				$oOrder->Status = $this->oConstant::ORDER_STATUS_PAY;
			}
			$oOrder->save();

			$aOrderDetails = OrderDetail::where('OrderID', $aParams['oid'])->get()->toArray();
			if(!empty($aOrderDetails))
			{
				$aEdit = array();
				foreach ($aOrderDetails as $nOrderDetailKey => $aOrderDetail)
				{
					if($aOrderDetail['Status'] == $this->oConstant::ORDER_DETAIL_STATUS_PREPARE)
					{
						array_push($aEdit, array(
							'ID'		=> $aOrderDetail['id'],
							'Status'	=> $this->oConstant::ORDER_DETAIL_STATUS_FINISH,
						));
					}
				}

				Batch::update(new OrderDetail, $aEdit, 'ID');
			}

			return redirect('/font/order_manger');
		}

		function get_orders()
		{
			$nType = request('type');

			$aOrderTmps = Order::where([['Status', '!=', $this->oConstant::ORDER_STATUS_FINISH],
										['Status', '!=', $this->oConstant::ORDER_STATUS_CANCEL],
										['Status', '!=', $this->oConstant::ORDER_STATUS_RESERVE],
										['Type', '=', $nType]])->orderBy('created_at', 'asc')->get()->toArray();

			$aOrderIDs = array_column($aOrderTmps, 'id');
			$aOrders = $this->key_array($aOrderTmps, 'id');

			$aOrderDetailTmps = OrderDetail::whereIn('OrderID', $aOrderIDs)->where('Status', $this->oConstant::ORDER_DETAIL_STATUS_PREPARE)->get()->toArray();
			$aMenuIDs = array_column($aOrderDetailTmps, 'MenuID');

			$aMenuTmps = Menu::whereIn('id', $aMenuIDs)->get()->toArray();
			$aMenus = $this->key_array($aMenuTmps, 'id');

			foreach ($aOrderDetailTmps as $nOrderDetailTmpKey => $aOrderDetailTmp)
			{
				$aOrderDetailTmps[$nOrderDetailTmpKey]['Name'] = $aMenus[$aOrderDetailTmp['MenuID']]['Name'];
			}
			$aOrderDetails = $this->key_array($aOrderDetailTmps, 'OrderID', 'multi');

			$aOrderData = array();
			foreach ($aOrderDetails as $nOrderID => $aOrderDetail)
			{
				$sOrderID = 'O_' . $nOrderID . Carbon::parse($aOrders[$nOrderID]['created_at'])->format('YmdHis');
				$aOrderData[$sOrderID] = $aOrderDetail;
			}

			$aData =
			[
				'mode'			=> 'back',
				'page'			=> $nType == $this->oConstant::ORDER_TYPE_INTERNAL ? 'internal' : 'takeaway',
				'orderDatas'	=> $aOrderData,
			];

			return view('back.order', $aData);
		}

		function finish_orderDetail()
		{
			$nOrderDetailID = request('id');
			$ntype = request('type');

			$oOrderDetail = OrderDetail::firstwhere('id', $nOrderDetailID);
			$oOrderDetail->Status = $this->oConstant::ORDER_DETAIL_STATUS_FINISH;
			$oOrderDetail->save();

			return redirect('/back/order?mode=back&type=' . $ntype);
		}
	}

?>
