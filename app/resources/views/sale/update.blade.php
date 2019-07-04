<div class="modal-dialog modal-lg" role="document" id="<?= time() ?>">

    <div class="modal-content">

        <div class="modal-header">
            <h4 class="modal-title">Alteração da venda</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">×</span>                
            </button>
        </div>
        
        <form method="post" action="/sale/update/{{ $model->id }}">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Cód da venda</label>
                            #{{ $model->company_sale_code }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="Sale[status]" class="form-control">
                                <option>Selecione</option>
                                @foreach($statues as $key => $status)
                                    <option value="{{ $key }}" {{ isset($model->status) && $key == $model->status ? 'selected' : null }}>{{ $status }}</option>
                                @endforeach                    
                            </select>
                        </div>
                    </div>
                </div>                
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Alterar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>

        </form>
    </div>
</div>