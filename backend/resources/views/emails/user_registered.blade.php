<!DOCTYPE html>
<html>
<head>
    <title>Bem-vindo ao Nosso Serviço</title>
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background-color: #ECEFF1;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <table align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td style="padding: 24px;">
                <table style="width: 100%;" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="background-color: #ffffff; border-radius: 4px; padding: 48px; text-align: left; color: #626262;">
                            <p style="font-weight: 600; font-size: 18px;">Olá</p>
                            <p style="font-weight: 700; font-size: 20px; color: #ff5850;">{{ $name ?? 'usuário' }}!</p>
                            <p style="font-weight: 600; font-size: 20px; color: #263238;">Obrigado por se inscrever! 👋</p>
                            <p>Por favor, verifique seu endereço de e-mail clicando no botão abaixo.</p>
                            <p>Se você não se inscreveu, por favor, ignore este e-mail.</p>
                            <a href="{{ url('verify-email?token=' . $token) }}" style="color: #7367f0;">Verificar E-mail Agora →</a>
                            <table style="width: 100%;" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background-color: #7367f0; border-radius: 4px;">
                                        <a href="{{ url('verify-email?token=' . $token) }}" style="display: block; padding: 16px 24px; color: #ffffff; text-decoration: none;">Verificar E-mail Agora →</a>
                                    </td>
                                </tr>
                            </table>
                            <div style="background-color: #eceff1; height: 1px;"></div>
                            <p>Não tem certeza do motivo pelo qual recebeu este e-mail? Por favor, <a href="connecta@dinamicasp.com.br" style="color: #7367f0;">nos avise</a>.</p>
                            <p>Obrigado,<br>Dinamica Telecom</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
