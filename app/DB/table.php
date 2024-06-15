<?php
    namespace App\DB;

    use Illuminate\Database\Eloquent\Model;

    class Table extends Model
    {
        protected $table = 'table';
        protected $promaryKey = 'id';
        protected $fillable = ['Token', 'Name', 'People'];
    }

?>
