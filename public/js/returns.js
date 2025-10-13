// Selection
    const transactions = document.querySelectorAll('.transaction');
    const hiddenTransactionId = document.getElementById('selectedTransaction');

    transactions.forEach(transaction => {
		transaction.addEventListener('click', () => {
			transactions.forEach(b => b.classList.remove('selected'));
			transaction.classList.add('selected');
			hiddenTransactionId.value = transaction.dataset.id;
		});
	});

    // Searchbox
    document.getElementById("borrowedBookListSearchBox").addEventListener("keyup", function () {
		let query = this.value;
		const route = this.dataset.route;

		fetch(`${route}?q=` + encodeURIComponent(query))
			.then(response => response.json())
			.then(transactions => {
				const borrowedBookList = document.getElementById("borrowedBookList");
				borrowedBookList.innerHTML = "";

				if (transactions.length === 0) {
					borrowedBookList.innerHTML = "<h2>No transactions found</h2>";
					return;
				}

				transactions.forEach(transaction => {
					const div = document.createElement("div");
					div.classList.add("transaction");
					div.dataset.id = transaction['id'];

					const imgDiv = document.createElement("div");
					const img = document.createElement("img");
					img.src = transaction['Book Info']['Image'];
					imgDiv.appendChild(img);

					const bookInfo = document.createElement("div");

					bookInfo.innerHTML = `
                            <strong>${transaction['Borrower']}</strong><br>
							Book Title: ${transaction["Book Info"]["Title"]}<br>
							Book Author: ${transaction["Book Info"]["Author"]}<br>
							ISBN: ${transaction["Book Info"]["ISBN"]}
					`;

					if(transaction["Status"] == "Overdue") {
						bookInfo.innerHTML += `<br><div class="overdue">${transaction['Status']}</div>`;
					} 

					div.append(
						imgDiv,
						bookInfo
					);

					div.addEventListener("click", () => {
						document.querySelectorAll('.transaction').forEach(b => b.classList.remove("selected"));
						div.classList.add("selected");
						document.getElementById("selectedTransaction").value = div.dataset.id;
					});

					borrowedBookList.appendChild(div);
				});
			});
	});