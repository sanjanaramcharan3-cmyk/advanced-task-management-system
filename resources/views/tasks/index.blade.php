<!DOCTYPE html>
<html>
<head>
    <title>Task Board</title>
    @vite('resources/js/app.js')
</head>
<body>

<h2 style="padding:10px;">
    Welcome {{ Auth::user()->name }} 👋
</h2>

<div id="app"></div>

</body>
</html>