@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-fw fa-tags "></i> Produtos <span>&gt; Novo</span></h1>
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
                                        <form id="w0" method="post" action="">
                                            @csrf
                                            <ul id="myTab1" class="nav nav-tabs bordered">
                                                <li class="nav-item">
                                                    <a href="#s1" data-toggle="tab" class="nav-link active show">Dados básicos</a>
                                                </li>
                                                <!--
                                                <li class="nav-item">
                                                    <a href="#s2" data-toggle="tab" class="nav-link">Ficha técnica</a>
                                                </li>
-->
                                            </ul>

                                            <div id="myTabContent1" class="tab-content padding-10">
                                                <div class="tab-pane active show" id="s1">
                                                    <fieldset>
                                                        <legend><i class="fa fa-file-text-o"></i> Dados básicos</legend>

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label>Nome</label>
                                                                    <input type="text" name="StoreProduct[name]" value="{{ $model->name }}" id="StoreProduct_name" class="form-control" placeholder="Nome do produto" />
                                                                </div>
                                                            </div>
                                                        </div>
    <!--
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Categoria</label>
                                                                    <select class="select2" name="StoreProduct[store_product_category_id]" id="StoreProduct_store_product_category_id">
                                                                        <option>Selecione</option>
                                                                        @foreach($categories as $category)
                                                                            <option value="{{ $category['id'] }}" {{ $category['id'] == $model->store_product_category_id ? 'selected' : null }}>{{ $category['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label>Marca</label>
                                                                    <select class="select2" name="StoreProduct[store_product_brand_id]" id="StoreProduct_store_product_brand_id">
                                                                        <option>Selecione</option>
                                                                        @foreach($brands as $brand)
                                                                            <option value="{{ $brand['id'] }}" {{ $brand['id'] == $model->store_product_brand_id ? 'selected' : null }}>{{ $brand['name'] }}</option>
                                                                        @endforeach                                                                
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label>Descrição</label>
                                                                    <textarea class="summernote" name="StoreProduct[description]"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
    -->
                                                        <div class="row mb-10">
                                                            <div class="col-sm-6">
                                                                <span class="onoffswitch">
                                                                    <input type="checkbox" name="StoreProduct[status]" class="onoffswitch-checkbox" id="StoreProduct_status" value="1" checked>
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
                                                                    <input type="checkbox" name="StoreProduct[sale_with_negative_stock]" class="onoffswitch-checkbox" id="StoreProduct_sale_with_negative_stock" value="1">
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

                                                    <!--
                                                    <fieldset>

                                                        <div class="row mb-10">
                                                            <div class="col-sm-12">
                                                                <span class="onoffswitch">
                                                                    <input type="checkbox" name="StoreProduct[with_attributes]" class="onoffswitch-checkbox" id="StoreProduct_with_attributes" value="1">
                                                                    <label class="onoffswitch-label" for="StoreProduct_with_attributes">
                                                                        <span class="onoffswitch-inner" data-swchon-text="SIM" data-swchoff-text="NÃO"></span>
                                                                        <span class="onoffswitch-switch"></span>
                                                                    </label>
                                                                </span>
                                                                Produto com variações
                                                            </div>
                                                        </div>

                                                    </fieldset>
                                                    -->  

                                                    <fieldset class="without-attributes">
                                                        <!--<legend><i class="fa fa-list-alt"></i> Especificações do produto</legend>-->
                                                        <div class="row">
                                                            <div class="col-sm-2 field-StoreProduct_price">
                                                                <label class="control-label" for="StoreProduct_price">Preço</label>
                                                                <input type="text" id="StoreProduct_price" class="form-control mask-currency" name="StoreProduct[price]" autocomplete="off">
                                                            </div>
                                                            <div class="col-sm-2 field-StoreProduct_price">
                                                                <label class="control-label" for="StoreProduct_cost_price">Preço de compra</label>
                                                                <input type="text" id="StoreProduct_cost_price" class="form-control mask-currency" name="StoreProduct[cost_price]" autocomplete="off" value="{{ $model['cost_price'] }}">
                                                            </div>
                                                            <div class="col-sm-2 field-StoreProduct_sku">
                                                                <label class="control-label" for="StoreProduct_sku">Código <a href="#" class="tooltips" data-original-title="Código único do produto"><i class="fa fa-info-circle"></i></a></label>
                                                                <input type="text" id="StoreProduct_sku" class="form-control" name="StoreProduct[sku]" maxlength="45">
                                                            </div>
                                                            <div class="col-sm-2 field-StoreProduct_weight">
                                                                <label class="control-label" for="StoreProduct_weight">Peso</label>
                                                                <input type="text" id="StoreProduct_weight" class="form-control mask-float-precision3" name="StoreProduct[weight]" maxlength="45">
                                                            </div>
                                                            <div class="col-sm-2 field-StoreProduct_total_in_stock">
                                                                <label class="control-label" for="StoreProduct_total_in_stock">Total no estoque</label>
                                                                <input type="text" id="StoreProduct_total_in_stock" class="form-control mask-number" name="StoreProduct[total_in_stock]">
                                                            </div>
                                                        </div>                                                    
                                                    </fieldset>

                                                    <fieldset class="with-attributes" style="display: none">
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
                                                                        <tr>
                                                                            <td>Variação 1
                                                                                <input type="hidden" name="id[]" value="0" class="id-attribute">
                                                                            </td>
                                                                            <td>
                                                                                <select name="StoreProductAttributes[0]" class="select2 select2-container store-product-attributes" id="StoreProduct-attributes-first" style="width: auto !important;">
                                                                                    <option>Selecione</option>
                                                                                    @foreach($attributes as $attribute)
                                                                                        <option value="{{ $attribute['id'] }}">{{ $attribute['name'] }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td width="50%">
                                                                                <input class="form-control" id="StoreProdutct-attributes-0-value" name="StoreProductAttributeValues[0][]" value="" >
                                                                            </td>
                                                                            <td>
                                                                                                                                                        
                                                                            </td>
                                                                        </tr>
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
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            

                                                        </div>

                                                    </fieldset>
                                                </div>

                                                <div class="tab-pane" id="s2">
                                                    <fieldset>
                                                        <legend>Ficha técnica</legend>

                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="2">Tamanho da receita</th>
                                                                    <th colspan="2">
                                                                        <div class="row">
                                                                            <div class="col-sm-4">
                                                                                <input type="text" class="form-control input-xs" value="1">
                                                                            </div>
                                                                            <div class="col-sm-8">
                                                                                <select class="form-control input-xs" style="height: 25px">
                                                                                    <option>Unidade</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </th>
                                                                    <th colspan="2">Custo da receita</th>
                                                                    <th>---</th>
                                                                </tr>
                                                                <tr>
                                                                    <th colspan="2">Tamanho da porção (kg)</th>
                                                                    <th colspan="2">
                                                                        <div class="row">
                                                                            <div class="col-sm-4">
                                                                                <input type="text" class="form-control input-xs" value="1">
                                                                            </div>
                                                                            <div class="col-sm-8">
                                                                                <select class="form-control input-xs" style="height: 25px">
                                                                                    <option>Unidade</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </th>
                                                                    <th colspan="2">Custo porção</th>
                                                                    <th>---</th>
                                                                </tr>
                                                                <tr>
                                                                    <th>Ingredientes</th>
                                                                    <th>Qtde líquida</th>
                                                                    <th>Unidade</th>
                                                                    <th>Rend %</th>
                                                                    <th>Qtde bruta</th>
                                                                    <th>Custo bruto unitário</th>
                                                                    <th>Custo total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td><input type="text" class="form-control" name="ProductDataSheet[][ingredient]" value="Pão"></td>
                                                                    <td><input type="text" class="form-control" name="ProductDataSheet[][qtd_net]" value="1,000"></td>
                                                                    <td>
                                                                        <select name="ProductDataSheet[][unity]" class="form-control">
                                                                            <option value="UN">Un.</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="text" class="form-control" name="ProductDataSheet[][qtd_gross]" value="100,00"></td>
                                                                    <td><input type="text" class="form-control" name="ProductDataSheet[][yield]" value="100,00"></td>
                                                                    <td><input type="text" class="form-control" name="ProductDataSheet[][cost_gross_unitary]" value="100,00"></td>
                                                                    <td><input type="text" class="form-control" name="ProductDataSheet[][cost_total]" value="100,00"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td><input type="text" class="form-control" name="ProductDataSheet[][ingredient]" value="Hamburguer"></td>
                                                                    <td><input type="text" class="form-control" name="ProductDataSheet[][qtd_net]" value="0,160"></td>
                                                                    <td>
                                                                        <select name="ProductDataSheet[][unity]" class="form-control">
                                                                            <option value="Kg">Kg.</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="text" class="form-control" name="ProductDataSheet[][qtd_gross]" value="100,00"></td>
                                                                    <td><input type="text" class="form-control" name="ProductDataSheet[][yield]" value="0,160"></td>
                                                                    <td><input type="text" class="form-control" name="ProductDataSheet[][cost_gross_unitary]" value="13,00"></td>
                                                                    <td><input type="text" class="form-control" name="ProductDataSheet[][cost_total]" value="2,08"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="6" class="align-right">
                                                                        <button id="add-address" type="button" class="btn btn-xs bg-blue-dark text-white" rel="tooltip" data-placemente="top" data-original-title="Adicionar mais um ingrediente">
                                                                            <i class="fa fa-plus-circle"></i> ingrediente
                                                                        </button>
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="6" class="align-right">
                                                                        Total
                                                                    </td>  
                                                                    <td>510,00</td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>

                                                        <div class="row mb-10">
                                                            <div class="col-sm-3">
                                                                <label class="control-label">Imposto (%)</label>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label class="control-label">Despesa (%)</label>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label class="control-label">Lucro (%)</label>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label class="control-label">Custo do lanche (%)</label>
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <label class="control-label">Modo de preparo</label>
                                                                <textarea class="summernote" name="ProductDataSheet[][method_preparation]"></textarea>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>

                                            
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
@stop