<?php ob_start(); ?>

    <?php foreach ($items as $item): ?>
        <tr>
            <td>
                <?php if(isImageUrl($item['image'] ?? "")): ?>
                    <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>">
                <?php else: ?>
                    <img src="<?= getFile('public/img/' . $item['image']) ?>" alt="<?= $item['title'] ?>">
                <?php endif; ?>
            </td>
            
            <td><?= $item['ISBN'] ?></td>
            <td><?= $item['author'] ?></td>
            <td><?= $item['publisher'] ?></td>
            <td><?= $item['title'] ?></td>
            <td><?= $item['genre'] ?></td>
            <td><?= $item['quantity'] ?></td>
            <td><span class="status <?= $item['status'] == "Available" ? "online" : "offline" ?>"><?= $item['status'] ?></span></td>
            <td>
                <div class="action-buttons">
                    <a href="<?= routeTo("/books/edit/" . $item['ISBN']) ?>" class="button act-edit safe"><i class="fa-solid fa-pen-to-square"></i></a>
                    <form class="delete-book-form" action="<?= routeTo('/books/delete/' . $item['ISBN']) ?>" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="button act-remove danger"><i class="fa-regular fa-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>

<?php $tableData = ob_get_clean(); ?>

<?php require 'src/Views/partials/table.php' ?>