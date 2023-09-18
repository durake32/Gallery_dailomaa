<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Brand;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Str;
use DB;

class ProductController extends Controller
{
    public function details($slug)
    {
        $product = Product::where('status', 1)
            ->where('slug', $slug)
            // ->with(
            //     [
            //         'reviews' => function ($comments) {
            //             $comments->where('status', 1)
            //                 ->latest();
            //         },
            //     ]
            // )
            ->with('reviews')
            ->with('brand')
            ->with('brand.sub_category')
            ->with('retailer')
            ->firstorFail();

        $productSEO = $product->toArray();

        $siteSEO = SiteSetting::select('title', 'meta_title', 'site_url', 'meta_description', 'twitter', 'default_image')->get()->toArray();

        SEOMeta::setTitle($productSEO['name'] . ' &#8211 ' . $siteSEO[0]['title']);
        SEOMeta::setDescription(strip_tags(htmlspecialchars_decode($productSEO['meta_description'])));


        OpenGraph::setTitle($productSEO['name']);
        OpenGraph::setUrl($siteSEO[0]['site_url'] . '/' . 'blog' . '/' . $productSEO['slug']);
        OpenGraph::setDescription(strip_tags(htmlspecialchars_decode($productSEO['meta_description'])));
        OpenGraph::addImage($siteSEO[0]['site_url'] . '/' . 'Asset/Uploads/Products/' . $productSEO['image']);
        OpenGraph::addProperty('type', 'article');
        OpenGraph::setSiteName($siteSEO[0]['title']);

        TwitterCard::setType('summary');
        TwitterCard::setSite($siteSEO[0]['twitter']);
        TwitterCard::setTitle($productSEO['name']);
        TwitterCard::setDescription(strip_tags(htmlspecialchars_decode($productSEO['meta_description'])));
        TwitterCard::setImage($siteSEO[0]['site_url'] . '/' . 'Asset/Uploads/Products/' . $productSEO['image']);

        $similarProducts = Product::onlineProduct()->where('status', 1)
            ->select('name', 'slug', 'sub_category_id', 'main_image', 'regular_price', 'sale_price', 'wholesaler_price', 'created_at')
            ->where('sub_category_id', $product->sub_category_id)
            ->where('slug', '!=', $slug)
            ->with('brand.sub_category')
            ->latest()
            ->take(6)
            ->get();


        return view('Frontend.Product.Individual.index', compact(
            'product',
            'similarProducts'
        ));
    }

