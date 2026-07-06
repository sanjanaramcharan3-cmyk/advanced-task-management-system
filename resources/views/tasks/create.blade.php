<!DOCTYPE html>
<html>
<head>
    <title>Add Task</title>
</head>
<body>

<h1>Add Task</h1>

<form method="POST" action="/tasks">
    @csrf

    <input type="text" name="title" placeholder="Title"><br><br>

    <textarea name="description" placeholder="Description"></textarea><br><br>

    <label>Due Date:</label>
    <input type="date" name="due_date" required><br><br>

    <label>Assign User:</label>
    <select name="user_id" required>
        <option value="">Select User</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>

    <br><br>

    <button>Save Task</button>
</form>

<br>
<a href="/tasks">Back</a>

</body>
</html>