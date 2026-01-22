
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-md">
    <h1 class="text-2xl font-bold mb-6">Detail Transaksi</h1>

    <div class="space-y-3">
        <p><strong>Ref ID:</strong> {{ $transaksi->ref_id }}</p>
        <p><strong>Buyer SKU Code:</strong> {{ $transaksi->buyer_sku_code }}</p>
        <p><strong>Customer No:</strong> {{ $transaksi->customer_no }}</p>
        <p><strong>Product Name:</strong> {{ $transaksi->product_name }}</p>
        <p><strong>Price:</strong> Rp{{ number_format($transaksi->price, 0, ',', '.') }}</p>
        <p><strong>Status:</strong> {{ $transaksi->status }}</p>
        <p><strong>Message:</strong> {{ $transaksi->message }}</p>
        <p><strong>SN:</strong> {{ $transaksi->sn }}</p>
        <p><strong>Dibuat:</strong> {{ $transaksi->created_at }}</p>
        <p><strong>Diperbarui:</strong> {{ $transaksi->updated_at }}</p>
    </div>
</div>

