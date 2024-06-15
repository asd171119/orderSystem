<?php
	namespace App\Constant;

	class Constant
	{
		#Commom
		const DB_TRUE = 1;
		const DB_FALSE = 0;

		const DEFAULT_DATETIME = '1911-01-01 00:00:00';
		const DEFAULT_INT = '0';

		const MENU_IMAGE_PATH = '\\images\\menus\\';
		const COMMON_IMAGE_PATH = '\\images\\common\\';

		const REFRESH_DURATION = 10;
		const KEEP_TIME = 10;

		# Table、Table Status
		const TABLE_STATUS_NONE = 0;
		const TABLE_STATUS_USED = 1;
		const TABLE_STATUS_BOOK = 2;
		const TABLE_STATUS_SERVE = 3;

		function get_tableStatusStr($nStatus)
		{
			$aTableStatusStr = array();
			$aTableStatusStr[Constant::TABLE_STATUS_NONE] = '空桌';
			$aTableStatusStr[Constant::TABLE_STATUS_USED] = '使用中';
			$aTableStatusStr[Constant::TABLE_STATUS_BOOK] = '訂位';
			$aTableStatusStr[Constant::TABLE_STATUS_SERVE] = '保留中';

			return $nStatus === 'All' ? $aTableStatusStr : $aTableStatusStr[$nStatus];
		}

		# Menu
		const MENU_STATUS_PUBLISH = 1;
		const MENU_STATUS_UNPUBLISH = 2;

		function get_menuStatusStr($nStatus)
		{
			$aMenuStatusStr = array();
			$aMenuStatusStr[Constant::MENU_STATUS_PUBLISH] = '上架';
			$aMenuStatusStr[Constant::MENU_STATUS_UNPUBLISH] = '下架';

			return $nStatus === 'All' ? $aMenuStatusStr : $aMenuStatusStr[$nStatus];
		}

		# Order
		const ORDER_STATUS_ESTABLISH = 1;
		const ORDER_STATUS_PAY = 2;
		const ORDER_STATUS_FINISH = 3;
		const ORDER_STATUS_CANCEL = 4;
		const ORDER_STATUS_RESERVE = 5;

		function get_OrderStatusStr($nStatus)
		{
			$aOrderStatusStr = array();
			$aOrderStatusStr[Constant::ORDER_STATUS_ESTABLISH] = '已成立';
			$aOrderStatusStr[Constant::ORDER_STATUS_PAY] = '已付款';
			$aOrderStatusStr[Constant::ORDER_STATUS_FINISH] = '已完成';
			$aOrderStatusStr[Constant::ORDER_STATUS_CANCEL] = '取消';
			$aOrderStatusStr[Constant::ORDER_STATUS_RESERVE] = '訂位';

			return $nStatus === 'All' ? $aOrderStatusStr : $aOrderStatusStr[$nStatus];
		}

		const ORDER_TYPE_INTERNAL = 1;
		const ORDER_TYPE_TAKEAWAY = 2;

		function get_OrderTypeStr($nType)
		{
			$aOrderTypeStr = array();
			$aOrderTypeStr[Constant::ORDER_TYPE_INTERNAL] = '內用';
			$aOrderTypeStr[Constant::ORDER_TYPE_TAKEAWAY] = '外帶';

			return $nType === 'All' ? $aOrderTypeStr : $aOrderTypeStr[$nType];
		}

		# Order Detail
		const ORDER_DETAIL_STATUS_PREPARE = 1;
		const ORDER_DETAIL_STATUS_FINISH = 2;
		const ORDER_DETAIL_STATUS_CANCEL = 3;

		function get_OrderDetailStatusStr($nStatus)
		{
			$aOrderDetailStatusStr = array();
			$aOrderDetailStatusStr[Constant::ORDER_DETAIL_STATUS_PREPARE] = '準備中';
			$aOrderDetailStatusStr[Constant::ORDER_DETAIL_STATUS_FINISH] = '已完成';
			$aOrderDetailStatusStr[Constant::ORDER_DETAIL_STATUS_CANCEL] = '取消';

			return $nStatus === 'All' ? $aOrderDetailStatusStr : $aOrderDetailStatusStr[$nStatus];
		}

		# Error Code
		const ERROR_PARAM = 'E0001'; // 參數錯誤
	}
?>
