<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <!-- (You can copy the same <style> block from create.php) -->
    <style>
        body { font-family: sans-serif; margin: 2em; }
        form { max-width: 500px; margin: auto; }
        label { display: block; margin-top: 1em; }
        input[type=text], input[type=number] { width: 100%; padding: 8px; margin-top: 0.5em; }
        input[type=submit] { margin-top: 2em; padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .back-link { display: block; margin-top: 1em; }
    </style>
</head>
<body>

    <h1>Edit Book</h1>

    <!-- The form submits to the 'update' method with the book's ID -->
    <form action="/books/update/<?= $book['id'] ?>" method="post">
        <?= csrf_field() ?>

        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="<?= esc($book['title']) ?>">

        <label for="author">Author</label>
        <input type="text" name="author" id="author" value="<?= esc($book['author']) ?>">

        <label for="genre">Genre</label>
        <input type="text" name="genre" id="genre" value="<?= esc($book['genre']) ?>">

        <label for="publication_year">Publication Year</label>
        <input type="number" name="publication_year" id="publication_year" value="<?= esc($book['publication_year']) ?>">

        <input type="submit" value="Update">
    </form>

    <a href="/" class="back-link">Back to List</a>

</body>
</html>