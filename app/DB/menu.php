<?php
    namespace App\DB;

    use Illuminate\Database\Eloquent\Model;

    class Menu extends Model
    {
        protected $table = 'menu';
        protected $promaryKey = 'id';
        protected $fillable = ['Token', 'Name', 'Price', 'OnSale', 'Status', 'Image', 'IsDeleted'];
    }

?>
