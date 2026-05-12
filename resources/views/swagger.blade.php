<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rozanadejda API — Swagger</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css" crossorigin="anonymous">
    <style>body { margin: 0; }</style>
</head>
<body>
<div id="swagger-ui"></div>
<script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js" crossorigin="anonymous"></script>
<script>
    window.onload = function () {
        window.ui = SwaggerUIBundle({
            url: @json(url('/docs/openapi.yaml')) ,
            dom_id: '#swagger-ui',
            persistAuthorization: true,
        });
    };
</script>
</body>
</html>
