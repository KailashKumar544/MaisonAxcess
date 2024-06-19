<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle enquête</title>
</head>
<body>
    <h1>Soumission du formulaire de contact</h1>
    <p><strong>Nom:</strong> {{ $formData['name'] }}</p>
    <p><strong>E-mail:</strong> {{ $formData['email'] }}</p>
    <p><strong>Sujet:</strong> {{ $formData['subject'] }}</p>
    <p><strong>Message:</strong> {{ $formData['message'] }}</p>
</body>
</html>
