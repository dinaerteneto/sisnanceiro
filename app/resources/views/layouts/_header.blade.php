<div class="sa-header-container h-100">
    <div class="d-table d-table-fixed h-100 w-100">
        <div class="sa-logo-space d-table-cell h-100">
            <div class="flex-row d-flex align-items-center h-100">
                <a class="sa-logo-link" href="" title="{{ config('app.name', 'Laravel') }}"><img alt="{{ config('app.name', 'Laravel') }}" src="{{ asset('assets/img/logo.png') }}" class="sa-logo"></a>

            </div>
        </div>
        <div class="d-table-cell h-100 w-100 align-middle">
            <div class="sa-header-menu">
                <div class="d-flex align-items-center w-100">

                    <div class="ml-auto sa-header-right-area">
                        <div class="form-inline">

                            <button class="btn btn-light sa-btn-icon sa-btn-micro d-none d-lg-block" type="button"><span class="fa fa-microphone"></span></button>
                            <button class="btn btn-light sa-btn-icon sa-toggle-full-screen d-none d-lg-block" type="button" onclick="toggleFullScreen()"><span class="fa fa-arrows-alt"></span></button>
                            <form class="sa-header-search-form">
                                <input type="text" class="form-control" placeholder="Find reports and more">
                                <button type="submit" class="sa-form-btn"><span class="fa fa-search"></span></button>
                            </form>
                            <button class="btn btn-default sa-logout-header-toggle sa-btn-icon" type="button" href="{{ route('logout') }}"><span class="fa fa-sign-out"></span></button>
                            <span class="dropdown sa-user-dropdown">
                                <a href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="sa-user-dropdown-toggle">
                                    <img src="assets/img/avatars/sunny.png" alt="John Doe">
                                </a>
                                <span class="dropdown-menu dropdown-menu-right ml-auto" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fa fa-cog"></i> Setting</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:void(0);"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fa fa-arrow-down"></i> <u>S</u>hortcut</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:void(0);"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item padding-10 padding-top-5 padding-bottom-5" href="javascript:void(0);" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
                                </span>
                            </span>

                            <button class="btn btn-default sa-btn-icon sa-sidebar-hidden-toggle" onclick="SAtoggleClass(this, 'body', 'sa-hidden-menu')" type="button"><span class="fa fa-reorder"></span></button>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>