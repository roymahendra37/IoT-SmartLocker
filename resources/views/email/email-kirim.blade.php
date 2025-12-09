<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ada Barang Untuk Anda di SmartLocker!</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f6fa; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 30px auto; background-color: #ffffff; border-radius: 10px; padding: 25px; box-shadow: 0 3px 8px rgba(0,0,0,0.1);">
        <h2 style="color: #2d3436; text-align: center;">Smart Locker - Penerimaan Barang</h2>
        <p>Halo <strong>{{ $transaction->receiver_name }}</strong>,</p>
        <p>Anda menerima kiriman barang dari <strong>{{ $transaction->name }}</strong> melalui layanan SmartLocker pada <strong>{{ \Carbon\Carbon::parse($transaction->start_time)->format('d M Y H:i') }}</strong></p>

        <table style="width:100%; border-collapse: collapse; margin: 15px 0;">
            <tr>
                <td><strong>Nomor Loker:</strong></td>
                <td>{{ $locker->locker_number }}</td>
            </tr>
            <tr>
                <td><strong>Berakhir:</strong></td>
                <td>{{ \Carbon\Carbon::parse($transaction->end_time)->format('d M Y H:i') }}</td>
            </tr>
        </table>

        <p style="margin-top: 10px; text-align: center;"><strong>Gunakan QR Code untuk membuka loker!</strong></p>
        <div style="text-align: center; margin: 20px 0;">
            <img src="{{ $message->embed($final_qr_path) }}" alt="QR Code" style="width: 250px; border: 8px solid #fff; border-radius: 10px;">
        </div>
        <p style="text-align: center;">Terima kasih telah menggunakan layanan</p>
        <h3 style="text-align: center;"><strong>Smart Locker</strong></h3>

        <hr style="border: none; border-top: 1px solid #eee;">
        <p style="text-align: center; font-size: 12px; color: #888;">Email ini dikirim otomatis. Mohon tidak membalas langsung.</p>
    </div>
</body>
</html>