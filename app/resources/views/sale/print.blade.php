<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ config('app.name', 'Laravel') }}</title>     

    <style media="print, screen">

        * {
            border: 0;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .content{
            width: 800px;
            /* background: #FFFFFF; */
            /* padding: 10px; */
            height:90%;
        }        

        table.table-no-border {
            border-collapse: collapse;
            width: 100%;
        }

        table.table-no-border tr {
            border-bottom: 1px solid #ccc;
        }

        table.table-no-border td {
            padding: 2px;
        }

        .div-border {
            border: 1px solid #ccc; 
            margin-bottom: 10px            
        }
        
    </style>

    <script type="text/javascript" src="./CUPOM_files/jquery-1.7.1.min.js"></script>
    <script type="text/javascript">
        window.print();
        /*
        setTimeout(function () {
            window.onmouseover = function () {
                window.close();
            }
        }, 1000);      
        */  
    </script>  

</head>
<body cz-shortcut-listen="true">
    <div class="content">

        <div class="">
            <table class="table-no-border">
                <tbody>
                    <tr>
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

        <table class="table-no-border">
            <thead>
                <tr>
                    <th>
                        <div style="float: left; font-size: 14px">PEDIDO #{{ $sale['sale_code'] }}</div>
                        <div style="float: right; font-size: 14px">{{ $sale['sale_date'] }} as {{ $sale['sale_hour'] }}h</div>
                    </th>
                </tr>
            </thead>
        </table>

        <br />
        <table class="table-no-border">
            <thead>
                <tr style="border: 0">
                    <th colspan="4">DADOS DO CLIENTE</th>
                </tr>
            </thead>

            @if(isset($sale['customer']['cpf-cnpj']))
            <tbody>
                <tr>
                    <td><strong>Cliente:</strong></td>
                    <td>{{ $sale['customer']['name'] }}</td>
                    <td><strong>CPF/CNPJ:</strong></td>
                    <td>{{ $sale['customer']['cpf-cnpj'] }}</td>
                </tr>
                <tr>
                    <td><strong>Endere??o:</strong></td>
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
            @else 
                <tbody>
                    <tr>
                        <td>Ao consumidor</td>
                    </tr>                    
                </tbody>
            @endif

        </table>

        <br />

        <table class="table-no-border">
            <thead>
                <tr style="border: 0">
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

        <!--
        <div class="div-border" style="padding: 20px; padding-top: 50px; text-align:center">
            <hr style="border: 0.5px solid #000" />
            Assinatura do cliente
        </div>
        -->

    </div>    
</body>
</html>