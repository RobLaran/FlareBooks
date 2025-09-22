<?php
use App\Core\Router;

Router::get('/', 'DashboardController@index');
Router::get('/dashboard', 'DashboardController@index');

// Book Routes
Router::get('/books', 'BooksController@index');
Router::get('/books/add', 'BooksController@addBook');

Router::get('/genres', 'GenresController@index');
Router::get('/borrowers', 'BorrowersController@index');
Router::get('/borrowed-books', 'BorrowedBooksController@index');
Router::get('/returns', 'ReturnsController@index');
Router::get('/reservations', 'ReservationsController@index');
Router::get('/reports', 'ReportsController@index');
