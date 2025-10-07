<div class="borrow-book-page">
	<div class="container">
		<!-- Search & Book List -->
		<div class="card book-selection">
			<h2>Search Book</h2>
				<div class="search-input input-container">
					<input type="text" id="bookListSearchBox" placeholder="Search by Title, Author, or ISBN">
				</div>

			<div class="book-list" id="bookList">
				<?php if (count($books) > 0): ?>

					<?php foreach ($books as $book): ?>

						<div class="book-item <?= $book['status'] != "Available" ? 'not-available' : '' ?>" data-id="<?= $book['ISBN'] ?>">
							<div>
								<img src="<?= isImageUrl($book['image']) ? $book['image'] : getFile('public/img/' . $book['image']) ?>" alt="">
							</div>
							<div>
								<strong><?= $book['title'] ?></strong><br>
								by <?= $book['author'] ?><br>
								ISBN: <?= $book['ISBN'] ?>
								<div class="availability <?= strtolower($book['status']) ?>"><?= $book['status'] ?></div>
							</div>
						</div>

					<?php endforeach; ?>

				<?php else: ?>

					<h2>No books</h2>

				<?php endif; ?>
			</div>
		</div>

		<!-- Borrower Info -->
		<div class="card borrower-selection">
			<h2>Borrower Information</h2>
			<form id="borrowForm" method="POST" action="<?= routeTo('/borrowed-books/add') ?>" novalidate>
				<div class="search-input input-container">
					<input type="text" id="borrowerListSearchBox" placeholder="Search by Name, Code, Email, or Address">
				</div>
				<div class="borrower-list" id="borrowerList">
					<?php if (count($borrowers) > 0): ?>

						<?php foreach ($borrowers as $borrower): ?>

							<div class="borrower <?= $borrower['status'] != "active" ? 'not-available' : '' ?>" data-id="<?= $borrower['borrower_code'] ?>">
								<div>
									<strong><?= $borrower['first_name'] . ' ' . $borrower['last_name'] ?></strong><br>
									Code: <?= $borrower['borrower_code'] ?><br>
									Email: <?= $borrower['email'] ?><br>
									Address: <?= $borrower['address'] ?>
									<div class="availability <?= $borrower['status'] != "active" ? 'unavailable' : 'available' ?>"><?= ucfirst($borrower['status']) ?></div>
								</div>
							</div>

						<?php endforeach; ?>

					<?php else: ?>

						<h2>No books</h2>

					<?php endif; ?>
				</div>

				<div class="due-date-input input-container">
					<label for="dueDate" id="due-date-label">Due Date:</label>
					<input type="date" name="due_date" id="dueDate" required>
				</div>

				<input type="hidden" name="book_id" id="selectedBookId">
				<input type="hidden" name="borrower_code" id="selectedBorrowerId">

				<div class="buttons">
					<button type="submit" class="button default" onclick="showAlert(event, 'question')">Confirm Borrow</button>
					<a href="<?= routeTo('/borrowed-books') ?>" class="button default reset">Reset</a>
				</div>
			</form>
		</div>
	</div>

	<?php ob_start(); ?>

		<?php foreach ($items as $item): ?>
			<tr>
				<td>
					<div class="book-info">
						<strong><?= $item['title'] ?></strong> <br>
						by <?= $item['author'] ?> <br>
						ISBN: <?= $item['ISBN'] ?>
					</div>
				</td>
				<td><?= $item['first_name'] ?></td>
				<td><?= $item['last_name'] ?></td>
				<td><?= $item['borrow_date'] ?></td>
				<td><?= $item['due_date'] ?></td>
				<td><span class="status <?= $item['status'] ? "offline" : "online" ?>"><?= $item['status'] ? "Overdue" : "On Time" ?></span></td>
				<td>
					<div class="action-buttons">
						<form class="delete-transaction-form" action="<?= routeTo('/borrowed-books/delete/' . $item['borrowed_id']) ?>" method="POST">
							<input type="hidden" name="_method" value="DELETE">
							<button type="button" class="button act-remove danger" onclick="showAlert(event)"><i class="fa-regular fa-trash"></i></button>
						</form>
					</div>
				</td>
			</tr>
		<?php endforeach; ?>

	<?php $tableData = ob_get_clean(); ?>

	<?php require 'src/Views/partials/table.php' ?>
