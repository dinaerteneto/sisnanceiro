<div class="modal-dialog modal-lg" role="document">

    <div class="modal-content">

        <form id="bank-category-form" class="bank-category-form" method="post" action="{{ $action }}">
        @csrf

        <div class="modal-header">
            <h4 class="modal-title">{{ $title }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">×</span>                
            </button>
            
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="Bank_Category_name">Categoria</label>
                <input type="text" name="BankCategory[name]" value="{{ $model->name }}" id="Bank_Category_name" class="form-control" />
            </div>

            @if(!empty($parent_category_id))
            <div class="form-group">
                <label for="Bank_Category_parent_category_id">Categoria pai</label>
                <select name="BankCategory[parent_category_id]" id="BankCategory_parent_category_id" class="form-control">
                    @foreach($categories as $category)
                        <option value="{{ $category['id'] }}" {{ $category['id'] == $parent_category_id ? 'selected' : null }}>{{ $category['name'] }}</option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Enviar</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>

        </form>

    </div>

</div>

<script src="{{ asset('assets/js/custom/BankCategory.js') }}"></script>