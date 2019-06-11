<form id="form1" method="post" action="/customer/create">
    @csrf

    <fieldset>
        <legend><i class="fa fa-list-alt"></i> Dados gerais</legend>
        <div class="row">
            <div class="col-sm-2">
                <div class="form-group">
                    <label>Tipo de cliente</label>
                    <select name="Customer[physical]" id="Customer_physical" class="form-control select2">
                        <option>Selecione</option>
                        <option value="1">Física</option>
                        <option value="0">Jurídica</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" id="Customer_name" name="Customer[firstname]" class="form-control" placeholder="Nome do cliente">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Sobrenome</label>
                    <input type="text" id="Customer_physical" name="Customer[lastname]" class="form-control" placeholder="Sobrenome">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label>Data nascimento</label>
                    <input type="text" id="Customer_birthdate" name="Customer[birthdate]" class="form-control datepicker" data-dateformat="dd/mm/yy" placeholder="Data de nascimento">
                </div>
            </div>                                        
        </div>

        <div class="row">
            <div class="col-sm-2">
                <div class="form-group">
                    <label>Sexo</label>
                    <input type="text" id="Customer_gender" name="Customer[gender]" class="form-control" placeholder="Sexo">
                </div>
            </div>                                                                      
            <div class="col-sm-4">
                <div class="form-group">
                    <label>CPF</label>
                    <input type="text" id="Customer_cpf" name="Customer[cpf]" class="form-control mask-cpf" placeholder="CPF">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>RG</label>
                    <input type="text" id="Customer_rg" name="Customer[rg]" class="form-control" placeholder="RG">
                </div>
            </div>          
        </div>
    </fieldset>

    <fieldset>
        <legend><i class="fa fa-map-marker"></i> Endereços</legend>
        <div id="container-address">
            @foreach($addresses as $modelAddress)
                @include('person/_form_address', compact('modelAddress', 'typeAddresses'))
            @endforeach
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
            @foreach($contacts as $modelContact)
                @include('person/_form_contact', compact('modelContact', 'typeContacts'))
            @endforeach            
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