    public function categoryWise($slug, Request $request)
    {
        $segment = $request->fullurl();

        $category = Category::where('status', 1)->where('slug', $slug)->select(
            'id',
            'name',
            'slug'
        )->first() ?? abort('404');

        $brands = Brand::where('status', 1)->get()->toArray();
            // dd($brands);
        $categories = Category::where('status', 1)->where('is_featured', 1)->select('id', 'slug', 'name', 'status', 'image')->with('sub_categories')->with('sub_categories.products')->get();
        $subCategories = SubCategory::where('status', 1)->where('is_featured', 1)->select('id', 'slug', 'name', 'status', 'image')->get();
            // $subcat=SubCategory::where('category_id',$categories)
        // dd($categories[6]['brands'][0]->products->count());
            $cat=Category::where('slug',$slug)->first();
            $subCat=SubCategory::where('category_id',$cat->id)->select('id')->where('is_featured', 1)->get();
            $subCatFilter=[];
                foreach ($subCat as $c) {
                    array_push($subCatFilter,$c->id);
                }
                // dd($zz);
        if (Str::contains($segment,'sort_by=name-ascending')) {
            $products = Product::onlineProduct()->active()->select(
                'id',
                'name',
                'meta_description',
                'regular_price',
                'sale_price',
                'main_image',
                'slug',
                'created_at',
                'brand_id',
                'status'
            )->with('brand')
                ->orderBy('name')
                ->paginate(15);
        } elseif (Str::contains($segment,'sort_by=name-descending')) {
            $products = Product::onlineProduct()->active()->select(
                'id',
                'name',
                'meta_description',
                'regular_price',
                'sale_price',
                'main_image',
                'slug',
                'created_at',
                'brand_id',
                'status',
            )->with('brand')
                ->orderBy('name', 'DESC')
                ->paginate(15);
        } elseif (Str::contains($segment,'sort_by=price-ascending')) {
            $products = Product::onlineProduct()->active()->select(
                'id',
                'name',
                'meta_description',
                'regular_price',
                'sale_price',
                'main_image',
                'slug',
                'created_at',
                'brand_id',
                'status',
            )->with('brand')
                ->orderBy('regular_price')
                ->paginate(15);
        } elseif (Str::contains($segment, 'sort_by=price-descending')) {
            $products = Product::onlineProduct()->active()->select(
                'id',
                'name',
                'meta_description',
                'regular_price',
                'sale_price',
                'main_image',
                'slug',
                'created_at',
                'brand_id',
                'status',
            )->with('brand')
                ->orderBy('regular_price', 'DESC')
                ->paginate(15);
        } else {
            $products = Product::onlineProduct()->active()->select(
                'id',
                'name',
                'meta_description',
                'regular_price',
                'sale_price',
                'main_image',
                'slug',
                'created_at',
                'brand_id',
                'status',
            )
            ->whereIn('sub_category_id',$subCatFilter)
                ->latest()
                ->paginate(15);
        }

        // dd($products);



        $siteSEO = SiteSetting::select('title', 'meta_title', 'site_url', 'meta_description', 'twitter', 'default_image')->get()->toArray();

        SEOMeta::setTitle($category['name'] . ' &#8211; ' . $siteSEO[0]['title']);
        SEOMeta::setDescription($siteSEO[0]['meta_description']);


        OpenGraph::setTitle($category['name']);
        OpenGraph::setUrl($siteSEO[0]['site_url'] . '/' . 'category/' . $slug . '/');
        OpenGraph::setDescription($siteSEO[0]['meta_description']);
        OpenGraph::addImage($siteSEO[0]['site_url'] . '/' . 'Asset/Uploads/Default/' . $siteSEO[0]['default_image']);
        OpenGraph::setSiteName($siteSEO[0]['title']);
        OpenGraph::addProperty('type', 'article');

        TwitterCard::setType('summary');
        TwitterCard::setSite($siteSEO[0]['twitter']);
        TwitterCard::setTitle($siteSEO[0]['title']);
        TwitterCard::setDescription($siteSEO[0]['meta_description']);
        TwitterCard::addImage($siteSEO[0]['site_url'] . '/' . 'Asset/Uploads/Logo/logo.png');


        return view('Frontend.Product.Category-Wise.index', compact(
            'category',
            'categories',
            'subCategories',
            'products',
        ));
    }



