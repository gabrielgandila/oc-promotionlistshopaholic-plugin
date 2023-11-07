<?php namespace Sprintsoft\PromotionsList\Updates;

use Schema;
use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class Migration103
 * @package Sprintsoft\PromotionsList\Updates
 */
class Migration103 extends Migration
{
    const TABLE_NAME = 'lovata_shopaholic_offers';

    public function up()
    {
        if (!Schema::hasTable(self::TABLE_NAME) || Schema::hasColumn(self::TABLE_NAME, 'promo_category_id')) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable)
        {
            $obTable->integer('promo_category_id')->nullable();
        });
    }

    public function down()
    {
        if (!Schema::hasTable(self::TABLE_NAME) || !Schema::hasColumn(self::TABLE_NAME, 'promo_category_id')) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable)
        {
            $obTable->dropColumn(['promo_category_id']);
        });
    }
}