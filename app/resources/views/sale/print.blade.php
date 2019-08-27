<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ config('app.name', 'Laravel') }}</title>     

    <style>
        body {
            font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            font-size: 12px;
        }

        .content{
            width: 80%;
            background: #FFFFFF;
            margin: 0 auto;
            padding: 10px;
            height:90%;
        }        

        table.table-border {
            border: 1px solid #ccc;
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 10px;
        }

        table.table-border, th, td {
            border: 1px solid #ccc;
            padding: 4px;
        }

        table.table-border tr td {
            border: 1px solid #ccc;
            padding: 4px;
        }

        table.table-border thead th, tfoot th {
            background-color: #ddd;
            padding: 2px;
        }

        table.table-no-border {
            border: 0;
            width: 100%;
        }

        table.table-no-border td {
            border: 0;
        }

        .div-border {
            border: 1px solid #ccc; 
            margin-bottom: 10px            
        }
        
    </style>
</head>
<body cz-shortcut-listen="true">
    <div class="content">

        <div class="div-border">
            <table class="table-no-border">
                <tbody>
                    <tr>
                        <td>
                            IMAGEM
                        </td>
                        <td>
                            SUL BAHIA COM DE ALIMENTOS
                        </td>
                        <td align="right">
                            @if(isset($sale['company']['contact']))
                                {{ $sale['company']['contact']['phone'] }}<br>
                                {{ $sale['company']['contact']['email'] }}<br>
                            @endif
                            Vendedor: {{ $sale['userCreated']['name'] }}
                        </td>
                    </tr>
                </tbody>
            </table>   
        </div>     

        <table class="table-border">
            <thead>
                <tr>
                    <th>
                        <div style="float: left; font-size: 14px">PEDIDO #{{ $sale['sale_code'] }}</div>
                        <div style="float: right; font-size: 14px">{{ $sale['sale_date'] }} as {{ $sale['sale_hour'] }}h</div>
                    </th>
                </tr>
            </thead>
        </table>

        <table class="table-border">
            <thead>
                <tr>
                    <th colspan="4">DADOS DO CLIENTE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Cliente:</strong></td>
                    <td>{{ $sale['customer']['name'] }}</td>
                    <td><strong>CPF/CNPJ:</strong></td>
                    <td>{{ $sale['customer']['cpf-cnpj'] }}</td>
                </tr>
                <tr>
                    <td><strong>Endereço:</strong></td>
                    <td>{{ $sale['customer']['address']['address'] }}</td>
                    <td><strong>CEP:</strong></td>
                    <td>{{ $sale['customer']['address']['zip_code'] }}</td>
                </tr>
                <tr>
                    <td><strong>Cidade:</strong></td>
                    <td>{{ $sale['customer']['address']['city'] }}</td>
                    <td><strong>Estado:</strong></td>
                    <td>{{ $sale['customer']['address']['uf'] }}</td>
                </tr>
                <tr>
                    <td><strong>Telefone:</strong></td>
                    <td>{{ $sale['customer']['contact']['phone'] }}</td>
                    <td><strong>E-Mail:</strong></td>
                    <td>{{ $sale['customer']['contact']['email'] }}</td>
                </tr>
            </tbody>
        </table>

        <table class="table-border">
            <thead>
                <tr>
                    <th colspan="6">PRODUTOS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th align="left" width="10%">QTD.</th>
                    <th align="left">PRODUTO</th>
                    <th align="right">DESC.</th>
                    <th align="right">VL UNIT.</th>
                    <th align="right">SUBTOTAL</th>
                </tr>
                <?php $i = 1?>

                @foreach($sale['items'] as $item)
                <tr>
                    <td align="left">{{ $item['quantity'] }} {{ $item['unit_measurement'] }}</td>
                    <td>{{ $item['product']['name'] }}</td>
                    <td align="right">{{ $item['discount_value'] }}</td>
                    <td align="right">{{ $item['unit_value'] }}</td>
                    <td align="right">{{ $item['total_value'] }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" align="right">TOTAL DOS PRODUTOS</th>
                    <th colspan="2" align="right">{{ $sale['net_value'] }}</th>
                </tr>
            </tfoot>
        </table>

        <div class="div-border" style="padding: 20px; padding-top: 50px; text-align:center">
            <hr style="border: 0.5px solid #000" />
            Assinatura do cliente
        </div>

    </div>    
</body>
</html>