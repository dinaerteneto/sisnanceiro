@extends('layouts.app')

@section('content')
<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa-fw fa fa-home"></i> Home <span>&gt; Bem vindo</span></h1>
    </div>
</div>

<article class="col-12 sortable-grid ui-sortable">
    <div class="jarviswidget jarviswidget-color-blue-dark no-padding">
        <header>
            <div class="widget-header">
                <span class="widget-icon"> <i class="fa-fw fa fa-home"></i> </span>
                <h2> Bem vindo </h2>						
            </div>
        </header>

        <div class="widget-body">
            <div class="col-sm-12">
                <br />
                <p><strong>Alterações em: 12/09/2019</strong></p>
                <ol>
                    <li>Importação de contatos dos clientes.</li>
                    <li>Importação de endereço dos clientes.</li>
                    <li>Corrigido o problema de clientes duplicados.</li>
                    <li>Corrigido o problema de clientes sem endereço.</li>
                    <li>Corrigido o problema de clientes sem contatos.</li>
                </ol>    
                <p><strong>Alterações em: 30/08/2019</strong></p>
                <ol>
                    <li>Removido campos dos produtos.</li>
                    <li>Alterador layout de impressão do papel A4.</li>
                    <li>Incluído botão de impressão do pedido após finalizar a venda.</li>
                    <li>Corrigido bug do copiar venda.</li>
                    <li>Script de update para remover os produtos, que foram removidos no sistema de origem.</li>
                </ol>
                <p><strong>Backlog - Correções</strong></p>
                <ol>
                    <li>Alteração do pedido</li>
                    <li>Ao realizar pedido, criar uma forma de recupera-lo, caso não seja possível finalizar.</li>
                    <li>Layout de impressão do cupom.</li>
                    <li>Produto: possibilitar incluir valores de varejo.</li>
                    <li>Venda: Incluir o tipo de valor do produto.</li>
                </ol>

            </div>
        </div>
    </div>
</article>

@endsection