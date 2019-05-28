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
                                            <form>
                                                @csrf

                                                <fieldset>
                                                    <legend><i class="fa fa-file-text-o"></i> Dados básicos</legend>

                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Nome</label>
                                                                <input type="text" name="Product[name]" value="{{ $model->name }}" id="Product_name" class="form-control" placeholder="Nome do produto" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>Categoria</label>
                                                                <select class="form-control">
                                                                    <option value="">Selecione</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>Marca</label>
                                                                <select class="form-control">
                                                                    <option value="">Selecione</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Descrição</label>
                                                                <div class="summernote"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>

                                                <fieldset>
                                                    <legend><i class="fa fa-image"></i> Fotos do produto</legend>
                                                    <div class="row">
                                                        <div class="col-sm-12 mb-10">
                                                            <div id="upload-images" class="dropzone dz-clickable" style="min-height: 100px !important">
                                                                <div class="dz-default dz-message">
                                                                    <span>
                                                                        <h4>Clique ou arraste as imagens aqui para fazer upload</h4>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-10">
                                                        <div class="col-sm-12">
                                                            <span class="onoffswitch">
                                                                <input type="checkbox" name="start_interval" class="onoffswitch-checkbox" id="st3">
                                                                <label class="onoffswitch-label" for="st3">
                                                                    <span class="onoffswitch-inner" data-swchon-text="SIM" data-swchoff-text="NÃO"></span>
                                                                    <span class="onoffswitch-switch"></span>
                                                                </label>
                                                            </span>
                                                            Produto com variações
                                                        </div>
                                                    </div>

                                                </fieldset>

                                                <fieldset>
                                                    <legend><i class="fa fa-list-alt"></i> Variações</legend>

                                                    <div class="row mb-10">
                                                        <div class="col-sm-12">
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
                                                                        <td>Variação 1</td>
                                                                        <td>
                                                                            <select class="select2-container store-product-attributes"></select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-group">
                                                                        </td>
                                                                        <td>
                                                                            <a class="del-attribute"><i class="fa fa-times-circle"></i></a>                                                                             
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                             <button class="btn btn-sm btn-primary" id="add-attribute">Adicionar variação</button>
                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-3 field-storeproducts-price">
                                                            <label class="control-label" for="storeproducts-price">Preço</label>
                                                            <input type="text" id="storeproducts-price" class="form-control mask-currency" name="StoreProducts[price]" autocomplete="off">
                                                        </div>
                                                        <div class="col-sm-3 field-storeproducts-sku">
                                                            <label class="control-label" for="storeproducts-sku">SKU <a href="#" class="tooltips" data-original-title="Código único do produto"><i class="fa fa-info-circle"></i></a></label>
                                                            <input type="text" id="storeproducts-sku" class="form-control" name="StoreProducts[sku]" maxlength="45">
                                                        </div>
                                                        <div class="col-sm-3 field-storeproducts-total_in_stock">
                                                            <label class="control-label" for="storeproducts-total_in_stock">Total no estoque</label>
                                                            <input type="text" id="storeproducts-total_in_stock" class="form-control mask-number" name="StoreProducts[total_in_stock]">
                                                        </div>
                                                    </div>
                                                </fieldset>

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
