<a href="javascript:void(0)" onclick="SAtoggleClass(this, 'body', 'sa-shortcuts-expanded')" class="sa-sidebar-shortcut-toggle">
    <img src="assets/img/avatars/sunny.png" alt="" class="online">
    <span>
        @if(Auth::user())
        {{ Auth::user()->email }} 
        @endif
        <span class="fa fa-angle-down"></span>
    </span>
</a>
<div class="sa-left-menu-outer">
    <ul class="metismenu sa-left-menu" id="menu1">
        <li class="{{ Request::is('event') ? 'active' : '' }}">
            <a class="" href="/event" title="Eventos">
                <span class="fa fa-lg fa-fw fa-calendar"></span> 
                <span class="menu-item-parent">Eventos</span>
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
                    <a href="/customer" title="Clientes">Clientes </a>
                </li>
                <!--
                <li class="{{ Request::is('user') ? 'active' : '' }}">
                    <a href="/user" title="Clientes">Usuários </a>
                </li>
                <li class="{{ Request::is('supplier') ? 'active' : '' }}">
                    <a href="/supplier" title="Clientes">Fornecedores </a>
                </li>          
                -->       
            </ul>          
        </li>

        <!--
        <li class="{{ Request::is('bank-category') ? 'active' : '' }}">
            
            <a class="has-arrow" href="#" title="Dashboard"><span class="fa fa-lg fa-fw fa-money"></span> <span class="menu-item-parent">Financeiro</span>
                <b class="collapse-sign">
                    <em class="fa fa-plus-square-o"></em>
                    <em class="fa fa-minus-square-o"></em>
                </b>
            </a>
            <ul aria-expanded="false" class="sa-sub-nav collapse">
                second-level 
                <li class="{{ Request::is('bank-category') ? 'active' : '' }}">
                    <a href="/bank-category"> Categorias </a>
                </li>
                
                <li class="">
                    <a href="dashboard-marketing.html" title="Marketing Dashboard"> Cartões de crédito </a>

                </li>
                <li class="">
                    <a href="dashboard-marketing.html" title="Marketing Dashboard"> Maquininhas </a>

                </li>
                <li class="">
                    <a href="dashboard-marketing.html" title="Marketing Dashboard"> Contas bancárias </a>

                </li>
                <li class="">
                    <a href="dashboard-marketing.html" title="Marketing Dashboard"> Despesas </a>

                </li>
                <li class="">
                    <a href="dashboard-social.html" title="Social Wall"> Receitas </a>

                </li>
                
            </ul>

        </li>
-->
        <li class="{{ Request::is('store/product') || Request::is('store/category') || Request::is('store/brand') || Request::is('sale') ? 'active' : null }}">
            <a class="" href="javascript:void(0)" title="Loja">
                <span class="fa fa-lg fa-fw fa-tags"></span> 
                <span class="menu-item-parent">Loja</span>
                <b class="collapse-sign">
                    <em class="fa fa-plus-square-o"></em>
                    <em class="fa fa-minus-square-o"></em>
                </b>
            </a>
            <ul aria-expanded="false" class="sa-sub-nav collapse">
                <!-- second-level -->
                <li class="{{ Request::is('store/product') ? 'active' : '' }}">
                    <a href="/store/product" title="Produtos"> Produtos </a>
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
                    <a href="/sale" title=""> Vendas </a>
                </li>        
            </ul>        
        </li>
    </ul>
</div>
<a href="javascript:void(0)" class="minifyme" onclick="SAtoggleClass(this, 'body', 'minified')">
    <i class="fa fa-arrow-circle-left hit"></i>
</a>