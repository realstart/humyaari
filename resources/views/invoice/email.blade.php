<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Invoice</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
    <style>
        * {
            margin: 0;
        }
        a {
            text-decoration: none;
        }
        html, body {
            width: 560pt;
            height: 842pt;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background: #fff;
            background-image: none;
            font-size: 13px;
            color: #434e51;
            padding: 3%;
            line-height: 1.2;
        }
        .text-white {
            color: #fff;
        }
        .text-right {
            text-align: right;
        }
        .bold {
            font-weight: 700;
        }
        .v-top {
            vertical-align: top;
        }
        .text-light {
            color: #718489;
        }
        .bill-to {
          font-size: 20px;
          font-weight: 700;
        }
        .invoice tr td:nth-child(2) {
          padding-left: 10px;
        }
        .table-center {
          margin-left: auto;
          margin-right: auto;
        }
        .table-bill-to {
          margin-left: 20px;
        }
        .table-bill-to tr td:nth-child(1) {
          font-weight: 700;
        }
        .table-bill-to tr td:nth-child(2) {}
        .product-table-title {
          font-size: 20px;
          font-weight: 700;
          padding-bottom: 10px;
        }
        .product-table th,
        .product-table td {
          padding: 7px 5px;
          box-sizing: border-box;
        }
        .product-table {
          border-collapse: collapse;
        }
        .product-table thead th {
          border-bottom: 1px solid gray;
          background-color: #f2f2f2;
        }
        .product-table {
          width: 100%;
          margin-bottom: 30px;
          table-layout:fixed;
        }
        .product-table th {
          text-align: left;
        }
        .product-table .no {
          width: 3%;
        }
        .product-table .name {
          width: 40%;
        }
        .product-table .pnr {
          width: 10%;
        }
        .product-table .route {
          width: 10%;
        }
        .product-table .qty {
          width: 7%;
          text-align: center;
        }
        .product-table td:nth-child(5) {
          text-align: center;
        }
        .product-table .rate {
          width: 15%;
        }
        .product-table .subtotal {
          width: 15%;
        }
        .product-table tr th:nth-child(6),
        .product-table tr td:nth-child(6),
        .product-table tr th:nth-child(7),
        .product-table tr td:nth-child(7) {
          text-align: right;
        }
        .breakdown td:first-child,
        .total td:first-child {
          font-weight: 700;
          padding-right: 20px;
        }
        .breakdown td:last-child,
        .total td:last-child {
          text-align: right;
        }
        .total {
          border-collapse: collapse;
          width: 50%;
          margin-left: auto;
          margin-right: 0;
        }
        .total td {
          padding-bottom: 10px;
          padding-top: 10px;
          border-bottom: 1px solid #666;
        }
        .invoice {
          float: right;
        }
        .invoice tr:first-child td {
          font-size: 20px;
        }
    </style>
</head>

