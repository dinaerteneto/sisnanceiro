

<div id="PersonAddress_{{ $modelAddress->id }}" class="content-person-address">
    <input type="hidden" name="PersonAddress[{{ $modelAddress->id }}][id]" value="{{ $modelAddress->id }}" id="PersonAddress_{{ $modelAddress->id }}_id">

    <div class="line-container"></div>
    <div class="delete-container"><a href="/person/del-address/{{ $modelAddress->id }}" class="remove-container btn btn-xs btn-danger" data-target-container="content-person-address"><i class="fa fa-times"></i></a></div>

    <div class="row">                                        
        <div class="col-sm-2">
            <div class="form-group">
                <label>Tipo</label>
                <select name="PersonAddress[{{ $modelAddress->id }}][person_address_type_id]" class="form-control select2" id="PersonAddress_{{ $modelAddress->id }}_person_address_type_id">
                    <option>Selecione</option>
                    @foreach($typeAddresses as $address)
                        <option value="{{ $address->id }}" {{ isset($modelAddress->person_address_type_id) && $address->id == $modelAddress->person_address_type_id ? 'selected' : null }}>{{ $address->name }}</option>
                    @endforeach                    
                </select>                    
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label>Cep</label>
                <input type="text" value="{{ $modelAddress->zip_code }}" id="PersonAddress_{{ $modelAddress->id }}_zip_code" name="PersonAddress[{{ $modelAddress->id }}][zip_code]" class="form-control mask-cep" placeholder="">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Logradouro</label>
                <input type="text" value="{{ $modelAddress->address }}" id="PersonAddress_{{ $modelAddress->id }}_address" name="PersonAddress[{{ $modelAddress->id }}][address]" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label>Número</label>
                <input type="text" value="{{ $modelAddress->number }}" id="PersonAddress_{{ $modelAddress->id }}_number" name="PersonAddress[{{ $modelAddress->id }}][number]" class="form-control" placeholder="">
            </div>
        </div>
    </div>
    <div class="row">                                        
        <div class="col-sm-3">
            <div class="form-group">
                <label>Complemento</label>
                <input type="text" value="{{ $modelAddress->complement }}" id="PersonAddress_{{ $modelAddress->id }}_complement" name="PersonAddress[{{ $modelAddress->id }}][complement]" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Bairro</label>
                <input type="text" value="{{ $modelAddress->district }}" id="PersonAddress_{{ $modelAddress->id }}_district" name="PersonAddress[{{ $modelAddress->id }}][district]" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Cidade</label>
                <input type="text" value="{{ $modelAddress->city }}" id="PersonAddress_{{ $modelAddress->id }}_address" name="PersonAddress[{{ $modelAddress->id }}][city]" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Referência</label>
                <input type="text" value="{{ $modelAddress->reference }}" id="PersonAddress_{{ $modelAddress->id }}_reference" name="PersonAddress[{{ $modelAddress->id }}][reference]" class="form-control" placeholder="">
            </div>
        </div>
    </div>
</div>