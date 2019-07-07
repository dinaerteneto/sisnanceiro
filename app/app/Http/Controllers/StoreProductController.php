<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sisnanceiro\Components\Cartesian;
use Sisnanceiro\Models\StoreProduct;
use Sisnanceiro\Services\StoreProductAttributeService;
use Sisnanceiro\Services\StoreProductBrandService;
use Sisnanceiro\Services\StoreProductCategoryService;
use Sisnanceiro\Services\StoreProductService;
use Sisnanceiro\Transformers\StoreProductAttributeTransform;
use Sisnanceiro\Transformers\StoreProductBrandTransform;
use Sisnanceiro\Transformers\StoreProductCategoryTransform;
use Sisnanceiro\Transformers\StoreProductTransform;

class StoreProductController extends Controller
{

    private $storeProductService;
    private $storeProductCategoryService;
    private $storeProductBrandService;
    private $storeProductAttributeService;

    public function __construct(
        StoreProductService $storeProductService,
        StoreProductCategoryService $storeProductCategoryService,
        StoreProductBrandService $storeProductBrandService,
        StoreProductAttributeService $storeProductAttributeService
    ) {
        $this->storeProductService          = $storeProductService;
        $this->storeProductCategoryService  = $storeProductCategoryService;
        $this->storeProductBrandService     = $storeProductBrandService;
        $this->storeProductAttributeService = $storeProductAttributeService;
    }

    public function create(Request $request)
    {
        $model = new StoreProduct;
        if ($request->isMethod('post')) {
            $data = $request->all();
            if ($model = $this->storeProductService->store($data, 'create')) {
                $request->session()->flash('success', ['message' => 'Produto incluído com sucesso.']);
            } else {
                $request->session()->flash('error', ['message' => 'Falha na tentativa de incluir o produto.']);
            }
            return redirect("/store/product/update/{$model->id}");
        } else {
            $categories          = $this->storeProductCategoryService->all();
            $transformCategories = fractal($categories, new StoreProductCategoryTransform());
            $categories          = $transformCategories->toArray()['data'];

            $brands          = $this->storeProductBrandService->all();
            $transformBrands = fractal($brands, new StoreProductBrandTransform());
            $brands          = $transformBrands->toArray()['data'];

            $attributes         = $this->storeProductAttributeService->all();
            $transformAttribute = fractal($attributes, new StoreProductAttributeTransform());
            $attributes         = $transformAttribute->toArray()['data'];

            return view('store/product/create', compact('model', 'categories', 'brands', 'attributes'));
        }
    }

    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $search  = $request->get('search');
            $records = $this->storeProductService->getAll();
            $dt      = datatables()->of($records);
            return $dt->filterColumn('name', function ($query, $keyword) {
                $query->whereRaw("name LIKE ?", ["%{$keyword}%"]);
            })->make(true);
        }
        return view('store/product/index');
    }

    public function update(Request $request, $id)
    {
        $model = $this->storeProductService->find($id);
        if ($request->isMethod('post')) {
            $data = $request->all();
            if ($model = $this->storeProductService->store($data, 'update')) {
                $request->session()->flash('success', ['message' => 'Produto alterado com sucesso.']);
            } else {
                $request->session()->flash('error', ['message' => 'Falha na tentativa de alterar o produto.']);
            }
            return redirect("/store/product/update/{$model->id}");
        } else {
            $categories          = $this->storeProductCategoryService->all();
            $transformCategories = fractal($categories, new StoreProductCategoryTransform());
            $categories          = $transformCategories->toArray()['data'];

            $brands          = $this->storeProductBrandService->all();
            $transformBrands = fractal($brands, new StoreProductBrandTransform());
            $brands          = $transformBrands->toArray()['data'];

            $attributes         = $this->storeProductAttributeService->all();
            $transformAttribute = fractal($attributes, new StoreProductAttributeTransform());
            $attributes         = $transformAttribute->toArray()['data'];

            $transformProduct = fractal($model, new StoreProductTransform());
            $model            = $transformProduct->toArray()['data'];

            $attrVariables = [];
            if (count($model['subproducts']) > 0) {
                $attrVariables = $model['subproducts']['variables'];
            }
            return view('store/product/update', compact('model', 'categories', 'brands', 'attributes', 'attrVariables'));
        }
    }

    /**
     * return a array subproducts based on attributes and values attributes
     * @return void
     */
    public function addSubproduct(Request $request)
    {
        $post           = $request->post('StoreProductAttributeValues');
        $postFormValues = $request->post('subproduct');
        $postChecked    = $request->post('subproduct-checked');

        $attributes  = Cartesian::build($post);
        $subproducts = [];
        if ($attributes) {
            foreach ($attributes as $key => $subproduct) {

                $keySubproduct = implode('-', $subproduct);
                $formData      = [
                    'id'             => '',
                    'price'          => '0,00',
                    'weight'         => '0,000',
                    'sku'            => '',
                    'total_in_stock' => 0,
                ];

                if (isset($postFormValues[$keySubproduct])) {

                    $checkbox = true;
                    if (!isset($postChecked[$keySubproduct]['checkbox'])) {
                        $checkbox = false;
                    }

                    $formData = [
                        'id'             => !empty($postFormValues[$keySubproduct]['id']) ? $postFormValues[$keySubproduct]['id'] : '',
                        'price'          => $postFormValues[$keySubproduct]['price'],
                        'weight'         => $postFormValues[$keySubproduct]['weight'],
                        'sku'            => !empty($postFormValues[$keySubproduct]['sku']) ? $postFormValues[$keySubproduct]['sku'] : '',
                        'total_in_stock' => $postFormValues[$keySubproduct]['total_in_stock'],
                        'checked'        => $checkbox,
                    ];
                }
                $subproducts[$key]['key']    = $keySubproduct;
                $subproducts[$key]['values'] = $subproduct;
                $subproducts[$key]['form']   = $formData;
            }
        }
        return Response::json($subproducts);
    }

    public function delete($id)
    {
        if ($this->storeProductService->destroy($id)) {
            return $this->apiSuccess(['success' => true, 'remove-tr' => true]);
        }
    }    
}
