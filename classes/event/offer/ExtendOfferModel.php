<?php namespace Sprintsoft\PromotionsList\Classes\Event\Offer;

use DB;
use Lovata\Toolbox\Classes\Event\ModelHandler;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Item\CategoryItem;
use Sprintsoft\PromotionsList\Classes\Store\OfferListStore;
use Lovata\Shopaholic\Models\Price;

/**
 * Class ExtendOfferModel
 * @package Sprintsoft\PromotionsList\Classes\Event\Offer
 */
class ExtendOfferModel extends ModelHandler
{
    /** @var Offer */
     protected $obElement;

     /**
      * @param $obEvent
      */
     public function subscribe($obEvent)
     {
        parent::subscribe($obEvent);

        Offer::extend(function ($obOffer) {
            /** @var Offer $obOffer */
            $obOffer->fillable[] = 'is_promotion';
            $obOffer->fillable[] = 'promo_category_id';

            $obOffer->addCachedField(['is_promotion']);
            $obOffer->addCachedField(['promo_category_id']);

            $this->beforeSaveEvent($obOffer);
        });
    }

    /**
     * After save event handler
     */
    protected function afterSave()
    {
        $this->checkFieldChanges('is_promotion', OfferListStore::instance()->is_promotion);
    }
    
    /**
     * Before save event handler
     */
    protected function beforeSaveEvent($model)
    {
        $model->bindEvent('model.beforeSave', function () use ($model) {
            // We assign the promotion category id to the Offer if is_promotion is set
            if($model->is_promotion) {
                $promoCategories = DB::table('lovata_shopaholic_offers_promo_categories')->get();
                $promoCategsCollection = collect($promoCategories);
    
                $category = CategoryItem::make($model->product->category_id);
                if(!empty($category)) {
                    $mainCategory = $category;
                    $promoCateg = null;
                    while(($mainCategory->isNotEmpty() && $mainCategory->nest_depth > 0) || (!$promoCateg && !$mainCategory)) {
                        $mainCategory = $mainCategory->parent;
                        $promoCateg = $promoCategsCollection->where('category_id', $mainCategory->id)->first();
                    }
    
                    if($promoCateg) {
                        $model->promo_category_id = $promoCateg->id;
                    } else {
                        $model->promo_category_id = $promoCategsCollection->where('category_id', 108)->first()->id;
                    }
                } else {
                    $model->promo_category_id = $promoCategsCollection->where('category_id', 108)->first()->id;
                }
            }
        });
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        if ($this->obElement->is_promotion) {
            OfferListStore::instance()->is_promotion->clear();
        }
    }

    /**
     * Get model class
     * @return string
     */
    protected function getModelClass()
    {
        return Offer::class;
    }

    /**
     * Get item class
     * @return string
     */
    protected function getItemClass()
    {
        return OfferItem::class;
    }
}
