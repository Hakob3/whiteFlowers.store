<?php

use Illuminate\Support\Facades\Route;


Route::get('/', 'MainController@index');
Route::get('/flower/{uri}', 'CartController@oneFlower');
Route::get('/personalOrder', 'CartController@personalOrder');
Route::get('/personal/{personalID}', 'CartController@personalOrder');
Route::get('/cart/{type}', 'CartController@index');
Route::get('/cart/', 'CartController@index');
Route::post('/addCartItem', 'CartController@addCartItem');
Route::post('/cartItemsCount', 'CartController@cartItemsCount');
Route::post('/cartItemCancel', 'CartController@cartItemCancel');
Route::post('/createFromSingleCart', 'CartController@createOrderSingleCart');
Route::post('/createFromMultipleCart', 'CartController@createOrderMultipleCart');
Route::post('/createOrder', 'CartController@createOrder');
Route::get('/successOrder/{id}', 'CartController@successOrder');
Route::get('/successOrderMulti/{id}', 'CartController@successOrderMulti');
Route::get('/successOrder/', 'CartController@successOrder');
Route::get('/contact', 'ContactController@index');
Route::post('/contact/store', 'MessageController@store');
Route::get('/catalog', 'CatalogController@index');
Route::get('/payment-map', 'PaymentController@paymentMap');
Route::post('/orderPay', 'PaymentController@orderPay');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/sendMailOrderConfirmed', 'CartController@sendTestMail');
Route::get('/sendMailOrderConfirmed2', 'CartController@sendTestMail2');
//Route::get('/sendMailOrderConfirmed3', 'CartController@sendTestMail3');
Route::get('/admin/messages/', 'MessageController@index');
Route::get('/admin/order/{orderId}', 'OrdersController@view');
Route::get('/admin/flowers', 'FlowersController@index');
Route::post('/editFlower', 'FlowersController@edit');
Route::post('/addFlower', 'FlowersController@add');
Route::post('/editOrder', 'OrdersController@edit');
Route::post('/editCourier', 'OrdersController@editCourier');
Route::post('/editRubrics', 'CollectionsController@editRubrics');
Route::get('/admin/flowers/{id}', 'FlowersController@view');
Route::get('/admin/order-to-ms/{orderId}', 'OrdersController@toMs');
Route::get('/admin/collections', 'CollectionsController@index')->name('index');
Route::get('/admin/add_rubric', 'CollectionsController@add_rubric');
Route::get('/admin/collection/{id}', 'CollectionsController@collectionEdit');
Route::get('/admin/collectionEdit/{id}', 'CollectionsController@collectionUpdate');
Route::get('/admin/orders', 'OrdersController@index');
Route::get('/admin/couriers', 'OrdersController@couriers');
Route::get('/admin/messages/{message}', 'MessageController@show')->name('admin.contact.show');
Route::post('/admin/messages/{message}', 'MessageController@destroy')->name('admin.contact.destroy');
