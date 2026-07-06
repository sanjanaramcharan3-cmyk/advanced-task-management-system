<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
</head>
<body>

<h1>Edit Task</h1>

<form method="POST" action="/tasks/{{ $task->id }}">
    @csrf
    @method('PUT')

    <input type="text" name="title" value="{{ $task->title }}"><br><br>

    <textarea name="description">{{ $task->description }}</textarea><br><br>

    <button>Update Task</button>
</form>

<br>
<a href="/tasks">Back</a>

</body>
</html>