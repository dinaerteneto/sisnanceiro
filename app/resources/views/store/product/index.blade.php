@extends('layouts.app')

@section('content')

<div class="d-flex w-100 home-header">
    <div>
        <h1 class="page-header"><i class="fa fa-fw fa-tags "></i> Produtos <span>&gt; Lista</span></h1>
    </div>
</div>

<div class="d-flex w-100">
    <section id="widget-grid" class="w-100">
        <div class="row">
            <article class="col-12 sortable-grid ui-sortable">

                    <div class="jarviswidget jarviswidget-color-blue-dark" id="wid-id-0" role="widget">

                        <header role="heading" class="ui-sortable-handle">
                            <div class="widget-header">
                                <span class="widget-icon"> <i class="fa fa-tags"></i> </span>
                                <h2>Produtos</h2>
                            </div>

                        </header>

                        <!-- widget div-->
                        <div role="content">

                            <!-- widget content -->
                            <div class="widget-body">
                            </div>
                        </div>
                    </div>
            </article>
        </div>
    </section>
</div>

@endsection