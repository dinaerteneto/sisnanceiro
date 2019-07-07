<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
        body {
            font-family: "courier";
            font-size:10pt;
            background: #CCCCCC;
            margin: 0;
            font-weight: bold;
        }

        .content{
            width: 400px;
            background: #FFFFFF;
            margin: 0 auto;
            padding: 10px;
            height:90%;
        }

        .text-center{
        text-align: center;
        }

        .text-left{
            text-align: left;
        }

        .text-right{
            text-align: right;
        }
    </style>

    <script type="text/javascript" src="./CUPOM_files/jquery-1.7.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
        window.print();
            setTimeout(function () {
                window.onmouseover = function () {
                    window.close();
                }
            }, 1000);
        });
    </script>    
</head>
<body cz-shortcut-listen="true">
    <div class="content">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tbody>
                <tr>
                    <td>
                        <div class="text-center"><b>{{ $sale['companyName'] }}</b></div>
                        <br>
                        <!--
                        <div>, , </div>
                        <div> -  ­ </div>
                        -->
                        <div>Operador: {{ $sale['userCreated']['name'] }}</div>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div>
            <div>==================================================</div>
            <div class="text-center">PEDIDO Nº {{ $sale['sale_code'] }}</div>
            <div>==================================================</div>

            <table cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td>Data: {{ $sale['sale_date'] }}</td>
                        <td class="text-right">Hora: {{ $sale['sale_hour'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div>
            <div>=====================PRODUTOS=====================</div>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <td>Nome</td>
                        <td class="text-right">Qtd.</td>
                        <td class="text-right">Vr. unt.</td>
                        <td class="text-right">Desc.</td>
                        <td class="text-right">Subtotal</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                        --------------------------------------------------
                        </td>
                    </tr>
                    @foreach($sale['items'] as $item)
                    <tr>
                        <td class="text-left" style="max-width:150px">{{ substr($item['product']['name'], 0, 20) }}</td>
                        <td class="text-right" style="max-width:150px">{{ $item['quantity'] }}</td>
                        <td class="text-right">{{ $item['unit_value'] }}</td>
                        <td class="text-right">{{ $item['discount_value'] }}</td>
                        <td class="text-right">{{ $item['total_value'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div>=====================PAGAMENTO====================</div>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <td>Total do pedido:</td>
                        <td class="text-right">{{ $sale['net_value']}}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            --------------------------------------------------
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-center">*** Este ticket não é documento fiscal ***</div>
        <div class="text-center"><br>OBRIGADO E VOLTE SEMPRE!</div>
    </div>    
</body>
</html>