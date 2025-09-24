<div class="books-page-content">
    <div class="table-container">
        <?php if(isset($addButton)): ?>
            <div class="row one">
                <a href="<?= routeTo($addButton['route']) ?>" class="button default"><?= $addButton['label']?></a>
            </div>
        <?php endif; ?>
        
        <div class="row two">
            <div class="select-entries">
                <span>
                    Showing 
                    <form method="GET" id="entries-form" style="display:inline;">
                        <select name="limit" id="entries-selection" onchange="document.getElementById('entries-form').submit()">
                            <option value="all" <?= $limit == 'all' ? 'selected' : '' ?>>All</option>
                            <option value="5" <?= $limit == 5 ? 'selected' : '' ?>>5</option>
                            <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                            <option value="15" <?= $limit == 15 ? 'selected' : '' ?>>15</option>
                            <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
                        </select>
                        <input type="hidden" name="page" value="1">
                    </form>
                    entries
                </span>
            </div>

            <div class="search-input-container">
                <form method="GET" style="display:inline;">
                    <label for="search-input">Search:</label>
                    <input type="text" id="search-input" name="search" value="<?= htmlspecialchars($search) ?>">
                    <input type="hidden" name="limit" value="<?= $limit ?>">
                    <input type="hidden" name="sortBy" value="<?= $sortBy ?>">
                    <input type="hidden" name="sortDir" value="<?= $sortDir ?>">
                    <button type="submit" class="submit-search button default">Go</button>
                </form>
            </div>

        </div>

        <?php if(count($books) == 0): ?>

        <h1 class="empty-notif">No Books Added</h1>

        <?php else: ?>

        <div class="table-container row three">
            <table class="table-zebra">
                <thead>
                    <tr class="table-heading">
                        <?php 
                        foreach ($columns as $col => $values): 
                            $newDir = ($sortBy === $col && $sortDir === 'ASC') ? 'DESC' : 'ASC';
                        ?>

                            <?php if($values['sortable']): ?>
                                <th>
                                    <?= $values['name'] ?>
                                    <a href="?page=<?= $page ?>&limit=<?= $limit ?>&sortBy=<?= $col ?>&sortDir=<?= $newDir ?>">
                                        <i class="fa fa-sort<?= ($sortBy === $col ? '-' . strtolower($sortDir) : '') ?>"></i>
                                    </a>
                                </th>
                            <?php else: ?>
                                <th>
                                    <?= $values['name'] ?>
                                </th>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($books) > 0): ?>

                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td>
                                    <?php if(isImageUrl($book['image'] ?? "")): ?>
                                        <img src="<?= $book['image'] ?>" alt="<?= $book['title'] ?>">
                                    <?php else: ?>
                                        <img src="<?= getFile('public/img/' . $book['image']) ?>" alt="<?= $book['title'] ?>">
                                    <?php endif; ?>
                                </td>
                                
                                <td><?= $book['ISBN'] ?></td>
                                <td><?= $book['author'] ?></td>
                                <td><?= $book['publisher'] ?></td>
                                <td><?= $book['title'] ?></td>
                                <td><?= $book['genre'] ?></td>
                                <td><?= $book['quantity'] ?></td>
                                <td><span class="status <?= $book['status'] == "Available" ? "online" : "offline" ?>"><?= $book['status'] ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= routeTo("/books/edit/" . $book['ISBN']) ?>" class="button act-edit safe"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <form class="delete-book-form" action="<?= routeTo('/books/delete/' . $book['ISBN']) ?>" method="POST">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="button act-remove danger"><i class="fa-regular fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php endif; ?>
                    
                </tbody>
            </table>
        </div>

        <div class="entries-pagination-container row four">
            <div class="showed-entries">
                <?php if ($totalBooks > 0): ?>
                    <span>
                        Showing <?= ($page - 1) * $limit + 1 ?> 
                        to <?= min($page * $limit, $totalBooks) ?> 
                        of <?= $totalBooks ?> entries
                    </span>
                <?php endif; ?>
            </div>

            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page previous">
                        <a href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>">Prev</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page <?= $i == $page ? 'current' : '' ?>">
                        <a href="?page=<?= $i ?>&limit=<?= $limit ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li class="page next">
                        <a href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <?php endif; ?>

    </div>
</div>