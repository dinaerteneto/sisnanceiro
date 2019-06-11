<div id="PersonContact_{{ $modelContact->id }}" class="content-person-contact">
    <input type="hidden" name="PersonContact[{{ $modelContact->id }}][id]" value="{{ $modelContact->id }}" id="PersonContact[{{ $modelContact->id }}]_id">

    <div class="line-container"></div>
    <div class="delete-container"><a href="/person/del-contact/{{ $modelContact->id }}" class="remove-container btn btn-xs btn-danger" data-target-container="content-person-contact"><i class="fa fa-times"></i></a></div>

    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label>Tipo</label>
                <input type="text" id="PersonContact_{{ $modelContact->id }}_person_contact_type_id" name="PersonContact[{{ $modelContact->id }}][person_contact_type_id]" class="form-control" placeholder="">
            </div>
        </div>                                    
        <div class="col-sm-3">
            <div class="form-group">
                <label>Nome</label>
                <input type="text" id="PersonContact_{{ $modelContact->id }}_name" name="PersonContact[{{ $modelContact->id }}][name]" class="form-control" placeholder="">
            </div>
        </div>                                    
        <div class="col-sm-3">
            <div class="form-group">
                <label>Contato</label>
                <input type="text" id="PersonContact_{{ $modelContact->id }}_value" name="PersonContact[{{ $modelContact->id }}][value]" class="form-control" placeholder="">
            </div>
        </div>                                    
        <div class="col-sm-3">
            <div class="form-group">
                <label>Observação</label>
                <input type="text" id="PersonContact_{{ $modelContact->id }}_description" name="PersonContact[{{ $modelContact->id }}][description]" class="form-control" placeholder="">
            </div>
        </div>      
    </div>

</div>