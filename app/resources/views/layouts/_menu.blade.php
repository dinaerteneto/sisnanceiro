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
        <li class=" active">
            <!-- first-level -->
            <a class="has-arrow" href="#" title="Dashboard"><span class="fa fa-lg fa-fw fa-money"></span> <span class="menu-item-parent">Financeiro</span>
                <b class="collapse-sign">
                    <em class="fa fa-plus-square-o"></em>
                    <em class="fa fa-minus-square-o"></em>
                </b>
            </a>
            <ul aria-expanded="false" class="sa-sub-nav collapse">
                <!-- second-level -->
                <li class="">
                    <a href="/bank-category" title="Analytics Dashboard"> Categorias </a>

                </li>
                <!-- second-level 
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
                -->
            </ul>

        </li>
        <li class="">
            <a class="" href="/event" title="Eventos">
                <span class="fa fa-lg fa-fw fa-calendar"></span> 
                <span class="menu-item-parent">Eventos</span>
            </a>          
        </li>
    </ul>
</div>
<a href="javascript:void(0)" class="minifyme" onclick="SAtoggleClass(this, 'body', 'minified')">
    <i class="fa fa-arrow-circle-left hit"></i>
</a>