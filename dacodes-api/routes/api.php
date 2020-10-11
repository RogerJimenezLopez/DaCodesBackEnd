<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//**************************** AUTH ROUTES ********************************/
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signUp');

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

//**************************** TEACHER ROUTES ********************************/

//Rutas de cursos
Route::get('/admin/course', 'Admin\CoursesAdminController@index')->middleware('auth:api', 'has.role:teacher')->name('course');
Route::post('/admin/course/create', 'Admin\CoursesAdminController@createCourse')->middleware('auth:api', 'has.role:teacher')->name('course.create');
Route::post('/admin/course/update', 'Admin\CoursesAdminController@updateCourse')->middleware('auth:api', 'has.role:teacher')->name('course.update');
Route::delete('/admin/course/delete', 'Admin\CoursesAdminController@deleteCourse')->middleware('auth:api', 'has.role:teacher')->name('course.delete');
Route::put('/admin/course/active', 'Admin\CoursesAdminController@updateCourseStatusByColum')->middleware('auth:api', 'has.role:teacher')->name('course.active');
Route::post('/admin/course/approve', 'Admin\CoursesAdminController@ApproveUserCourse')->middleware('auth:api', 'has.role:teacher')->name('course.approve');

//Rutas de lecciones
Route::get('/admin/lesson', 'Admin\LessonsAdminController@index')->middleware('auth:api', 'has.role:teacher')->name('lesson');
Route::post('/admin/lesson/create', 'Admin\LessonsAdminController@createLesson')->middleware('auth:api', 'has.role:teacher')->name('lesson.create');
Route::post('/admin/lesson/update', 'Admin\LessonsAdminController@updateLesson')->middleware('auth:api', 'has.role:teacher')->name('lesson.update');
Route::delete('/admin/lesson/delete', 'Admin\LessonsAdminController@deleteLesson')->middleware('auth:api', 'has.role:teacher')->name('lesson.delete');
Route::put('/admin/lesson/active', 'Admin\LessonsAdminController@updateLessonStatusByColum')->middleware('auth:api', 'has.role:teacher')->name('lesson.active');

//Rutas de preguntas
Route::get('/admin/question', 'Admin\QuestionAdminController@index')->middleware('auth:api', 'has.role:teacher')->name('question');
Route::post('/admin/question/create', 'Admin\QuestionAdminController@createQuestion')->middleware('auth:api', 'has.role:teacher')->name('question.create');
Route::post('/admin/question/update', 'Admin\QuestionAdminController@updateQuestion')->middleware('auth:api', 'has.role:teacher')->name('question.update');
Route::delete('/admin/question/delete', 'Admin\QuestionAdminController@deleteQuestion')->middleware('auth:api', 'has.role:teacher')->name('question.delete');
Route::put('/admin/question/active', 'Admin\QuestionAdminController@updateQuestionStatusByColum')->middleware('auth:api', 'has.role:teacher')->name('question.active');

//Rutas de tipos de preguntas
Route::get('/admin/typequestion', 'Admin\TypeQuestionAdminController@index')->middleware('auth:api', 'has.role:teacher')->name('typequestion');
Route::post('/admin/typequestion/create', 'Admin\TypeQuestionAdminController@createTypeQuestion')->middleware('auth:api', 'has.role:teacher')->name('typequestion.create');
Route::post('/admin/typequestion/update', 'Admin\TypeQuestionAdminController@updateTypeQuestion')->middleware('auth:api', 'has.role:teacher')->name('typequestion.update');
Route::delete('/admin/typequestion/delete', 'Admin\TypeQuestionAdminController@deleteTypeQuestion')->middleware('auth:api', 'has.role:teacher')->name('typequestion.delete');
Route::put('/admin/typequestion/active', 'Admin\TypeQuestionAdminController@updateTypeQuestionStatusByColum')->middleware('auth:api', 'has.role:teacher')->name('typequestion.active');
Route::post('/admin/typequestion/approve', 'Admin\TypeQuestionAdminController@ApproveAnswer')->middleware('auth:api', 'has.role:teacher')->name('typequestion.approve');

//Rutas de usuarios
Route::get('/admin/user', 'Admin\UserAdminController@index')->middleware('auth:api', 'has.role:teacher')->name('user');
Route::post('/admin/user/assignUserToCourse', 'Admin\UserAdminController@assignUserToCourse')->middleware('auth:api', 'has.role:teacher')->name('user.assing');
Route::post('/admin/user/unassignUserToCourse', 'Admin\UserAdminController@unassignUserToCourse')->middleware('auth:api', 'has.role:teacher')->name('user.unassign');

//**************************** STUDENT ROUTES ********************************/

Route::get('/user/course', 'User\CoursesUserController@index')->middleware('auth:api')->name('courseSt');
Route::get('/user/lesson', 'User\LessonUserController@index')->middleware('auth:api')->name('lessonSt');
Route::get('/user/question', 'User\QuestionUserController@index')->middleware('auth:api')->name('questionSt');

Route::post('/user/typequestion/answer', 'User\QuestionUserController@createAnswer')->middleware('auth:api')->name('typequestion.answer');


