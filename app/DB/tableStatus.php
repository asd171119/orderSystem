<?php
	namespace App\DB;

	use Illuminate\Database\Eloquent\Model;

	class TableStatus extends Model
	{
		protected $table = 'table_status';
		protected $promaryKey = 'id';
		protected $fillable = ['TableID', 'OrderID', 'Reserve', 'Status'];
	}

?>