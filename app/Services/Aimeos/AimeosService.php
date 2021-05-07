<?php

namespace App\Services\Aimeos;

use Aimeos\MShop;
use Aimeos\Controller\Frontend;

class AimeosService
{
    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * Get all categories
     *
     * @return array $categories [all categories]
     */
    public function getAllCategories()
    {
        $manager = $this->getAimeosManagerByContextAndDomain('catalog');

        $search = $manager->createSearch();

        $categories = array();
        $counter = 0;
        foreach ($manager->searchItems($search) as $cat) {
            $categories[$counter]['id'] =  $cat->getId();
            $categories[$counter]['name'] =  $cat->getCode();
            $categories[$counter]['label'] =  $cat->getLabel();
            $categories[$counter]['url'] =  $cat->getUrl();

            $counter++;
        }

        return $categories;
    }


    /**
     * Get all products (referenced by category)
     *
     * @return array $products [all products]
     */
    public function getAllProducts()
    {
        $categories = $this->getAllCategories();

        $products = array();
        foreach ($categories as $category) {
            $categoryProducts = $this->getProductsByCategory($category['name']);

            $products[$category['name']] = $categoryProducts;
        }

        return $products;
    }


    /**
     * Get category count
     *
     * @param \Aimeos\MShop\Common\Manager\Iface $manager [manager object - optional]
     * @param \Aimeos\MW\Criteria\Iface          $search  [search object - optional]
     *
     * @return integer $count [the category count]
     */
    public function getCategoryCount($manager = null, $search = null)
    {
        if ($manager == null) {
            $manager = $this->getAimeosManagerByContextAndDomain('catalog');
        }

        if ($search == null) {
            $search = $manager->createSearch(true);
        }

        $count = $manager->searchItems($search)->count();

        return $count;
    }


    /**
     * Get products by category
     *
     * @param string $category [the category name]
     *
     * @return array $products [all products of the given category]
     */
    public function getProductsByCategory($category)
    {
        $manager = $this->getAimeosManagerByContextAndDomain('catalog');

        $search = $manager->createSearch(true);

        $products = array();

        $categoryCount = $this->getCategoryCount($manager, $search);

        // Categories exist?
        if ($categoryCount > 0) {
            $cat_id = $manager->searchItems($search)->first()->getid();

            // Does $category route parameter exist?
            if (!is_null($category)) {
                try {
                    $cat_id = $manager->findItem(trim($category))->getId();
                } catch (\Aimeos\MShop\Exception $e) {
                    /* Category not found */
                    return redirect()->route('public.pages.display');
                }
            }

            $context = $this->getAimeosContext();

            $productsOut = \Aimeos\Controller\Frontend::create($context, 'product')->uses(['attribute', 'media','price', 'text'])->category($cat_id)->sort('relevance')->search();

            /* Sorted products as per the given category
               Note: will return empty collection if no products exist */

            $counter = 0;
            foreach ($productsOut as $product) {
                $products[$counter] = ['href' => url($product->getRefItems('media')->getUrl()->first()), 'title' => $product->getUrl(), 'description' => $product->getRefItems('text', 'short')->getContent()->first()];

                $counter++;
            }
        }

        return $products;
    }


    /**
     * Get Aimeos manager by context and domain
     *
     *  @param string $path [Name of the domain (and sub-managers) separated by slashes, e.g "product/list"]
     *
     * @return \Aimeos\MShop\Common\Manager\Iface [the manager object]
     */
    protected function getAimeosManagerByContextAndDomain(string $path)
    {
        $context = $this->getAimeosContext();

        return \Aimeos\MShop::create($context, $path);
    }


    /**
     * Get Aimeos context
     *
     * @return \Aimeos\MShop\Context\Item\Iface [the context]
     */
    protected function getAimeosContext()
    {
        return resolve('aimeos.context')->get();
    }
}
