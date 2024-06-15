<?php
	namespace App\DB;

	use Illuminate\Database\Eloquent\Model;

	class OrderDetail extends Model
	{
		protected $table = 'order_detail';
		protected $promaryKey = 'id';
		protected $fillable = ['OrderID', 'MenuID', 'Amount', 'Status'];
	}

?>
