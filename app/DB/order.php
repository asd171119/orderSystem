<?php
	namespace App\DB;

	use Illuminate\Database\Eloquent\Model;

	class Order extends Model
	{
		protected $table = 'order';
		protected $promaryKey = 'id';
		protected $fillable = ['Type', 'Phone', 'Status'];
	}

?>
