<?php
use App\Core\Router;
use App\Middleware\AuthMiddleware;

// Auth Routes
Router::get('/auth/login', 'AuthController@login');
Router::get('/auth/register', 'AuthController@register');
Router::post('/auth/login', 'AuthController@attemptLogin');
Router::post('/auth/register', 'AuthController@attemptRegister');
Router::get('/auth/logout', 'AuthController@logout');

// ALL Admin ROUTES --------------------------------------------------------------------

// Dashboard
Router::get('/admin', 'Admin\AdminController@dashboard', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::get('/admin', 'Admin\AdminController@dashboard', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::get('/admin/dashboard', 'Admin\AdminController@dashboard', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);

// Books
Router::get('/admin/books', 'Admin\BooksController@index', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::get('/admin/books/add', 'Admin\BooksController@create', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::get('/admin/books/search-books', 'Admin\BooksController@search', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::post('/admin/books/add', 'Admin\BooksController@add', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::get('/admin/books/edit/{id}', 'Admin\BooksController@edit', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::put('/admin/books/update/{id}', 'Admin\BooksController@update', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::delete('/admin/books/delete/{id}', 'Admin\BooksController@delete', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::get('/admin/books/search-books-by-genre', 'Admin\BooksController@searchByGenre', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);

// Genres
Router::get('/admin/genres', 'Admin\GenresController@index', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::post('/admin/genres/add', 'Admin\GenresController@add', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::put('/admin/genres/update/{id}', 'Admin\GenresController@update', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::delete('/admin/genres/delete/{id}', 'Admin\GenresController@delete', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::get('/admin/genres/search-genres', 'Admin\GenresController@search', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);

// Staffs
Router::get('/admin/staffs', 'Admin\AdminController@staffs', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::post('/admin/staffs/add', 'Admin\AdminController@addStaff', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::put('/admin/staffs/update/{id}', 'Admin\AdminController@updateStaff', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::delete('/admin/staffs/delete/{id}', 'Admin\AdminController@deleteStaff', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::get('/admin/staffs/search-staffs', 'Admin\AdminController@searchStaffs', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);

// Reports
Router::get('/admin/reports', 'Admin\ReportsController@index', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::post('/admin/reports/generate', 'Admin\ReportsController@generate', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::get('/admin/reports/stats', 'Admin\ReportsController@stats', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);

// Profile
Router::get('/admin/profile/{id}', 'Admin\AdminController@profile', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::put('/admin/profile/{id}', 'Admin\AdminController@update', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);
Router::put('/admin/profile/change-password/{id}', 'Admin\AdminController@changePassword', [[AuthMiddleware::class, 'checkRole', ['Admin']]]);


// ALL USER ROUTES --------------------------------------------------------------------
Router::get('/', 'UserController@index');
Router::get('/home', 'UserController@index');
Router::get('/features', 'UserController@features');
Router::get('/about', 'UserController@about');
Router::get('/contact', 'UserController@contact');

// Dashboard
Router::get('/dashboard', 'DashboardController@index', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);

// Book Routes
Router::get('/books', 'BooksController@index', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::get('/books/add', 'BooksController@create', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::get('/books/search-books', 'BooksController@search', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::get('/books/search-books-by-genre', 'BooksController@searchByGenre', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::post('/books/add', 'BooksController@add', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::get('/books/edit/{id}', 'BooksController@edit', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::put('/books/update/{id}', 'BooksController@update', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::delete('/books/delete/{id}', 'BooksController@delete', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);

// Genres
Router::get('/genres', 'GenresController@index', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);

// Borrowers
Router::get('/borrowers', 'BorrowersController@index', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::get('/borrowers/add', 'BorrowersController@create', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::get('/borrowers/search-borrowers', 'BorrowersController@search', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::post('/borrowers/add', 'BorrowersController@add', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::get('/borrowers/edit/{id}', 'BorrowersController@edit', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::put('/borrowers/update/{id}', 'BorrowersController@update', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::delete('/borrowers/delete/{id}', 'BorrowersController@delete', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);

// Borrowed Books
Router::get('/borrowed-books', 'BorrowedBooksController@index', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::post('/borrowed-books/add', 'BorrowedBooksController@add', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::get('/borrowed-books/search-borrowed-books', 'BorrowedBooksController@searchBorrowedBooks', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::get('/borrowed-books/search-book', 'BorrowedBooksController@searchBook', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::get('/borrowed-books/search-borrower', 'BorrowedBooksController@searchBorrower', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::get('/borrowed-books/search-books-by-genre', 'BorrowedBooksController@searchByGenre', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::delete('/borrowed-books/delete/{id}', 'BorrowedBooksController@delete', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);

// Returns
Router::get('/returns', 'ReturnsController@index', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::post('/returns/add', 'ReturnsController@add', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::get('/returns/search-returned-books', 'ReturnsController@searchReturnedBooks', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::get('/returns/search-transaction', 'ReturnsController@searchTransaction', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::get('/returns/search-books-by-genre', 'ReturnsController@searchByGenre', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::delete('/returns/delete/{id}', 'ReturnsController@delete', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);

// Reports
Router::get('/reports', 'ReportsController@index', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);

// Profile
Router::get('/profile/{id}', 'UserController@profile', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::put('/profile/{id}', 'UserController@update', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
Router::put('/profile/change-password/{id}', 'UserController@changePassword', [[AuthMiddleware::class, 'checkRole', ['Librarian']]]);
