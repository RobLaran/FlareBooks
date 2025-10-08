<?php
use App\Core\Router;
use App\Middleware\AuthMiddleware;

// User Routes
Router::get('/profile/{id}', 'UserController@profile');
Router::get('/auth/login', 'AuthController@loginUser');
Router::post('/auth/login', 'AuthController@loginUser');

// Admin Routes
Router::get('/auth/login/admin', 'AuthController@loginAdmin');
Router::post('/auth/login/admin', 'AuthController@loginAdmin');

// Logout route
Router::get('/auth/logout', 'AuthController@logout');


// Dashboard
Router::get('/', 'DashboardController@index', [[AuthMiddleware::class, 'check']]);
Router::get('/dashboard', 'DashboardController@index', [[AuthMiddleware::class, 'check']]);

// Book Routes
Router::get('/books', 'BooksController@index', [[AuthMiddleware::class, 'check']]);
Router::get('/books/add', 'BooksController@create', [[AuthMiddleware::class, 'check']]);
Router::post('/books/add', 'BooksController@add', [[AuthMiddleware::class, 'check']]);
Router::get('/books/edit/{id}', 'BooksController@edit', [[AuthMiddleware::class, 'check']]);
Router::put('/books/update/{id}', 'BooksController@update', [[AuthMiddleware::class, 'check']]);
Router::delete('/books/delete/{id}', 'BooksController@delete', [[AuthMiddleware::class, 'check']]);

// Genres
Router::get('/genres', 'GenresController@index', [[AuthMiddleware::class, 'check']]);

// Borrowers
Router::get('/borrowers', 'BorrowersController@index', [[AuthMiddleware::class, 'check']]);
Router::get('/borrowers/add', 'BorrowersController@create', [[AuthMiddleware::class, 'check']]);
Router::post('/borrowers/add', 'BorrowersController@add', [[AuthMiddleware::class, 'check']]);
Router::get('/borrowers/edit/{id}', 'BorrowersController@edit', [[AuthMiddleware::class, 'check']]);
Router::put('/borrowers/update/{id}', 'BorrowersController@update', [[AuthMiddleware::class, 'check']]);
Router::delete('/borrowers/delete/{id}', 'BorrowersController@delete', [[AuthMiddleware::class, 'check']]);

// Borrowed Books
Router::get('/borrowed-books', 'BorrowedBooksController@index', [[AuthMiddleware::class, 'check']]);
Router::post('/borrowed-books/add', 'BorrowedBooksController@add', [[AuthMiddleware::class, 'check']]);
Router::get('/borrowed-books/search-book', 'BorrowedBooksController@searchBook', [[AuthMiddleware::class, 'check']]);
Router::get('/borrowed-books/search-borrower', 'BorrowedBooksController@searchBorrower', [[AuthMiddleware::class, 'check']]);
Router::delete('/borrowed-books/delete/{id}', 'BorrowedBooksController@delete', [[AuthMiddleware::class, 'check']]);

// Returns
Router::get('/returns', 'ReturnsController@index', [[AuthMiddleware::class, 'check']]);
Router::post('/returns/add', 'ReturnsController@add', [[AuthMiddleware::class, 'check']]);
Router::get('/returns/search-returned-books', 'ReturnsController@searchReturnedBooks', [[AuthMiddleware::class, 'check']]);
Router::get('/returns/search-transaction', 'ReturnsController@searchTransaction', [[AuthMiddleware::class, 'check']]);

// Reports
Router::get('/reports', 'ReportsController@index', [[AuthMiddleware::class, 'check']]);

