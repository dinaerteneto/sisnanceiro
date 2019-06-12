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
                            @include('customer/_form', compact('modelAddress', 'typeAddresses', 'addresses', 'contacts'))
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