<form id="form1" method="post" action="">
    @csrf
    <input type="hidden" name="Supplier[id]" value="{{ isset($model) ? $model->id : null }}">

    <fieldset>
        <legend><i class="fa fa-list-alt"></i> Dados gerais</legend>
        <div class="row">
            <div class="col-sm-2">
                <div class="form-group">
                    <label>Tipo de fornecedor</label>
                    <select name="Supplier[physical]" id="Supplier_physical" class="form-control select2">
                        <option value="">Selecione</option>
                        <option value="1" {{  isset($model) && $model->physical == "1" ? 'selected' : null}}>Física</option>
                        <option value="0" {{  isset($model) && $model->physical != "1" ? 'selected' : null}}>Jurídica</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" value="{{ isset($model) ? $model->firstname : null }}" id="Supplier_name" name="Supplier[firstname]" class="form-control" placeholder="">
                </div>
            </div>
            <div class="col-sm-4" id="container-lastname">
                <div class="form-group">
                    <label>Sobrenome</label>
                    <input type="text" value="{{ isset($model) ? $model->lastname : null }}" id="Supplier_lastname" name="Supplier[lastname]" class="form-control" placeholder="">
                </div>
            </div>
            <div class="col-sm-2" id="container-birthdate">
                <div class="form-group">
                    <label>Data nascimento</label>
                    <input type="text" value="{{ isset($model) ? $model->birthdate : null }}" id="Supplier_birthdate" name="Supplier[birthdate]" class="form-control datepicker mask-date" data-dateformat="dd/mm/yy" placeholder="">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2" id="container-gender">
                <div class="form-group">
                    <label>Sexo</label>
                    <select name="Supplier[gender]" id="Supplier_gender" class="form-control select2">
                        <option value="">Selecione</option>
                        <option value="M" {{ isset($model) && $model->gender == 'M' ? 'selected' : null}}>Masculino</option>
                        <option value="F" {{ isset($model) && $model->gender == 'F' ? 'selected' : null}}>Feminino</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>CPF</label>
                    <input type="text" value="{{ isset($model) ? $model->cpf : null }}" id="Supplier_cpf" name="Supplier[cpf]" class="form-control mask-cpf" placeholder="">
                </div>
            </div>
            <div class="col-sm-4" id="container-rg">
                <div class="form-group">
                    <label>RG</label>
                    <input type="text" value="{{ isset($model) ? $model->rg : null }}" id="Supplier_rg" name="Supplier[rg]" class="form-control" placeholder="">
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend><i class="fa fa-map-marker"></i> Endereços</legend>
        <div id="container-address">
            @if(isset($addresses))
                @foreach($addresses as $modelAddress)
                    @include('person/_form_address', compact('modelAddress', 'typeAddresses'))
                @endforeach
            @else
                @include('person/_form_address', compact('modelAddress', 'typeAddresses'))
            @endif

        </div>
        <div class="row">
            <div class="col-sm-12 col-lg-12 col-md-12 align-right">
                <button id="add-address" type="button" class="btn bg-blue-dark text-white">
                    <i class="fa fa-plus-circle"></i> Inserir novo endereço
                </button>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend><i class="fa fa-phone"></i> Contatos</legend>
        <div id="container-contact">
            @if(isset($contacts))
                @foreach($contacts as $modelContact)
                    @include('person/_form_contact', compact('modelContact', 'typeContacts'))
                @endforeach
            @else
                @include('person/_form_contact', compact('modelContact', 'typeContacts'))
            @endif
        </div>
        <div class="row">
            <div class="col-sm-12 col-lg-12 col-md-12 align-right">
                <button id="add-contact" type="button" class="btn bg-blue-dark text-white">
                    <i class="fa fa-plus-circle"></i> Inserir novo contato
                </button>
            </div>
        </div>
    </fieldset>

    <input type="submit" value="Salvar" class="btn bg-blue-dark text-white">
</form>
