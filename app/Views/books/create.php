<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Book</title>
    <link rel="stylesheet" type="text/css" href="<?= base_url('styles/style.css') ?>">
</head>
<body>

    <div class="form-container">
        <h1>Add New Book</h1>

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

        <form action="/books/create" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <label for="title">Title <span style="color: red;">*</span></label>
            <input type="text" name="title" id="title" value="<?= old('title') ?>">

            <label for="author">Author <span style="color: red;">*</span></label>
            <input type="text" name="author" id="author" value="<?= old('author') ?>">

            <label for="genre">Genre</label>
            <input type="text" name="genre" id="genre" value="<?= old('genre') ?>">

            <label for="publication_year">Publication Year <span style="color: red;">*</span></label>
            <input type="number" name="publication_year" id="publication_year" value="<?= old('publication_year') ?>" placeholder="e.g., 2023">
            <label for="cover_image">Book Cover Image (Optional)</label>
            <input type="file" name="cover_image" id="cover_image">

            <input type="submit" value="Save Book">
        </form>
        <a href="/" class="back-link">← Back to List</a>
    </div>
</body>
</html>