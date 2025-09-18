<div class="genres-page-content">
    <div class="table-container">
        <div class="row one">
            <button class="button default">ADD</button>
            
        </div>

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

        <?php if(count($genres) == 0): ?>

        <h1 class="empty-notif">No Genres Added</h1>

        <?php else: ?>

        <div class="table-container row three">
            <table class="table-zebra">
                <thead>
                    <tr class="table-heading">
                        <?php 
                        $columns = [
                            "id" => "Number",
                            "genre" => "Genre",
                            "description" => "Description",
                            "status" => "Status"
                        ];

                        foreach ($columns as $col => $label): 
                            // Toggle direction if current column is sorted
                            $newDir = ($sortBy === $col && $sortDir === 'ASC') ? 'DESC' : 'ASC';
                        ?>
                            <th>
                                <?= $label ?>
                                <a href="?page=<?= $page ?>&limit=<?= $limit ?>&sortBy=<?= $col ?>&sortDir=<?= $newDir ?>">
                                    <i class="fa fa-sort<?= ($sortBy === $col ? '-' . strtolower($sortDir) : '') ?>"></i>
                                </a>
                            </th>
                        <?php endforeach; ?>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($genres) > 0): ?>

                        <?php foreach ($genres as $genre): ?>
                            <tr>
                                <td><?= $genre['id'] ?></td>
                                <td><?= $genre['genre'] ?></td>
                                <td><?= $genre['description'] ?></td>
                                <td><span class="status <?= $genre['status'] == 1 ? 'online' : 'offline' ?>"><?= $genre['status'] == 1 ? 'Active' : 'Inactive' ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="button act-edit safe"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button class="button act-remove danger"><i class="fa-regular fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <h1>No Genres Added</h1>

                    <?php endif; ?>
                    
                </tbody>
            </table>
        </div>

        <div class="entries-pagination-container row four">
            <div class="showed-entries">
                <?php if ($totalGenres > 0): ?>
                    <span>
                        Showing <?= ($page - 1) * $limit + 1 ?> 
                        to <?= min($page * $limit, $totalGenres) ?> 
                        of <?= $totalGenres ?> entries
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