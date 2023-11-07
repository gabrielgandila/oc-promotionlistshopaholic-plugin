<?php namespace Sprintsoft\PromotionsList\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use Illuminate\Support\Facades\DB;

class Migration102 extends Migration
{
	const TABLE_NAME = 'lovata_shopaholic_offers_promo_categories';
	
    public function up()
    {
        if (Schema::hasTable(self::TABLE_NAME)) {
            return;
        }

        Schema::create(self::TABLE_NAME, function ($obTable)
        {
            $obTable->engine = 'InnoDB';
            $obTable->increments('id')->unsigned();
            $obTable->text('name');
            $obTable->integer('order');
            $obTable->integer('category_id')->unsigned();
        });
        
        DB::table(self::TABLE_NAME)->insert([
            ['name' => 'Cosuri de fum', 'category_id' => 8, 'order' => 1],
            ['name' => 'Seminee si Sobe pe lemne', 'category_id' => 711, 'order' => 2],
            ['name' => 'Centrale si Seminee pe peleti', 'category_id' => 256, 'order' => 3],
            ['name' => 'Seminee Decorative', 'category_id' => 271, 'order' => 4],
            ['name' => 'Gratare si cuptoare', 'category_id' => 270, 'order' => 5],
            ['name' => 'Accesorii', 'category_id' => 108, 'order' => 6],
        ]);
        
    }
    
    /**
     * Rollback migration
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
}