<body>
<div class="container">
    <table class="table-center" width="100%">
        <tr>
            <td colspan="2">
                <span style="display: inline-block">
                <strong>Fly Solution Travel & Tours</strong><br>
                Suite #3AB, House #67, Road #17, Block - C,<br>
                Banani -1213, Dhaka, Bangladesh.<br>
                Phone: +8801615475882 | +881615475884<br>
                E-mail: info@flysolution.com.bd
                </span>
            </td>
            <td>
              <table class="invoice">
                <tr>
                  <td class="text-right" colspan="2"><strong>INVOICE</strong></td>
                </tr>
                <tr>
                  <td><strong>INVOICE NO</strong></td>
                  <td class="text-right">#{{ $invoice->invoice_no }}</td>
                </tr>
                <tr>
                  <td><strong>BILLING DATE</strong></td>
                  <td class="text-right">{{ $invoice->invoice_date }}</td>
                </tr>
                <tr>
                  <td><strong>DUE DATE</strong></td>
                  <td class="text-right">{{ $invoice->invoice_due_date }}</td>
                </tr>
              </table>
            </td>
        </tr>
        <tr>
          <td colspan="3" style="padding-top: 30px; padding-bottom: 30px">
            <table>
              <tr class="v-top">
                <td class="bill-to">Billed To:</td>
                <td>
                  <table class="table-bill-to">
                    <tr>
                      <td>Customer Name</td>
                      <td>{{ $invoice->customer->name }}</td>
                    </tr>
                    <tr>
                      <td>Adderss</td>
                      <td>{{ $invoice->customer->address1 }}, {{ $invoice->customer->address2 }}</td>
                    </tr>
                    <tr>
                      <td>Phone</td>
                      <td>{{ $invoice->customer->phone }}</td>
                    </tr>
                    <tr>
                      <td>Email</td>
                      <td>{{ $invoice->customer->email }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        @if (count($products) > 0)
        <tr>
          <td class="product-table-title" colspan="3">Product Description</td>
        </tr>
        <tr>
        <td colspan="3">
            <table class="product-table">
              <thead>
                <tr>
                  <th class="no">#</th>
                  <th class="name">E-Ticket No.</th>
                  <th class="pnr">PNR</th>
                  <th class="route">Route</th>
                  <th class="qty">Qty</th>
                  <th class="rate">Rate</th>
                  <th class="subtotal">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($products as $index => $product)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->pnr }}</td>
                    <td>{{ $product->route }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>BDT {{ number_format($product->price, 2, '.', ',') }}</td>
                    <td>BDT {{ number_format($product->quantity * $product->price, 2, '.', ',') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </td>
        </tr>
        @endif
        @if (count($services) > 0)
        <tr>
          <td class="product-table-title" colspan="3">Product Description</td>
        </tr>
        <tr>
        <td colspan="3">
            <table class="product-table">
              <thead>
                <tr>
                  <th class="no">#</th>
                  <th class="name">E-Ticket No.</th>
                  <th class="pnr"></th>
                  <th class="route"></th>
                  <th class="qty">Qty</th>
                  <th class="rate">Rate</th>
                  <th class="subtotal">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($services as $serviceIndex => $service)
                  <tr>
                    <td>{{ $serviceIndex }}</td>
                    <td>{{ $service->name }}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>{{ $service->quantity }}</td>
                    <td>BDT {{ number_format($service->price, 2, '.', ',') }}</td>
                    <td>BDT {{ number_format($service->quantity * $service->price, 2, '.', ',') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </td>
        </tr>
        @endif
        <tr>
          <td colspan="3">
            <table style="width: 100%;">
              <tr>
                <td style="width: 50%">
                  @if ($official)
                  <table class="breakdown">
                    <tr>
                      <td>Base Fare:</td>
                      <td>BDT {{ number_format($invoice->base_fare, 2, '.', ',') }}</td>
                    </tr>
                      <tr>
                      <td>Tax:</td>
                      <td>BDT {{ number_format($invoice->tax, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                      <td>Discount</td>
                      <td>BDT {{ number_format($invoice->discount, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                      <td>Other Agent:</td>
                      <td>BDT {{ number_format($invoice->other_agent, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                      <td>service Agent:</td>
                      <td>BDT {{ number_format($invoice->service_agent, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                      <td>IATA Comission:</td>
                      <td>BDT {{ number_format($invoice->iata_comission, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                      <td>Govt. Tax:</td>
                      <td>BDT {{ number_format($invoice->govt_tax, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                      <td>Profit:</td>
                      <td>BDT {{ number_format($invoice->profit, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                      <td>Airline Payment:</td>
                      <td>BDT {{ number_format($invoice->airline_payment, 2, '.', ',') }}</td>
                    </tr>
                  </table>
                  @endif
                </td>
                <td style="width: 50%; vertical-align: top;">
                  <table class="total">
                    <tr>
                      <td>Subtotal</td>
                      <td>BDT {{ number_format($invoice->subtotal, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                      <td>Discount</td>
                      <td>BDT {{ number_format($invoice->discount, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                      <td>Total</td>
                      <td>BDT {{ number_format($invoice->total_receivable, 2, '.', ',') }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="padding-top: 100px" colspan="2">Computer generated document and it does not require a signature.</td>
          <td style="padding-top: 100px" class="text-right"><!--Invoice Created By: Codebooemrs--></td>
        </tr>
    </table>
</div>
</body>
</html>