</div>


<script>
	const books = document.querySelectorAll('.book-item');
	const borrowers = document.querySelectorAll('.borrower');
	const hiddenBookId = document.getElementById('selectedBookId');
	const hiddenBorrowerId = document.getElementById('selectedBorrowerId');

	books.forEach(book => {
		book.addEventListener('click', () => {
			if (book.classList.contains('not-available')) return;

			books.forEach(b => b.classList.remove('selected'));
			book.classList.add('selected');
			hiddenBookId.value = book.dataset.id;
		});
	});

	borrowers.forEach(borrower => {
		borrower.addEventListener('click', () => {
			if (borrower.classList.contains('not-available')) return;

			borrowers.forEach(b => b.classList.remove('selected'));
			borrower.classList.add('selected');
			hiddenBorrowerId.value = borrower.dataset.id;
		});
	});

	document.getElementById("bookListSearchBox").addEventListener("keyup", function () {
		let query = this.value;

		fetch("<?= routeTo('/borrowed-books/search-book') ?>?q=" + encodeURIComponent(query))
			.then(response => response.json())
			.then(books => {
				const bookList = document.getElementById("bookList");
				bookList.innerHTML = "";

				if (books.length === 0) {
					bookList.innerHTML = "<h2>No books found</h2>";
					return;
				}

				books.forEach(book => {
					const div = document.createElement("div");
					div.classList.add("book-item");
					if (book.status !== "Available") div.classList.add("not-available");
					div.dataset.id = book.ISBN;

					const imgDiv = document.createElement("div");
					const img = document.createElement("img");
					img.src = book.image;
					imgDiv.appendChild(img);

					const bookInfo = document.createElement("div");

					bookInfo.innerHTML = `
						<strong>${book.title}</strong><br>
						by ${book.author}<br>
						ISBN: ${book.ISBN}
						<div class="availability ${book.status.toLowerCase()}">${book.status}</div>
					`;

					div.append(
						imgDiv,
						bookInfo
					);

					div.addEventListener("click", () => {
						if (div.classList.contains("not-available")) return;
						document.querySelectorAll('.book-item').forEach(b => b.classList.remove("selected"));
						div.classList.add("selected");
						document.getElementById("selectedBookId").value = div.dataset.id;
					});

					bookList.appendChild(div);
				});
			});
	});

	document.getElementById("borrowerListSearchBox").addEventListener("keyup", function () {
		let query = this.value;

		fetch("<?= routeTo('/borrowed-books/search-borrower') ?>?q=" + encodeURIComponent(query))
			.then(response => response.json())
			.then(borrowers => {
				const borrowerList = document.getElementById("borrowerList");
				borrowerList.innerHTML = "";

				if (borrowers.length === 0) {
					borrowerList.innerHTML = "<h2>No borrowers found</h2>";
					return;
				}


				borrowers.forEach(borrower => {
					const div = document.createElement("div");
					div.classList.add("borrower");
					if (borrower.status !== "active") div.classList.add("not-available");
					div.dataset.id = borrower.borrower_code;

					const borrowerInfo = document.createElement("div");

					borrowerInfo.innerHTML = `
						<strong>${borrower.first_name + " " + borrower.last_name}</strong><br>
						Code: ${borrower.borrower_code}<br>
						Email: ${borrower.email}<br>
						Address: ${borrower.address}
						<div class="availability ${borrower.status != "active" ? "unavailable" : "available"}">${borrower.status.toUpperCase()}</div>
					`;

					div.appendChild(borrowerInfo);

					div.addEventListener("click", () => {
						if (div.classList.contains("not-available")) return;
						document.querySelectorAll('.borrower').forEach(b => b.classList.remove("selected"));
						div.classList.add("selected");
						document.getElementById("selectedBorrowerId").value = div.dataset.id;
					});

					borrowerList.appendChild(div);
				});
			});
	});

</script>