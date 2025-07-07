<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Book</title>
    <style>
        body { font-family: sans-serif; margin: 2em; }
        form { max-width: 500px; margin: auto; }
        label { display: block; margin-top: 1em; }
        input[type=text], input[type=number] { width: 100%; padding: 8px; margin-top: 0.5em; }
        input[type=submit] { margin-top: 2em; padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .back-link { display: block; margin-top: 1em; }
    </style>
</head>
<body>

    <h1>Add New Book</h1>

    <!-- The form will be submitted to the 'store' method -->
    <form action="/books/create" method="post">
        <?= csrf_field() ?> <!-- Important for security -->

        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="">

        <label for="author">Author</label>
        <input type="text" name="author" id="author" value="">

        <label for="genre">Genre</label>
        <input type="text" name="genre" id="genre" value="">

        <label for="publication_year">Publication Year</label>
        <input type="number" name="publication_year" id="publication_year" value="">

        <input type="submit" value="Save">
    </form>

    <a href="/" class="back-link">Back to List</a>

</body>
</html>