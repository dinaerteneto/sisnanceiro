@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-fw fa-user"></i> Adicionar cliente </h1>
    </div>
</div>

<div id="content" style="opacity:1;">
    <div class="d-flex w-100">
        <section id="widget-grid" class="w-100">
            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
                    <div class="jarviswidget well jarviswidget-color-darken">
                        <div class="widget-body">
                            <form>
                                <fieldset>
                                    <legend><i class="fa fa-list-alt"></i> Dados gerais</legend>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Tipo de cliente</label>
                                                <input type="text" id="Customer_physical" name="Customer[physical]" class="form-control" placeholder="Tipo">
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
                                                <input type="text" id="Customer_birthdate" name="Customer[birthdate]" class="form-control" placeholder="Data de nascimento">
                                            </div>
                                        </div>                                        
                                    </div>

                                    <div class="row">
                                       <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Sexo</label>
                                                <input type="text" id="Customer_birthdate" name="Customer[birthdate]" class="form-control" placeholder="Sexo">
                                            </div>
                                        </div>                                                                      
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>CPF</label>
                                                <input type="text" id="Customer_cpf" name="Customer[cpf]" class="form-control" placeholder="CPF">
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
                                        @include('person/_form_address', compact('modelAddress'))
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
                                        @include('person/_form_contact', compact('modelContact'))
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-lg-12 col-md-12 align-right">
                                            <button id="add-contact" type="button" class="btn bg-blue-dark text-white">
                                                <i class="fa fa-plus-circle"></i> Inserir novo contato
                                            </button>
                                        </div>                                        
                                    </div>                                    
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </article>
            </div>

        </section>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('assets/js/custom/Person.js') }}"></script>
@endsection