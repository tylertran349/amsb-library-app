<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Library Books</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?= base_url('styles/style.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poly:ital@0;1&display=swap" rel="stylesheet">
</head>
<body>
    <?php if (session()->getFlashdata('message')): ?>
        <div class="success-message">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>
    
    <div class="header-container">
        <h1>List of Books</h1>
        <a href="/books/new" class="add-book-btn">Add Book</a>
    </div>

    <div class="book-container">
        <?php if (!empty($books) && is_array($books)): ?>
            <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <div class="book-details">
                        <?php if (!empty($book['cover_image'])): ?>
                            <img src="<?= base_url('uploads/' . esc($book['cover_image'])) ?>" alt="<?= esc($book['title']) ?>" class="book-cover">
                        <?php else: ?>
                            <div class="book-cover placeholder">No Image</div>
                        <?php endif; ?>
                        <div class="book-text-content">
                            <h2 class="book-title"><?= esc($book['title']) ?></h2>
                            <div class="book-meta">
                                <p class="book-info">Author: <?= esc($book['author']) ?></p>
                                <p class="book-info">Genre: <?= esc($book['genre']) ?></p>
                                <p class="book-info">Publication year: <?= esc($book['publication_year']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="book-actions">
                        <a href="/books/edit/<?= $book['id'] ?>" class="action-btn edit-btn">Edit</a>
                        <form action="/books/delete/<?= $book['id'] ?>" method="post" style="display:inline;">
                            <?= csrf_field() ?>
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this book?')" class="action-btn delete-btn">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-books-message">No books found. Click "Add New Book" to get started!</p>
        <?php endif; ?>
    </div>

</body>
</html>