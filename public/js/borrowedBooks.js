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
		const route = this.dataset.route;

		fetch(`${route}?q=` + encodeURIComponent(query))
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
					if (book.Status !== "Available") div.classList.add("not-available");
					div.dataset.id = book.ISBN;

					const imgDiv = document.createElement("div");
					const img = document.createElement("img");
					img.src = book.Image;
					imgDiv.appendChild(img);

					const bookInfo = document.createElement("div");

					bookInfo.innerHTML = `
						<strong>${book.Title}</strong><br>
						by ${book.Author}<br>
						ISBN: ${book.ISBN}
						<div class="availability ${book.Status.toLowerCase()}">${book.Status}</div>
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
		const route = this.dataset.route;

		fetch(`${route}?q=` + encodeURIComponent(query))
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
					if (borrower['Status'] !== "Active") div.classList.add("not-available");
					div.dataset.id = borrower.Code;

					const borrowerInfo = document.createElement("div");

					borrowerInfo.innerHTML = `
						<strong>${borrower['First Name'] + " " + borrower['Last Name']}</strong><br>
						Code: ${borrower['Code']}<br>
						Email: ${borrower['Email']}<br>
						Address: ${borrower['Address']}
						<div class="availability ${borrower['Status'] != "Active" ? "unavailable" : "available"}">${borrower['Status'].toUpperCase()}</div>
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
