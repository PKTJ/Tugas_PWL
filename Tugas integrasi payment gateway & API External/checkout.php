<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-xxxxxxxxxxxxxxxxxxxx"></script> <!-- Ganti dengan Client Key Anda -->
</head>
<body>
    <h2>Checkout</h2>
    <button id="pay-button">Bayar Sekarang</button>

    <script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        fetch('charge.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                window.snap.pay(data.token, {
                    onSuccess: function(result){
                        console.log('success:', result);
                        alert('Pembayaran berhasil!');
                    },
                    onPending: function(result){
                        console.log('pending:', result);
                        alert('Pembayaran pending, silakan selesaikan pembayaran');
                    },
                    onError: function(result){
                        console.log('error:', result);
                        alert('Pembayaran gagal!');
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses pembayaran');
            });
    };
    </script>
</body>
</html>