    public function subWiseCategory($slug, Request $request)
    {
        $segment = $request->fullurl();

        $subCategory = SubCategory::where('status', 1)->where('slug', $slug)->first() ?? abort('404');
        $brands = Brand::where('status', 1)->get()->toArray();
                // dd($brands);
        $categories = Category::where('status', 1)->where('is_featured', 1)->select('id', 'slug', 'name', 'status', 'image')->get();
        $subCategories = SubCategory::where('status', 1)->where('is_featured', 1)->select('id', 'slug', 'name', 'status', 'category_id', 'image')->with('products')->get();

        // dd($categories[6]['brands'][0]->products->count());


        if (Str::contains($segment, 'sort_by=name-ascending')) {
            $products = Product::onlineProduct()->active()->select(
                'id',
                'name',
                'meta_description',
                'regular_price',
                'sale_price',
                'main_image',
                'slug',
                'created_at',
                'brand_id',
                'status',
            )->with('brand')
                ->orderBy('name')
                ->paginate(15);
        } elseif (Str::contains($segment, 'sort_by=name-descending')) {
            $products = Product::onlineProduct()->active()->select(
                'id',
                'name',
                'meta_description',
                'regular_price',
                'sale_price',
                'main_image',
                'slug',
                'created_at',
                'brand_id',
                'status',
            )->with('brand')
                ->orderBy('name', 'DESC')
                ->paginate(15);
        } elseif (Str::contains($segment, 'sort_by=price-ascending')) {
            $products = Product::onlineProduct()->active()->select(
                'id',
                'name',
                'meta_description',
                'regular_price',
                'sale_price',
                'main_image',
                'slug',
                'created_at',
                'brand_id',
                'status',
            )->with('brand')
                ->orderBy('regular_price')
                ->paginate(15);
        } elseif (Str::contains($segment, 'sort_by=price-descending')) {
            $products = Product::onlineProduct()->active()->select(
                'id',
                'name',
                'meta_description',
                'regular_price',
                'sale_price',
                'main_image',
                'slug',
                'created_at',
                'brand_id',
                'status',
            )->with('brand')
                ->orderBy('regular_price', 'DESC')
                ->paginate(15);
        } else {
            // $products = Product::with('brand')->active()->where('sub_category_id', $subCategory)
            //     ->latest()
            //     ->paginate(6);
            $products = Product::onlineProduct()->with('brand')->active()->where('sub_category_id', $subCategory->id)
            ->latest()
            ->paginate(15);
            }
            // dd($products);
            // dd($subCategory);

        $siteSEO = SiteSetting::select('title', 'meta_title', 'site_url', 'meta_description', 'twitter', 'default_image')->get()->toArray();

        SEOMeta::setTitle($subCategory['name'] . ' &#8211; ' . $siteSEO[0]['title']);
        SEOMeta::setDescription($siteSEO[0]['meta_description']);


        OpenGraph::setTitle($subCategory['name']);
        OpenGraph::setUrl($siteSEO[0]['site_url'] . '/' . 'category/' . $slug . '/');
        OpenGraph::setDescription($siteSEO[0]['meta_description']);
        OpenGraph::addImage($siteSEO[0]['site_url'] . '/' . 'Asset/Uploads/Default/' . $siteSEO[0]['default_image']);
        OpenGraph::setSiteName($siteSEO[0]['title']);
        OpenGraph::addProperty('type', 'article');

        TwitterCard::setType('summary');
        TwitterCard::setSite($siteSEO[0]['twitter']);
        TwitterCard::setTitle($siteSEO[0]['title']);
        TwitterCard::setDescription($siteSEO[0]['meta_description']);
        TwitterCard::addImage($siteSEO[0]['site_url'] . '/' . 'Asset/Uploads/Logo/logo.png');


        return view('Frontend.Product.Sub-Category-Wise.index', compact(
            'subCategory',
            'categories',
            'subCategories',
            'products',
        ));
    }

