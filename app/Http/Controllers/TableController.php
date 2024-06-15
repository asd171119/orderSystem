<?php
	namespace App\Http\Controllers;

	use App\Http\Controllers\Controller;
	use App\Constant\Constant;
	use App\DB\Table;
	use App\DB\TableStatus;
	use Validator;
	use Log;

	class TableController extends Controller
	{
		var $oConstant;
		function __construct()
		{
			$this->oConstant = new constant();
		}

		# Table Manger、Table Manger Edit
		function get_tables()
		{
			$aTables = Table::all();
			$aTableStatusTmp = TableStatus::get(['id', 'Status'])->toArray();
			$aTableStatus = $this->key_array($aTableStatusTmp, 'id');


			$oConstant = new Constant();
			foreach ($aTables as $nTablesKey => $aTable)
			{
				$aTables[$nTablesKey]['Status'] = $aTableStatus[$aTable['id']]['Status'];
				$aTables[$nTablesKey]['StatusStr'] = $oConstant->get_tableStatusStr($aTableStatus[$aTable['id']]['Status']);
			}

			$aData =
			[
				'mode'		=> 'font',
				'page'		=> 'table_manger',
				'tables'	=> $aTables,
			];

			return view('font.table_manger', $aData);
		}

		function get_table()
		{
			$nTableToken = isset($_GET['token']) ? $_GET['token'] : '';

			$aTable = Table::firstwhere('Token', $nTableToken);

			$aData =
			[
				'mode'      => 'font',
				'page'      => 'table_manger',
				'table'     => $aTable,
				'token'     => $nTableToken
			];

			return view('font.table_manger_edit', $aData);
		}

		function edit_table()
		{
			$aParams = request()->all();

			if($aParams['Token'] == '')
			{
				$aParams['Token'] = $this->create_token(Table::class, 'table_');
				Table::create($aParams);

				$aTable = Table::firstwhere('Token', $aParams['Token'])->toArray();

				$oTableSattus = new TableStatus;
				$oTableSattus->TableID = $aTable['id'];
				$oTableSattus->save();
			}
			else
			{
				$aTable = Table::firstwhere('Token', $aParams['Token']);

				$aTable->Name = $aParams['Name'];
				$aTable->People = $aParams['People'];

				$aTable->save();
			}

			return redirect('/font/table_manger');
		}

		function delete_table()
		{
			$nTableToken = isset($_GET['token']) ? $_GET['token'] : '';

			$aTable = Table::firstwhere('Token', $nTableToken);

			$aTableStatus = TableStatus::where('TableID', $aTable->id);

			$aTable->delete();
			$aTableStatus->delete();

			return redirect('/font/table_manger');
		}

	}

?>
