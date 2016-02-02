<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {{ get_title() }}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PHP Framework">
    <meta name="author" content="Phan Nguyen">
</head>
<body>
{% include "layouts/header.volt" %}
{{ content() }}
{% include "layouts/footer.volt" %}
</body>
</html>
