<?php
use App\Core\Router;

Router::get('/', 'DashboardController@index');
Router::get('/dashboard', 'DashboardController@index');

// User Auth Routes
Router::get('/auth/login', 'UserController@index');

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
Router::get('/reservations', 'ReservationsController@index');
Router::get('/reports', 'ReportsController@index');
