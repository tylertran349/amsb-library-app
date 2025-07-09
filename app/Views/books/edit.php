<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit Book</title>
        <link rel="stylesheet" type="text/css" href="<?= base_url('styles/style.css') ?>">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Domine:wght@400..700&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="form-wrapper">
            <div class="form-container">
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
                            <img src="<?= base_url('uploads/' . esc($book['cover_image'])) ?>" alt="Current Cover Image" style="max-width: 150px; display: block; margin-top: 5px; margin-bottom: 15px;">
                        </div>
                    <?php endif; ?>

                    <label for="cover_image">Upload New Cover Image (Optional)</label>
                    <input type="file" name="cover_image" id="cover_image" accept="image/*">
                    
                    <input type="submit" value="Update Book">
                </form>
                <a href="/" class="back-link">← Back to List</a>
            </div>
        </div>
    </body>
</html>