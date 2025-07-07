<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Book</title>
    <!-- Using a consistent style block -->
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; margin: 2em; background-color: #f8f9fa; color: #212529; }
        .container { max-width: 600px; margin: auto; background: white; padding: 2em; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #343a40; }
        form { margin-top: 1.5em; }
        label { display: block; margin-top: 1em; font-weight: 500; }
        input[type=text], input[type=number] { width: 100%; padding: 0.75em; margin-top: 0.5em; border-radius: 4px; border: 1px solid #ced4da; box-sizing: border-box; }
        input[type=submit] { margin-top: 2em; padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 1em; }
        input[type=submit]:hover { background-color: #218838; }
        .back-link { display: inline-block; margin-top: 1.5em; color: #007bff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .errors { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 1rem; border-radius: 5px; margin-bottom: 20px; }
        .errors ul { padding-left: 20px; margin: 0; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Add New Book</h1>

        <!-- This block will display validation errors -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="errors">
                <strong>Please correct the following errors:</strong>
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Make sure the form action is correct for presenter routing -->
        <form action="/books/create" method="post">
            <?= csrf_field() ?> <!-- Don't forget the CSRF field for security -->

            <label for="title">Title <span style="color: red;">*</span></label>
            <!-- The old() function re-populates the field on validation error -->
            <input type="text" name="title" id="title" value="<?= old('title') ?>">

            <label for="author">Author <span style="color: red;">*</span></label>
            <input type="text" name="author" id="author" value="<?= old('author') ?>">

            <label for="genre">Genre</label>
            <input type="text" name="genre" id="genre" value="<?= old('genre') ?>">

            <label for="publication_year">Publication Year <span style="color: red;">*</span></label>
            <input type="number" name="publication_year" id="publication_year" value="<?= old('publication_year') ?>" placeholder="e.g., 2023">

            <input type="submit" value="Save Book">
        </form>

        <a href="/" class="back-link">← Back to List</a>
    </div>

</body>
</html>