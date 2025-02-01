{{-- resources/views/emails/orders/partials/_styles.blade.php --}}
<style>
    body {
        margin: 0;
        padding: 0;
        width: 100%;
        background-color: #f5f5f5;
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        font-family: Arial, sans-serif;
    }

    .email-wrapper {
        width: 100%;
        margin: 0;
        padding: 20px 0;
        background-color: #f5f5f5;
    }

    .email-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .email-header {
        text-align: center;
        padding-bottom: 30px;
        border-bottom: 1px solid #eee;
    }

    .header-title {
        color: #333333;
        font-size: 24px;
        font-weight: bold;
        margin: 0 0 10px 0;
    }

    .logo {
        max-width: 200px;
        margin-bottom: 20px;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .order-table th {
        background-color: #f8f9fa;
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #eee;
        color: #333333;
    }

    .order-table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        color: #666666;
    }

    .button {
        display: inline-block;
        padding: 12px 24px;
        background-color: #007bff;
        color: #ffffff !important;
        text-decoration: none;
        border-radius: 4px;
        font-weight: bold;
        margin: 20px 0;
    }

    .footer {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
        text-align: center;
        color: #666666;
    }

    @media only screen and (max-width: 600px) {
        .email-container {
            padding: 15px;
        }

        .order-table {
            font-size: 14px;
        }
    }
</style>
