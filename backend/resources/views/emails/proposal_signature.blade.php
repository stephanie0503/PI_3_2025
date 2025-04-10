<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Assine sua proposta' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2d3e50;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        footer {
            font-size: 12px;
            color: #aaa;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Olá!</h1>
        <p>Esse e-mail é referente a sua participação na assinatura digital de documentos pela <Strong>Dinâmica Informática e Telecomunicações</Strong>.</p>

        <p>Clique no botão abaixo para assinar a proposta:</p>

        <a href="{{ $link }}" class="btn">Assinar Proposta</a>

        <footer>
            <p>Se você não reconhece essa solicitação, por favor, ignore este e-mail.</p>
        </footer>
    </div>

</body>

</html>