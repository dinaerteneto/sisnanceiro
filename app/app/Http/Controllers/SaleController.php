<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;
use Sisnanceiro\Models\Sale;
use Sisnanceiro\Services\CartService;
use Sisnanceiro\Services\CustomerService;
use Sisnanceiro\Services\SaleService;
use Sisnanceiro\Services\StoreProductService;
use Sisnanceiro\Transformers\CartTransformer;
use Sisnanceiro\Transformers\SaleCustomerTransformer;
use Sisnanceiro\Transformers\SaleStoreProductTransformer;
use Sisnanceiro\Transformers\SaleTransformer;

class SaleController extends Controller
{

    public function __construct(SaleService $saleService, StoreProductService $storeProductService, CustomerService $customerService, CartService $cartService)
    {
        $this->saleService = $saleService;
        $this->storeProductService = $storeProductService;
        $this->customerService = $customerService;
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $records = $this->saleService->getAll();
            $dt = datatables()->of($records);
            return $dt->filterColumn('firstname', function ($query, $keyword) {
                $query->whereRaw("firstname LIKE ?", ["%{$keyword}%"]);
            })->make(true);
        }

        $tempItems = fractal($this->cartService->getAll(), new CartTransformer());
        if ($tempItems) {
            $tempItems = $tempItems->toArray()['data'];
        }

        return view('/sale/index', compact('tempItems'));
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $model = $this->saleService->create($postData);
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Erro na tentativa de criar a venda.', 'errors' => $model->getErrors()]);
                Log::debug(json_encode($request->all()));
                return redirect("sale/index");
            } else {
                return redirect("sale/ask/{$model->id}");
            }
        }
        return view('sale/create');
    }

    public function update(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $model = $this->saleService->findBy('id', $id);
            $postData = $request->all();

            $model = $this->saleService->update($model, $postData, 'update');
            if (method_exists($model, 'getErrors') && $model->getErrors()) {
                $request->session()->flash('error', ['message' => 'Falha na tentativa de alterar a venda.']);
            } else {
                $request->session()->flash('success', ['message' => 'Venda alterada com sucesso.']);
            }
            return redirect('sale');
        } else {
            $statues = Sale::getStatues();

            $model = $this->saleService->find($id);
            $manager = new Manager();
            $manager->setSerializer(new DataArraySerializer());
            $resource = new Item($model, new SaleTransformer());
            $sale = $manager->createData($resource)->toArray()['data'];

            return view('sale/update', compact('sale', 'statues'));
        }
    }

    public function cancel(Request $request, $id)
    {
        $model = $this->saleService->find($id);
        $data = array_replace($model->getAttributes(), ['status' => Sale::STATUS_CANCELED]);
        $model = $this->saleService->update($model, $data, 'update');
        if (method_exists($model, 'getErrors') && $model->getErrors()) {
            return $this->apiSuccess(['success' => false]);
        } else {
            $request->session()->flash('success', ['message' => 'Venda cancelada com sucesso.']);
            return $this->apiSuccess(['success' => true]);
        }

    }

    public function ask($id)
    {
        $model = $this->saleService->find($id);
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());
        $resource = new Item($model, new SaleTransformer());
        $sale = $manager->createData($resource)->toArray()['data'];
        return View('sale/ask', compact('sale'));
    }

    public function coupon($id)
    {
        $model = $this->saleService->find($id);
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());
        $resource = new Item($model, new SaleTransformer());
        $sale = $manager->createData($resource)->toArray()['data'];
        return view('sale/coupon', compact('sale'));
    }

    function print($id) {
        $model = $this->saleService->find($id);
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());
        $resource = new Item($model, new SaleTransformer());
        $sale = $manager->createData($resource)->toArray()['data'];
        return view('sale/print', compact('sale'));
    }

    public function view($id)
    {
        $model = $this->saleService->find($id);
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());
        $resource = new Item($model, new SaleTransformer());
        $sale = $manager->createData($resource)->toArray()['data'];
        return view('sale/view', compact('sale'));
    }

    public function copy($id)
    {
        $model = $this->saleService->find($id);
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());
        $resource = new Item($model, new SaleTransformer());
        $sale = $manager->createData($resource)->toArray()['data'];
        return view('sale/copy', compact('sale'));
    }

    public function searchItem(Request $request)
    {
        $search = $request->get('term');
        $records = $this->storeProductService->getAll($search)->get();
        if ($records) {
            $recordTransform = fractal($records, new SaleStoreProductTransformer());

            $return = ['items' => $recordTransform->toArray()['data'], 'total_count' => count($records)];
            return Response::json($return);
        }
    }

    public function searchCustomer(Request $request)
    {
        $search = $request->get('term');
        $records = $this->customerService->getAll($search)->get();
        if ($records) {
            $recordTransform = fractal($records, new SaleCustomerTransformer());

            $return = ['items' => $recordTransform->toArray()['data'], 'total_count' => count($records)];
            return Response::json($return);
        }
    }

    public function delete($id)
    {
        if ($this->saleService->destroy($id)) {
            return $this->apiSuccess(['success' => true, 'remove-tr' => true]);
        }
    }

    public function createTemp($token)
    {
        $tempItems = fractal($this->cartService->getByToken($token), new CartTransformer());
        if ($tempItems) {
            $tempItems = (object) $tempItems->toArray()['data'];
        }
        return view('/sale/create', compact('tempItems'));
    }

    public function addTempItem(Request $request)
    {
        $this->cartService->addItem($request->all());
        return $this->apiSuccess(['success' => true]);
    }

    public function delTempItem(Request $request)
    {
        $token = $request->get('__token');
        $productId = $request->get('id');

        $return = $this->cartService->delItem($token, $productId);
        return $this->apiSuccess(['success' => $return]);
    }

    public function delTemp($token)
    {
        if ($this->cartService->deleteByToken($token)) {
            return $this->apiSuccess(['success' => true]);
        }
        return $this->apiSuccess(['success' => error]);
    }

}
