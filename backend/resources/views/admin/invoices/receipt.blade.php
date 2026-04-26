<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Receipt — {{ $invoice->invoice_number }}</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Courier New', Courier, monospace;
      background: #e8e0d0;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 30px 16px;
    }

    .receipt {
      background: #fff;
      width: 300px;
      padding: 20px 16px;
      box-shadow: 2px 4px 16px rgba(0,0,0,0.15);
      position: relative;
    }

    /* Torn edge top */
    .receipt::before {
      content: '';
      display: block;
      height: 8px;
      background: repeating-linear-gradient(90deg, #fff 0px, #fff 8px, #e8e0d0 8px, #e8e0d0 16px);
      position: absolute;
      top: -8px; left: 0; right: 0;
    }
    .receipt::after {
      content: '';
      display: block;
      height: 8px;
      background: repeating-linear-gradient(90deg, #fff 0px, #fff 8px, #e8e0d0 8px, #e8e0d0 16px);
      position: absolute;
      bottom: -8px; left: 0; right: 0;
    }

    .center { text-align: center; }
    .store-name { font-size: 14px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
    .store-sub  { font-size: 9px; color: #666; margin-top: 2px; letter-spacing: 1px; }
    .store-contact { font-size: 9px; color: #666; margin-top: 4px; line-height: 1.6; }

    .dashed { border: none; border-top: 1px dashed #aaa; margin: 10px 0; }
    .solid   { border: none; border-top: 1px solid #333; margin: 10px 0; }

    .receipt-label { font-size: 10px; letter-spacing: 3px; text-transform: uppercase; color: #555; text-align: center; }

    .meta { font-size: 9px; color: #333; line-height: 1.8; }
    .meta-row { display: flex; justify-content: space-between; }

    /* Items */
    .items { width: 100%; font-size: 9px; }
    .item-header { display: flex; justify-content: space-between; font-weight: 700; font-size: 9px; text-transform: uppercase; letter-spacing: 1px; padding-bottom: 4px; border-bottom: 1px dashed #aaa; margin-bottom: 4px; }
    .item-row { margin-bottom: 6px; }
    .item-name { font-size: 10px; font-weight: 700; }
    .item-detail { display: flex; justify-content: space-between; font-size: 9px; color: #555; }

    /* Totals */
    .totals { font-size: 9px; }
    .total-row { display: flex; justify-content: space-between; padding: 2px 0; }
    .total-row.grand { font-size: 13px; font-weight: 700; padding-top: 6px; border-top: 1px solid #333; margin-top: 4px; }

    /* Payment */
    .payment { font-size: 9px; line-height: 1.8; }
    .payment-row { display: flex; justify-content: space-between; }
    .status { font-weight: 700; text-transform: uppercase; font-size: 9px; }
    .status.paid   { color: #2e7d32; }
    .status.unpaid { color: #e65100; }

    /* Footer */
    .footer { text-align: center; font-size: 9px; color: #666; line-height: 1.8; }
    .thank-you { font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 4px; }

    /* Print button */
    .print-btn {
      margin-top: 20px;
      padding: 10px 28px;
      background: #c9a84c;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      letter-spacing: 1px;
    }
    .print-btn:hover { background: #b8943d; }

    @media print {
      body { background: #fff; padding: 0; }
      .receipt { box-shadow: none; width: 80mm; }
      .print-btn { display: none; }

      @page {
        size: 80mm auto;
        margin: 4mm;
      }
    }
  </style>
</head>
<body>

<div class="receipt">

  <!-- Store Header -->
  <div class="center">
    <div class="store-name">{{ \App\Models\Setting::get('store_name', "Andaya's") }}</div>
    <div class="store-sub">Native Products</div>
    <div class="store-contact">
      @if(\App\Models\Setting::get('store_address')){{ \App\Models\Setting::get('store_address') }}<br>@endif
      @if(\App\Models\Setting::get('store_phone')){{ \App\Models\Setting::get('store_phone') }}<br>@endif
      @if(\App\Models\Setting::get('store_email')){{ \App\Models\Setting::get('store_email') }}@endif
    </div>
  </div>

  <hr class="dashed"/>
  <div class="receipt-label">Official Receipt</div>
  <hr class="dashed"/>

  <!-- Meta -->
  <div class="meta">
    <div class="meta-row"><span>Receipt #</span><span>{{ $invoice->invoice_number }}</span></div>
    <div class="meta-row"><span>Order #</span><span>{{ $invoice->order->order_number }}</span></div>
    <div class="meta-row"><span>Date</span><span>{{ \Carbon\Carbon::parse($invoice->paid_at ?? $invoice->created_at)->format('m/d/Y h:i A') }}</span></div>
    <div class="meta-row"><span>Customer</span><span>{{ $invoice->user->name }}</span></div>
  </div>

  <hr class="dashed"/>

  <!-- Items -->
  <div class="items">
    <div class="item-header">
      <span>Item</span><span>Total</span>
    </div>
    @foreach($invoice->order->orderItems as $item)
    <div class="item-row">
      <div class="item-name">{{ $item->product->name }}</div>
      <div class="item-detail">
        <span>{{ $item->quantity }} {{ $item->product->unit }} x ₱{{ number_format($item->unit_price, 2) }}</span>
        <span>₱{{ number_format($item->total_price, 2) }}</span>
      </div>
    </div>
    @endforeach
  </div>

  <hr class="solid"/>

  <!-- Totals -->
  <div class="totals">
    <div class="total-row"><span>Subtotal</span><span>₱{{ number_format($invoice->amount, 2) }}</span></div>
    <div class="total-row"><span>Shipping</span><span>₱0.00</span></div>
    <div class="total-row grand"><span>TOTAL</span><span>₱{{ number_format($invoice->amount, 2) }}</span></div>
  </div>

  <hr class="dashed"/>

  <!-- Payment -->
  <div class="payment">
    <div class="payment-row">
      <span>Payment</span>
      <span>
        @if($invoice->order->payment_method === 'gcash')
          GCash{{ $invoice->order->gcash_number ? ' ('.$invoice->order->gcash_number.')' : '' }}
        @else
          Cash on Delivery
        @endif
      </span>
    </div>
    <div class="payment-row">
      <span>Status</span>
      <span class="status {{ $invoice->status }}">{{ strtoupper($invoice->status) }}</span>
    </div>
    @if($invoice->order->shipping_address)
    <div style="margin-top:4px">
      <span>Ship to: </span><span>{{ $invoice->order->shipping_address }}</span>
    </div>
    @endif
  </div>

  <hr class="dashed"/>

  <!-- Footer -->
  <div class="footer">
    <div class="thank-you">Thank You!</div>
    <p>Salamat sa inyong pagbili.<br/>Ingatan ang resibong ito.</p>
  </div>

</div>

<button class="print-btn" onclick="window.print()">🖨 Print / Save as PDF</button>

</body>
</html>
