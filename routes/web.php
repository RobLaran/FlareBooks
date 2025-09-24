<?php
use App\Core\Router;
use App\Middleware\AuthMiddleware;

Router::get('/', 'DashboardController@index', [[AuthMiddleware::class, 'check']]);
Router::get('/dashboard', 'DashboardController@index');

// User Routes
Router::get('/auth/login', 'AuthController@loginUser');
Router::post('/auth/login', 'AuthController@loginUser');

// Admin Routes
Router::get('/auth/login/admin', 'AuthController@loginAdminForm');
Router::post('/auth/login/admin', 'AuthController@loginAdmin');

// Book Routes
Router::get('/books', 'BooksController@index');
Router::get('/books/add', 'BooksController@create');
Router::post('/books/add', 'BooksController@add');
Router::get('/books/edit/{id}', 'BooksController@edit');
Router::put('/books/update/{id}', 'BooksController@update');
Router::delete('/books/delete/{id}', 'BooksController@delete');

Router::get('/genres', 'GenresController@index');
Router::get('/borrowers', 'BorrowersController@index');
Router::get('/borrowed-books', 'BorrowedBooksController@index');
Router::get('/returns', 'ReturnsController@index');
Router::get('/overdue-books', 'OverdueBooks@index');
Router::get('/reports', 'ReportsController@index');
