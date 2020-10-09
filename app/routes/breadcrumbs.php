<?php

Breadcrumbs::register('home', function ($breadcrumbs) {
 $breadcrumbs->push('Dashboard', route('home'));
});

Breadcrumbs::for('customer', function ($trail) {
 $trail->parent('home');
 $trail->push('Clientes', route('customer'));
});

Breadcrumbs::for('customer-create', function ($trail) {
 $trail->parent('customer');
 $trail->push("Novo cliente", route('customer-create'));
});

Breadcrumbs::for('customer-update', function ($trail, $model) {
 $trail->parent('customer');
 $trail->push("{$model->firstname} {$model->lastname}", route('customer', $model->id));
});

Breadcrumbs::for('supplier', function ($trail) {
 $trail->parent('home');
 $trail->push('Fornecedores', route('customer'));
});

Breadcrumbs::for('supplier-create', function ($trail) {
 $trail->parent('supplier');
 $trail->push("Novo fornecedor", route('supplier-create'));
});

Breadcrumbs::for('supplier-update', function ($trail, $model) {
 $trail->parent('supplier');
 $trail->push("{$model->firstname} {$model->lastname}", route('customer', $model->id));
});

Breadcrumbs::for('bank-account', function ($trail) {
 $trail->parent('home');
 $trail->push('Contas bancárias', route('bank-account'));
});

Breadcrumbs::for('bank-category', function ($trail) {
 $trail->parent('home');
 $trail->push('Categorias', route('bank-category'));
});

Breadcrumbs::for('bank-transaction', function ($trail) {
 $trail->parent('home');
 $trail->push('Transações', route('bank-transaction'));
});

Breadcrumbs::for('bank-transaction-pay', function ($trail) {
 $trail->parent('home');
 $trail->push('Contas a pagar', route('bank-transaction-pay'));
});

Breadcrumbs::for('bank-transaction-receive', function ($trail) {
 $trail->parent('home');
 $trail->push('Contas a receber', route('bank-transaction-receive'));
});

Breadcrumbs::for('credit-card', function ($trail) {
 $trail->parent('home');
 $trail->push('Cartões de crédito', route('credit-card'));
});

Breadcrumbs::for('credit-card-invoices', function ($trail, $model) {
 $trail->parent('credit-card');
 $trail->push("{$model->name}", route('credit-card'));
});

Breadcrumbs::for('transfer', function ($trail) {
 $trail->parent('home');
 $trail->push('Transferências', route('transfer'));
});

Breadcrumbs::for('reports-cash-flow', function ($trail) {
 $trail->parent('home');
 $trail->push('Fluxo de caixa', route('reports-cash-flow'));
});
