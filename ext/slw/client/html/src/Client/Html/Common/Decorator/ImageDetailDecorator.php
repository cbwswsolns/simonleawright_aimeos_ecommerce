<?php

namespace Aimeos\Client\Html\Common\Decorator;
 
class ImageDetailDecorator extends \Aimeos\Client\Html\Common\Decorator\Base implements \Aimeos\Client\Html\Common\Decorator\Iface
{
    /**
     * Adds the data to the view object required by the templates
     *
     * @param \Aimeos\MW\View\Iface $view The view object which generates the HTML output
     * @param array &$tags Result array for the list of tags that are associated to the output
     * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
     * @return \Aimeos\MW\View\Iface The view object with the data required by the templates
     * @since 2018.01
     */
    public function addData(\Aimeos\MW\View\Iface $view, array &$tags = [], string &$expire = null): \Aimeos\MW\View\Iface
    {
        $enc = $view->encoder();

        $view = parent::addData($view, $tags, $expire);
 
        // access already added data
        $detailMediaItems = $view->get('detailMediaItems', []);
 
        $counter = 0;
        foreach ($detailMediaItems as $id => $mediaItem) {
            foreach ($mediaItem->getPreviews() as $pkey => $url) {
                /* Do nothing */
            }

            $previewUrls[$counter] =  $enc->html($view->content($url));

            $counter++;
        }

        // fetch some items from the database
        $view->previewUrls = $previewUrls;

        $catListManager = \Aimeos\MShop::create($this->getContext(), 'catalog/lists');

        $searchCatList = $catListManager->createSearch(true);

        $exprCatList = [
            $searchCatList->compare('==', 'catalog.lists.refid', $view->get('detailProductItem')->getId()),
            $searchCatList->compare('==', 'catalog.lists.domain', 'product'),
            $searchCatList->getConditions()
        ];


        $searchCatList->setConditions($searchCatList->combine('&&', $exprCatList));

        $categoriesList = array();
        $counter = 0;
        foreach ($catListManager->searchItems($searchCatList) as $cat) {
            $categoriesList[$counter] = $cat->getParentId();

            $counter++;
        }

        $currentCategory = null;

        if (!empty($categoriesList)) {
            $catManager = \Aimeos\MShop::create($this->getContext(), 'catalog');

            $searchCat = $catManager->createSearch(true);

            $exprCat = [
                $searchCat->compare('==', 'catalog.id', $categoriesList[0]),
                $searchCat->getConditions()
            ];

            $searchCat->setConditions($searchCat->combine('&&', $exprCat));

            $categories = array();
            $counter = 0;
            foreach ($catManager->searchItems($searchCat) as $cat) {
                $currentCategory =  $cat->getCode();

                break;

                $counter++;
            }
        }



        $searchCatList2 = $catListManager->createSearch(true);

        $exprCatList2 = [
            $searchCatList2->compare('==', 'catalog.lists.parentid', $categoriesList[0]),
            $searchCatList2->compare('==', 'catalog.lists.domain', 'product'),
            $searchCatList2->getConditions()
        ];

        $searchCatList2->setConditions($searchCatList->combine('&&', $exprCatList2));

        $catProductList = array();
        $counter = 0;
        foreach ($catListManager->searchItems($searchCatList2) as $cat) {
            $catProductList[$counter] = $cat->getRefId();

            $counter++;
        }

        $productManager = \Aimeos\MShop::create($this->getContext(), 'product');

        $searchProducts = $productManager->createSearch(true);

        $expr = $searchProducts->compare('==', 'product.id', $catProductList);
        $searchProducts->setConditions($expr);


        $catProductUrls = array();
        $counter = 0;
        foreach ($productManager->searchItems($searchProducts) as $product) {
            $catProductUrls[$counter] = $product->getUrl();

            $counter++;
        }

        $view->currentCategory = $currentCategory;

        $view->catProductUrls = $catProductUrls;

        $view->catProductCount = count($catProductUrls);

        $view->catProductPos = 1 + array_search($view->get('detailProductItem')->getUrl(), $catProductUrls);

        return $view;
    }
}
