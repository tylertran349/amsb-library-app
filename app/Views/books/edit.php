<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <link rel="stylesheet" type="text/css" href="<?= base_url('styles/style.css') ?>">
</head>
<body>

    <div class="container">
        <h1>Edit Book</h1>

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

        <form action="/books/update/<?= $book['id'] ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <label for="title">Title <span style="color: red;">*</span></label>
            <input type="text" name="title" id="title" value="<?= old('title', esc($book['title'])) ?>">

            <label for="author">Author <span style="color: red;">*</span></label>
            <input type="text" name="author" id="author" value="<?= old('author', esc($book['author'])) ?>">

            <label for="genre">Genre</label>
            <input type="text" name="genre" id="genre" value="<?= old('genre', esc($book['genre'])) ?>">

            <label for="publication_year">Publication Year <span style="color: red;">*</span></label>
            <input type="number" name="publication_year" id="publication_year" value="<?= old('publication_year', esc($book['publication_year'])) ?>" placeholder="e.g., 2023">

            <?php if (!empty($book['cover_image'])): ?>
                <div class="current-image">
                    <label>Current Cover Image</label>
                    <img src="<?= base_url('uploads/' . esc($book['cover_image'])) ?>" alt="Current Cover Image">
                </div>
            <?php endif; ?>

            <label for="cover_image">Upload New Cover Image (Optional)</label>
            <p style="font-size: 0.8em; color: #6c757d;">Leave blank to keep the current image.</p>
            <input type="file" name="cover_image" id="cover_image">

            <input type="submit" value="Update Book">
        </form>

        <a href="/" class="back-link">← Back to List</a>
    </div>

</body>
</html>