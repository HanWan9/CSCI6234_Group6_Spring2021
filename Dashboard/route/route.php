<?php
// 主页
Route::get('/', 'admin/Index/index')->middleware('check');
// 切换主题
Route::post('/changeTheme', 'admin/Index/changeTheme')->middleware('check');

// 登录
Route::get('/login', 'admin/Login/login');
Route::get('/verify', 'admin/Login/verify');
Route::post('/login/checkLogin', 'admin/Login/checkLogin');

// 退出登录
Route::get('/outlogin', 'admin/Index/outLogin')->middleware('check');

Route::get('/register', 'admin/Login/register');
Route::post('/register/checkRegister', 'admin/Login/checkRegister');

/* dashboard */
// 绑定患者
Route::get('/bind', 'admin/Patient/index')->middleware('check');
Route::post('/bind/save', 'admin/Patient/saveBindInfo')->middleware('check');

// 记录患者吃药
Route::get('/medicine', 'admin/Medicine/index')->middleware('check');
Route::post('/medicine/save', 'admin/Medicine/saveInfo')->middleware('check');

// 出行记录
Route::get('/trip', 'admin/Trip/trip')->middleware('check');
Route::post('/trip/save', 'admin/Trip/saveInfo')->middleware('check');

// 记录就诊时间
Route::get('/clinic', 'admin/Clinic/clinic')->middleware('check');
Route::post('/clinic/save', 'admin/Clinic/saveInfo')->middleware('check');

// 发布新闻页面
Route::get('/releaseNews', 'admin/Index/releaseNews')->middleware('check');
Route::post('/releaseNews/upload/covers', 'admin/Index/uploadCovers')->middleware('check');
Route::post('/releaseNews/save', 'admin/Index/saveNews')->middleware('check');

// 用餐记录
Route::get('/dining', 'admin/Dining/dining')->middleware('check');
Route::post('/dining/save', 'admin/Dining/saveInfo')->middleware('check');

Route::get('/physicalState', 'admin/PhysicalState/index')->middleware('check');
Route::post('/physicalState/save', 'admin/PhysicalState/saveInfo')->middleware('check');

Route::get('/calculatedRisk', 'admin/CalculatedRisk/index')->middleware('check');
Route::post('/calculatedRisk/save', 'admin/CalculatedRisk/saveInfo')->middleware('check');
Route::post('/calculatedRisk/detail', 'admin/CalculatedRisk/viewDetail')->middleware('check');

// 当前用户修改密码
Route::post('/updateMySelfPassword', 'admin/Index/updateMySelfPassword')->middleware('check');
