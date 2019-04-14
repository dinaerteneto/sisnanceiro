<a href="javascript:void(0)" onclick="SAtoggleClass(this, 'body', 'sa-shortcuts-expanded')" class="sa-sidebar-shortcut-toggle">
    <img src="assets/img/avatars/sunny.png" alt="" class="online">
    <span>{{ Auth::user()->email }} <span class="fa fa-angle-down"></span></span>
</a>
<div class="sa-left-menu-outer">
    <ul class="metismenu sa-left-menu" id="menu1">
        <li class=" active">
            <!-- first-level -->
            <a class="has-arrow" href="index.html" title="Dashboard"><span class="fa fa-lg fa-fw fa-home"></span> <span class="menu-item-parent">Dashboard</span>
                <b class="collapse-sign">
                    <em class="fa fa-plus-square-o"></em>
                    <em class="fa fa-minus-square-o"></em>
                </b>
            </a>
            <ul aria-expanded="false" class="sa-sub-nav collapse">
                <!-- second-level -->
                <li class="">
                    <a href="index.html" title="Analytics Dashboard"> Analytics Dashboard </a>

                </li><!-- second-level -->
                <li class="">
                    <a href="dashboard-marketing.html" title="Marketing Dashboard"> Marketing Dashboard </a>

                </li><!-- second-level -->
                <li class="">
                    <a href="dashboard-social.html" title="Social Wall"> Social Wall </a>

                </li>
            </ul>

        </li>
    </ul>
</div>
<a href="javascript:void(0)" class="minifyme" onclick="SAtoggleClass(this, 'body', 'minified')">
    <i class="fa fa-arrow-circle-left hit"></i>
</a>