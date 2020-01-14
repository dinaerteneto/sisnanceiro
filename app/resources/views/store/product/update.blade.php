@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-fw fa-tags "></i> Produtos <span>&gt; {{ $model['name'] }}</span></h1>
    </div>
</div>

<div class="d-flex w-100">
            <section id="widget-grid" class="w-100">
                <div class="row">
                    <article class="col-12 sortable-grid ui-sortable">

                            <div class="jarviswidget jarviswidget-color-blue-dark" id="wid-id-0" role="widget">

                                <header role="heading" class="ui-sortable-handle">
                                    <div class="widget-header">
                                        <span class="widget-icon"> <i class="fa fa-tags"></i> </span>
                                        <h2>Produto</h2>
                                    </div>

                                </header>

                                <!-- widget div-->
                                <div role="content">

                                    <!-- widget content -->
                                    <div class="widget-body">

                                        <!-- this is what the user will see -->
                                        <form id="w0" method="post" action="/store/product/update/{{ $model['id'] }}">
                                            @csrf
                                            <input type="hidden" name="StoreProduct[id]" value="{{ $model['id'] }}">
                                            <input type="hidden" id="StoreProduct-with_attributes" value="{{ count($model['subproducts']) ? 1 : 0 }}">

                                            <fieldset>
                                                <legend><i class="fa fa-file-text-o"></i> Dados básicos</legend>

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Nome</label>
                                                            <input type="text" name="StoreProduct[name]" value="{{ $model['name'] }}" id="StoreProduct_name" class="form-control" placeholder="Nome do produto" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Categoria</label>
                                                            <select class="select2" name="StoreProduct[store_product_category_id]" id="StoreProduct_store_product_category_id">
                                                                <option value="">Selecione</option>
                                                                @foreach($categories as $category)
                                                                    <option value="{{ $category['id'] }}" {{ $category['id'] == $model['category']['id'] ? 'selected' : null }}>{{ $category['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Marca</label>
                                                            <select class="select2" name="StoreProduct[store_product_brand_id]" id="StoreProduct_store_product_brand_id">
                                                                <option value="">Selecione</option>
                                                                @foreach($brands as $brand)
                                                                    <option value="{{ $brand['id'] }}" {{ $brand['id'] == $model['brand']['id'] ? 'selected' : null }}>{{ $brand['name'] }}</option>
                                                                @endforeach                                                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Descrição</label>
                                                            <textarea class="summernote" name="StoreProduct[description]">{{ $model['description'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
-->
                                                <div class="row mb-10">
                                                    <div class="col-sm-6">
                                                        <span class="onoffswitch">
                                                            <input type="checkbox" name="StoreProduct[status]" class="onoffswitch-checkbox" id="StoreProduct_status" value="1" @if(!empty($model['status'])) checked @endif >
                                                            <label class="onoffswitch-label" for="StoreProduct_status">
                                                                <span class="onoffswitch-inner" data-swchon-text="SIM" data-swchoff-text="NÃO"></span>
                                                                <span class="onoffswitch-switch"></span>
                                                            </label>
                                                        </span>
                                                        Ativo
                                                    </div>          
                                                    <!--
                                                    <div class="col-sm-6">
                                                        <span class="onoffswitch">
                                                            <input type="checkbox" name="StoreProduct[sale_with_negative_stock]" class="onoffswitch-checkbox" id="StoreProduct_sale_with_negative_stock" value="1" @if(!empty($model['sale_with_negative_stock'])) checked @endif>
                                                            <label class="onoffswitch-label" for="StoreProduct_sale_with_negative_stock">
                                                                <span class="onoffswitch-inner" data-swchon-text="SIM" data-swchoff-text="NÃO"></span>
                                                                <span class="onoffswitch-switch"></span>
                                                            </label>
                                                        </span>
                                                        Venda com estoque negativo
                                                    </div>     
                                                    -->
                                                </div>

                                            </fieldset>

                                            <fieldset class="without-attributes">
                                                <legend><i class="fa fa-list-alt"></i> Especificações do produto</legend>
                                                <div class="row">
                                                    <div class="col-sm-2 field-StoreProduct_price">
                                                        <label class="control-label" for="StoreProduct_price">Preço</label>
                                                        <input type="text" id="StoreProduct_price" class="form-control mask-currency" name="StoreProduct[price]" autocomplete="off" value="{{ $model['price'] }}">
                                                    </div>
                                                    <div class="col-sm-2 field-StoreProduct_price">
                                                        <label class="control-label" for="StoreProduct_cost_price">Preço de compra</label>
                                                        <input type="text" id="StoreProduct_cost_price" class="form-control mask-currency" name="StoreProduct[cost_price]" autocomplete="off" value="{{ $model['cost_price'] }}">
                                                    </div>
                                                    <div class="col-sm-2 field-StoreProduct_sku">
                                                        <label class="control-label" for="StoreProduct_sku">Código <a href="#" class="tooltips" data-original-title="Código único do produto"><i class="fa fa-info-circle"></i></a></label>
                                                        <input type="text" id="StoreProduct_sku" class="form-control" name="StoreProduct[sku]" maxlength="45" value="{{ $model['sku'] }}">
                                                    </div>
                                                    <div class="col-sm-2 field-StoreProduct_weight">
                                                        <label class="control-label" for="StoreProduct_weight">Peso</label>
                                                        <input type="text" id="StoreProduct_weight" class="form-control mask-float-precision3" name="StoreProduct[weight]" maxlength="45" value="{{ $model['weight'] }}">
                                                    </div>
                                                    <div class="col-sm-2 field-StoreProduct_total_in_stock">
                                                        <label class="control-label" for="StoreProduct_total_in_stock">Total no estoque</label>
                                                        <input type="text" id="StoreProduct_total_in_stock" class="form-control mask-number" name="StoreProduct[total_in_stock]" value="{{ $model['total_in_stock'] }}">
                                                    </div>
                                                </div>                                                    
                                            </fieldset>
            
                                            <fieldset class="with-attributes">
                                                <legend><i class="fa fa-list-alt"></i> Variações</legend>

                                                <div class="row mb-10">
                                                    <div class="col-sm-12 mb-10">
                                                        <table class="table" id="table-attributes">
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th>Tipo de variação</th>
                                                                    <th>Variações <a href="#" class="tooltips" data-original-title="Escreva a variação e aperte enter para inserir"><i class="fa fa-info-circle"></i></a></th>
                                                                    <th>&nbsp;</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if(count($attrVariables) > 0)
                                                                    @foreach($attrVariables as $key => $attr)
                                                                    <tr>
                                                                        <td>Variação 1</td>
                                                                        <td>
                                                                            <select name="StoreProductAttributes[{{ $key }}]" class="select2 select2-container store-product-attributes" id="StoreProduct-attributes-first" style="width: auto !important;">
                                                                                <option value="">Selecione</option>
                                                                                @foreach($attributes as $attribute)
                                                                                    <option value="{{ $attribute['id'] }}" @if( isset($attr['id']) && $attr['id']  ===  $attribute['id'] ) selected @endif>
                                                                                        {{ $attribute['name'] }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td width="50%">
                                                                            @if( isset($attr['store_product_attribute_id']) )
                                                                            <input class="form-control" name="StoreProductAttributeValues[{{ $attr['store_product_attribute_id'] }}][]" value="<?= implode(',', $attr['values']) ?>" >
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <a class="del-attribute"><i class="fa fa-times-circle"></i></a>                                                  
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                            
                                                        </table>
                                                        <button class="btn btn-sm btn-primary" id="add-attribute">Adicionar variação</button>
                                                    </div>
                                                    
                                                    <div class="col-sm-12">
                                                        <table class="table" id="table-subproducts">
                                                            <thead>
                                                                <tr>
                                                                    <th width=""></th>
                                                                    <th>Variações </th>
                                                                    <th width="">Preço de venda</th>
                                                                    <th>Peso</th>
                                                                    <th>Código <a href="#" class="tooltips" data-original-title="Código único do produto"><i class="fa fa-info-circle"></i></a></th>
                                                                    <th width="">Quant. estoque</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($model['subproducts'] as $key => $subproduct)
                                                                @if($key !== 'variables' && count($subproduct['attributes']) > 0) 
                                                                <tr>
                                                                    <td>
                                                                        <input type="checkbox" name="subproduct-checked[{{ $subproduct['id_attribute'] }}][checkbox]" value="{{ $subproduct['id'] }}" class="subproduct-checked" checked>
                                                                        @foreach($subproduct['attributes'] as $attribute)
                                                                            <input type="hidden" name="subproduct[{{ $subproduct['id_attribute'] }}][product_attribute][]" value="{{ $attribute['value'] }}">
                                                                        @endforeach
                                                                    </td>
                                                                    <td>
                                                                        <?= str_replace('-', ' , ', $subproduct['id_attribute']) ?>
                                                                    </td>
                                                                    <td><input type="text" name="subproduct[{{ $subproduct['id_attribute'] }}][price]" class="form-control mask-currency" value="{{ $subproduct['price'] }}"></td>
                                                                    <td><input type="text" name="subproduct[{{ $subproduct['id_attribute'] }}][weight]" class="form-control float-precision-3" value="{{ $subproduct['weight'] }}"></td>
                                                                    <td>
                                                                        <input type="text" name="subproduct[{{ $subproduct['id_attribute'] }}][sku]" class="form-control subproduct-sku" value="{{ $subproduct['sku'] }}">
                                                                        <input type="hidden" name="subproduct[{{ $subproduct['id_attribute'] }}][id]" value="{{ $subproduct['id'] }}" class="subproduct-id" >
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" value="{{ $subproduct['total_in_stock'] }}" class="form-control" disabled >
                                                                        <input type="hidden" name="subproduct[{{ $subproduct['id_attribute'] }}][total_in_stock]" value="{{ $subproduct['total_in_stock'] }}">
                                                                    </td>                                                                    
                                                                </tr>
                                                                @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>

                                            </fieldset>
                                            
                                            <div class="row" style="margin-top: 10px">
                                                <div class="col-sm-12">
                                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                    <!-- end widget content -->
                                </div>
                                <!-- end widget div -->
                            </div>
                        <!-- end widget -->
                    </article>
                </div>
                <!-- end row -->
            </section>
        </div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('assets/js/libs/jquery-easy-ui-1.5.1/jquery.easyui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/custom/Store.js') }}"></script>
<link href="{{ asset('assets/js/libs/jquery-easy-ui-1.5.1/themes/bootstrap/tagbox.css') }}" rel="stylesheet">
<link href="{{ asset('assets/js/libs/jquery-easy-ui-1.5.1/themes/icon.css') }}" rel="stylesheet">
<script type="text/javascript">
$(document).ready(function(){
    Store.addSubproducts();
});
</script>
@stop