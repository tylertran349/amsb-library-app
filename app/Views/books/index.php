<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Library Books</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: sans-serif; margin: 2em; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; }
        th { background-color: #f2f2f2; }
        .action-links a { margin-right: 10px; }
        .add-book-btn { display: inline-block; margin-bottom: 20px; padding: 10px 15px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; }
        .book-cover { max-width: 60px; height: auto; border-radius: 4px; }
        .placeholder { width: 60px; height: 90px; background-color: #e9ecef; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #6c757d; font-size: 0.8em; }
    </style>
</head>
<body>
    <?php if (session()->getFlashdata('message')): ?>
        <div class="success-message" style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>
    <h1>List of Books</h1>

    <a href="/books/new" class="add-book-btn">Add New Book</a>

    <table>
        <thead>
            <tr>
                <th>Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>Publication Year</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($books) && is_array($books)): ?>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td>
                            <?php if (!empty($book['cover_image'])): ?>
                                <img src="<?= base_url('uploads/' . esc($book['cover_image'])) ?>" alt="<?= esc($book['title']) ?>" class="book-cover">
                            <?php else: ?>
                                <div class="placeholder">No Image</div>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($book['title']) ?></td>
                        <td><?= esc($book['author']) ?></td>
                        <td><?= esc($book['genre']) ?></td>
                        <td><?= esc($book['publication_year']) ?></td>
                        <td class="action-links">
                            <a href="/books/edit/<?= $book['id'] ?>">Edit</a>
                            <form action="/books/delete/<?= $book['id'] ?>" method="post" style="display:inline;">
                                <?= csrf_field() ?>
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this book?')" style="background:none; border:none; color:red; cursor:pointer; padding:0; text-decoration:underline;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No books found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>