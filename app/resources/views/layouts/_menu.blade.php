<a href="javascript:void(0)" onclick="SAtoggleClass(this, 'body', 'sa-shortcuts-expanded')" class="sa-sidebar-shortcut-toggle">
    <img src="assets/img/avatars/sunny.png" alt="" class="online">
    <span>
        @if(Auth::user())
            <?php $person = Auth::user()->load('person')->person;?>
            @if($person)
                {{ $person->firstname }}
            @endif
        @endif
        <span class="fa fa-angle-down"></span>
    </span>
</a>
<div class="sa-left-menu-outer">
    <ul class="metismenu sa-left-menu" id="menu1">
        <!--
        <li class="{{ Request::is('event') ? 'active' : '' }}">
            <a class="" href="{{ url('/event') }}" title="Eventos">
                <span class="fa fa-lg fa-fw fa-calendar"></span>
                <span class="menu-item-parent">Eventos</span>
            </a>
        </li>
        -->

        <li class="{{ Request::is('home') ? 'active' : '' }}">
            <a href="{{ url('/home') }}" title="Dashboard">
                <i class="fa fa-lg fa-fw fa-home"></i>
                <span class="menu-item-parent">Dashboard</span>
            </a>
        </li>

        <li class="{{ Request::is('customer') || Request::is('user') || Request::is('supplier') ? 'active' : '' }}">
            <a class="" href="javascript:void(0)" title="Cadastros">
                <span class="fa fa-lg fa-fw fa-list"></span>
                <span class="menu-item-parent">Cadastros</span>
                <b class="collapse-sign">
                    <em class="fa fa-plus-square-o"></em>
                    <em class="fa fa-minus-square-o"></em>
                </b>
            </a>
            <ul aria-expanded="false" class="sa-sub-nav collapse">
                <li class="{{ Request::is('customer') ? 'active' : '' }}">
                    <a href="{{ url('/customer') }}" title="Clientes">Clientes </a>
                </li>
                <!--
                <li class="{{ Request::is('user') ? 'active' : '' }}">
                    <a href="{{ url('/user') }}" title="Clientes">Usu??rios </a>
                </li>
                -->
                <li class="{{ Request::is('supplier') ? 'active' : '' }}">
                    <a href="{{ url('/supplier') }}" title="Fornecedores">Fornecedores </a>
                </li>

            </ul>
        </li>


        <li class="{{ Request::is('bank-category')
            || Request::is('bank-account')
            || Request::is('bank-transaction')
            || Request::is('credit-card')
            || Request::is('bank-transaction/pay')
            || Request::is('bank-transaction/receive')
            || Request::is('bank-transaction/transfer')
            || Request::is('payment-tax')
            || Request::is('reports/cash-flow') ? 'active' : '' }}">

            <a class="has-arrow" href="#" title="Financeiro"><span class="fa fa-lg fa-fw fa-money"></span>
                <span class="menu-item-parent">Financeiro</span>
                <b class="collapse-sign">
                    <em class="fa fa-plus-square-o"></em>
                    <em class="fa fa-minus-square-o"></em>
                </b>
            </a>

            <ul aria-expanded="false" class="sa-sub-nav collapse">
                <li class="{{ Request::is('bank-category') ? 'active' : '' }}">
                    <a href="{{ url('/bank-category') }}"> Categorias </a>
                </li>

                <li class="">
                    <a href="{{ url('/bank-account') }}" title="Contas banc??rias"> Contas banc??rias </a>
                </li>
                <li class="">
                    <a href="{{ url('/bank-transaction') }}" title="Transa????es de receita e despesas"> Transa????es </a>
                </li>
                <li class="">
                    <a href="{{ url('/credit-card') }}" title="Fluxo de caixa"> Cart??o de cr??dito </a>
                </li>
                <li class="">
                    <a href="{{ url('/bank-transaction/pay') }}" title="Contas a receber"> Contas a pagar </a>
                </li>
                <li class="">
                    <a href="{{ url('/bank-transaction/receive') }}" title="Contas a pagar"> Contas a receber </a>
                </li>
                <li class="">
                    <a href="{{ url('/bank-transaction/transfer') }}" title="Transfer??ncias"> Transfer??ncias </a>
                </li>
                <!--
                <li class="">
                    <a href="{{ url('/reports/cash-flow') }}" title="Fluxo de caixa"> Fluxo de caixa </a>
                </li>

                <li class="">
                    <a href="{{ url('/payment-tax') }}" title="Taxas e prazos"> Taxas e prazos </a>
                </li>

                <li class="">
                    <a href="dashboard-marketing.html" title="Marketing Dashboard"> Cart??es de cr??dito </a>

                </li>
                <li class="">
                    <a href="dashboard-marketing.html" title="Marketing Dashboard"> Maquininhas </a>

                </li>
                <li class="">
                    <a href="dashboard-marketing.html" title="Marketing Dashboard"> Despesas </a>

                </li>
                <li class="">
                    <a href="dashboard-social.html" title="Social Wall"> Receitas </a>

                </li>
                -->

            </ul>

        </li>

        <li class="{{ Request::is('store/product') || Request::is('store/category') || Request::is('store/brand') || Request::is('sale') ? 'active' : null }}">
            <!--
            <a class="" href="javascript:void(0)" title="Loja">
                <span class="fa fa-lg fa-fw fa-tags"></span>
                <span class="menu-item-parent">Loja</span>
                <b class="collapse-sign">
                    <em class="fa fa-plus-square-o"></em>
                    <em class="fa fa-minus-square-o"></em>
                </b>
            </a>
            -->
            <ul aria-expanded="false" class="sa-sub-nav collapse">
                <!-- second-level -->
                <li class="{{ Request::is('store/product') ? 'active' : '' }}">
                    <a href="{{ url('/store/product') }}" title="Produtos"> Produtos </a>
                </li>
                <!--
                <li class="{{ Request::is('store/category') ? 'active' : '' }}">
                    <a href="/store/category" title="Categorias de produto"> Categorias </a>
                </li>
                <li class="{{ Request::is('store/brand') ? 'active' : '' }}">
                    <a href="/store/brand" title=""> Marcas </a>
                </li>
                -->
                <li class="{{ Request::is('sale') ? 'active' : '' }}">

                    <a href="{{ url('/sale') }}" title="Vendas"> Vendas </a>
                </li>
            </ul>
        </li>
    </ul>
</div>
<a href="javascript:void(0)" class="minifyme" onclick="SAtoggleClass(this, 'body', 'minified')">
    <i class="fa fa-arrow-circle-left hit"></i>
</a>
