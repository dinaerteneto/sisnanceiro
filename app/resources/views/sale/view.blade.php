<div class="modal-dialog modal-lg" role="document" id="<?= time() ?>">

    <div class="modal-content">

        <div class="modal-header">
            <h4 class="modal-title">Visualização da venda</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">×</span>                
            </button>
        </div>

        <div class="modal-body">

            <div class="col-sm-12 mb-10">
                <div class="row">
                    <div class="col-sm-12">
                        <strong>Código:</strong> #{{ $sale['sale_code'] }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8"><strong>Cliente:</strong> {{ $sale['customer']['name'] }}</div>
                    <div class="col-sm-4 text-right">{{ $sale['sale_date'] }} as {{ $sale['sale_hour'] }}h</div>
                </div>

                <div class="row">
                    <div class="col-sm-12"><strong>Operador:</strong> {{ $sale['userCreated']['name'] }}</div>
                </div>

            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Produto</th>
                                    <th class="text-center">Quantidade</th>
                                    <th class="text-center">Subtotal</th>
                                    <th class="text-center">Descontos</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1 ?>
                                @foreach($sale['items'] as $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td class="text-left">{{ $item['product']['name'] }}</td>
                                        <td class="text-right" style="max-width:150px">{{ $item['quantity'] }}</td>
                                        <td class="text-right">{{ $item['unit_value'] }}</td>
                                        <td class="text-right">{{ $item['discount_value'] }}</td>
                                        <td class="text-right">{{ $item['total_value'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right">Total</td>
                                    <td class="text-right">{{ $sale['net_value'] }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
        </div>

    </div>
</div>
@include('layouts/_partial_scripts')