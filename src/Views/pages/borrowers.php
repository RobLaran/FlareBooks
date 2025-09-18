<?php ob_start(); ?>

    <?php foreach ($array as $item): ?>
        <tr>
            <td><?= $item['borrower_code'] ?></td>
            <td><?= $item['first_name'] ?></td>
            <td><?= $item['last_name'] ?></td>
            <td><?= $item['email'] ?></td>
            <td><?= $item['phone'] ?></td>
            <td><?= $item['address'] ?></td>
            <td><?= $item['date_of_birth'] ?></td>
            <td><?= $item['membership_date'] ?></td>
            <td><span class="status <?= $item['status'] == "active" ? "online" : "offline" ?>"><?= ucfirst($item['status']) ?></span></td>
            <td>
                <div class="action-buttons">
                    <button class="button act-edit safe"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button class="button act-remove danger"><i class="fa-regular fa-trash"></i></button>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>

<?php $tableData = ob_get_clean(); ?>

<?php require 'src/Views/partials/table.php' ?>