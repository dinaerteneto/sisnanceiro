@extends('layouts.app')

@section('content')
    <style>
    #contact {
        position: relative;
        margin: 10px auto;
        width: 100%;
        padding: 1%;
        -webkit-box-shadow: 0px 1px 22px 0px rgba(50, 50, 50, 0.75);
        -moz-box-shadow: 0px 1px 22px 0px rgba(50, 50, 50, 0.75);
        box-shadow: 0px 1px 22px 0px rgba(50, 50, 50, 0.75);
        min-height: 75vh;    
    }       

    #contact .teclas {
        position: absolute;
        bottom: 0;
        width: 100%;
        padding: 1%;
        text-align: center;
        background: #E9E9E9;
        left: 0;        
    } 

    #btn-customer, #btn-customer-clear {
        color: #fff;
        /* display: block; */
        /* float: right; */
        /* text-align: right; */
    }

    .select2-container .select2-choice {
        min-height: 45px;
        max-height: 45px;
        padding: 5px;
        text-align: left;
    }

    .select2-container .select2-choice .select2-arrow b {
        padding-top: 5px;
    }

    </style>



    <div class="modal fade" id="modal-customer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form id="form-customer" method="post" action="">
                <input type="hidden" id="Customer_name">
                <input type="hidden" id="Customer_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Selecione</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <fieldset>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Cliente</label>
                                    <input type="text" name="Customer[search]" id="Customer_search" class="form-control" placeholder="Nome/Razão social do cliente" />
                                </div>
                            </div>
                        </fieldset>                            
                    </div>
                    <div class="modal-footer">
                        <div class="align-right col-sm-6">
                            <button type="submit" class="btn btn-primary">Enviar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>          
                </div>
            </form>
        </div>
    </div>


    <section id="contact" class="home-section text-center">
        <div class="container">
            <div class="row">			
                <div class="col-sm-4">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group row">
                                <input id="Sale_search" type="text" class="form-control input-lg" placeholder="Nome ou código do produto" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        
                        <!--
                        <div class="col-sm-4">
                            <div class="row">
                                <div class="hidden-xs">
                                    <img src="https://drive.beteltecnologia.com/img/produtos_fotos/produto-sem-foto.png" width="100%">
                                </div>
                            </div>
                        </div>
                        -->

                        <div class="col-sm-12">
                            <form class="form-horizontal smart-form" id="form-add-product" method="post" action="">
                                <input type="hidden" name="Product[name]" id="Product_name">
                                <input type="hidden" name="Product[id]" id="Product_id">
                                <input type="hidden" name="Product[total_value_by_item]" id="Product_total_value_by_item">
                                <input type="hidden" name="Product[unit_measurement]" id="Product_unit_measurement">

                                <div class="form-group row">                                    
                                    <label class="col-md-6 control-label">CÓDIGO</label>
                                    <input type="text" name="Product[code]" id="Product_code" class="col-md-6 form-control input-lg" disabled>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-6 control-label">QUANTIDADE</label>
                                    <input type="text" name="Product[quant]" id="Product_quant" class="col-md-6 form-control input-lg mask-float-precision3">
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-6 control-label">VALOR UNITÁRIO</label>
                                    <input type="text" name="Product[unit_value]" id="Product_unit_value" class="col-md-6 form-control input-lg mask-currency">
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-6 control-label">DESCONTO</label>
                                    <input type="text" name="Product[discount]" id="Product_discount" class="col-md-4 form-control input-lg mask-currency">
                                    <select class="col-md-2" name="Product[discount_type]" id="Product_discount_type">
                                        <option value="R$">R$</option>
                                        <option value="%">%</option>
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-6 control-label">VALOR TOTAL</label>
                                    <input type="text" name="Product[total_value]" id="Product_total_value" class="col-md-6 form-control input-lg" disabled>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-6 control-label"></label>
                                    <input type="submit" value="ADD PRODUTO" class="btn bg-dark text-white col-md-6 input-lg">
                                </div>
                            </form>
                        </div>

                    </div>  
                    
                </div>

                <div class="col-sm-8">
                    <form id="form-sale" method="post" action="/sale/create">
                        @csrf
                        <input type="hidden" name="Sale[customer_id]" id="Sale_customer_id">
                        <input type="hidden" name="Sale[net_value]" id="Sale_net_value">
                        <input type="hidden" name="Sale[gross_value]" id="Sale_gross_value">
                        <input type="hidden" name="Sale[token]" id="Sale_token" value="<?= time() ?>">

                        <div class="text-center well bg-darken well-sm text-white" style="padding: 14px">
                            <div class="row">
                                <div class="col-sm-10">
                                    Cliente: <span id="Sale_customer_name">AO CONSUMIDOR</span>
                                </div>
                                <div class="col-sm-2">
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#modal-customer" id="btn-customer" rel="tooltip" data-placement="top" data-original-title="Alterar cliente">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>

                                    <a href="javascript:void(0)" class="d-none" id="btn-customer-clear" rel="tooltip" data-placement="top" data-original-title="Limpar cliente">
                                        <i class="fa fa-times-circle-o"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive" style="height: 307px; margin-top: -5px; overflow: auto; ">
                            
                            <table class="table table-hover table-striped" id="table-items">
                                <thead>
                                    <tr>
                                        <th class="text-left" width="35%">Produto</th>
                                        <th width="15%">Vl Unit</th>
                                        <th width="15%">Qtd.</th>
                                        <th width="15%">Vl Desc.</th>
                                        <th width="15%">Vl Total</th>
                                        <th width="5%">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>                                      
                                </tbody>
                            </table>
                            
                        </div>
                        
                        <div class="table-responsive" style="margin-top: -5px; overflow: auto; ">
                            
                            <table class="table table-hover table-striped" >
                                <thead>
                                    <tr>
                                        <th width="35%" class="text-left"></th>
                                        
                                        <th width="45%" colspan="3">
                                            Desconto
                                            <input type="text" name="Sale[discount_value]" id="Sale_discount_value" class="col-sm-4 mask-float" />
                                            <select name="Sale[discount_type]" id="Sale_discount_type" class="col-sm-2">
                                                <option value="R$" selected>R$</option>
                                                <option value="%">%</option>
                                            </select>
                                        </th>
                                        <th width="15%">R$ <span id="total-value">0,00</span></th>
                                        <th width="5%">&nbsp;</th>
                                    </tr>
                                </thead>

                            </table>

                <!--

                            <div class="form-group row">
                                <label class="col-md-6 control-label">DESC VENDA</label>
                                <input class="col-md-6 form-control input-lg mask-currency" type="text" name="Sale[discount_value]" id="Sale_discount_value" />
                            </div>
                            
                            <div class="form-group row">
                                <div class="text-center well bg-red well-sm text-white input-lg">
                                    TOTAL: R$ <span id="total-value"></span>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <input type="submit" class="btn btn-primary input-lg pull-right" value="FINALIZAR VENDA">
                            </div>
                        </div>
-->
                    </form>
                </div>
            </div>

                <div class="row">
                    <div class="hidden-xs teclas">
                        <b>F2</b> = Nova Busca <b>|</b> <b>F3</b> = Adicionar Produto <b>|</b> <b>F4</b> = Finalizar Venda <b>
                    </div>                
                </div>


        </div>
    </section>
@endsection
    
@section('scripts')
    <script type="text/javascript" src="{{ asset('assets/js/custom/Sale.js') }}"></script>    
@endsection