    public function subCategoryWise($slug, Request $request)
    {
        $segment = $request->fullurl();

        $subCategory = SubCategory::where('status', 1)->where('slug', $slug)->first() ?? abort('404');
        $brands = Brand::where('status', 1)->get()->toArray();
                // dd($brands);
        $categories = Category::where('status', 1)->where('is_featured', 1)->select('id', 'slug', 'name', 'status', 'image')->get();
        $subCategories = SubCategory::where('status', 1)->where('is_featured', 1)->select('id', 'slug', 'name', 'status', 'category_id', 'image')->with('products')->get();

        // dd($categories[6]['brands'][0]->products->count());


        if (Str::contains($segment, 'sort_by=name-ascending')) {
            $products = Product::onlineProduct()->active()->select(
                'id',
                'name',
                'meta_description',
                'regular_price',
                'sale_price',
                'main_image',
                'slug',
                'created_at',
                'brand_id',
                'status',
            )->with('brand')
                ->orderBy('name')
                ->paginate(15);
        } elseif (Str::contains($segment, 'sort_by=name-descending')) {
            $products = Product::onlineProduct()->active()->select(
                'id',
                'name',
                'meta_description',
                'regular_price',
                'sale_price',
                'main_image',
                'slug',
                'created_at',
                'brand_id',
                'status',
            )->with('brand')
                ->orderBy('name', 'DESC')
                ->paginate(15);
        } elseif (Str::contains($segment, 'sort_by=price-ascending')) {
            $products = Product::onlineProduct()->active()->select(
                'id',
                'name',
                'meta_description',
                'regular_price',
                'sale_price',
                'main_image',
                'slug',
                'created_at',
                'brand_id',
                'status',
            )->with('brand')
                ->orderBy('regular_price')
                ->paginate(15);
        } elseif (Str::contains($segment, 'sort_by=price-descending')) {
            $products = Product::onlineProduct()->active()->select(
                'id',
                'name',
                'meta_description',
                'regular_price',
                'sale_price',
                'main_image',
                'slug',
                'created_at',
                'brand_id',
                'status',
            )->with('brand')
                ->orderBy('regular_price', 'DESC')
                ->paginate(15);
        } else {
            // $products = Product::with('brand')->active()->where('sub_category_id', $subCategory)
            //     ->latest()
            //     ->paginate(6);
            $products = Product::onlineProduct()->with('brand')->active()->where('sub_category_id', $subCategory->id)
            ->latest()
            ->paginate(15);
            }
            // dd($products);
            // dd($subCategory);

        $siteSEO = SiteSetting::select('title', 'meta_title', 'site_url', 'meta_description', 'twitter', 'default_image')->get()->toArray();

        SEOMeta::setTitle($subCategory['name'] . ' &#8211; ' . $siteSEO[0]['title']);
        SEOMeta::setDescription($siteSEO[0]['meta_description']);


        OpenGraph::setTitle($subCategory['name']);
        OpenGraph::setUrl($siteSEO[0]['site_url'] . '/' . 'category/' . $slug . '/');
        OpenGraph::setDescription($siteSEO[0]['meta_description']);
        OpenGraph::addImage($siteSEO[0]['site_url'] . '/' . 'Asset/Uploads/Default/' . $siteSEO[0]['default_image']);
        OpenGraph::setSiteName($siteSEO[0]['title']);
        OpenGraph::addProperty('type', 'article');

        TwitterCard::setType('summary');
        TwitterCard::setSite($siteSEO[0]['twitter']);
        TwitterCard::setTitle($siteSEO[0]['title']);
        TwitterCard::setDescription($siteSEO[0]['meta_description']);
        TwitterCard::addImage($siteSEO[0]['site_url'] . '/' . 'Asset/Uploads/Logo/logo.png');


        return view('Frontend.Product.Sub-Category-Wise.index', compact(
            'subCategory',
            'categories',
            'subCategories',
            'products',
        ));
    }

    public function viewAllJustForYou()
    {
        $justForYou=Product::onlineProduct()->where('status', 1)
          ->where('is_foryou', 1)
        ->latest()
        ->paginate(24);
        return view('Frontend.Product.View-all.just-for-you',compact('justForYou'));
    }

    public function viewAllFeaturedProduct()
    {
        $featuredProducts=Product::onlineProduct()->where('status', 1)
        ->where('is_featured',1)
        ->latest()
        ->paginate(24);
        return view('Frontend.Product.View-all.featured',compact('featuredProducts'));
    }

    public function viewAllNewArrival()
    {
        $newProducts = Product::onlineProduct()->where('status', 1)
        ->latest()
        ->with('brand')
        ->take(6)
        ->paginate(24);
            return view('Frontend.Product.View-all.new-arrival',compact('newProducts'));

    }


        public function viewAllNepaliSelling()
    {
        $nepaliProducts = Product::onlineProduct()->where('status', 1)
        ->where('section1', 1)
        ->with('brand')
        ->take(6)
        ->paginate(24);
            return view('Frontend.Product.View-all.nepali-selling',compact('nepaliProducts'));

    }


      public function viewAllTopSelling()
    {
        $topProducts = Product::onlineProduct()->where('status', 1)
        ->where('section2', 1)
        ->with('brand')
        ->take(6)
        ->paginate(24);
            return view('Frontend.Product.View-all.top-selling',compact('topProducts'));

    }

   public function brandWise($slug, Request $request)
    {
        $brand= Brand::where('status', 1)->where('slug', $slug)->select(
            'id',
            'name',
            'slug'
        )->first() ?? abort('404');
       $products = DB::table('products')
            ->select('products.*')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->where('products.status', '=', 1)
            ->where('products.product_type', '=','online')
            ->where('brands.slug', '=', $slug)->get();
        return view('Frontend.Product.Brand-Wise.index', compact(
            'products',
             'brand'
        ));
    }





}
