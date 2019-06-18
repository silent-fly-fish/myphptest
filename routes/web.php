<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->group(['middleware' => ['auth','before','after']], function () use ($router) {

    //患者端api
    $router->group(['prefix'=> 'patient'], function() use ($router){

        //医生详情
        $router->get('/doctors/{doctorId}', function($doctorId){

            return App\Http\Patient\DoctorCtl::getDoctorInfo($doctorId);
        });

        //医生列表
        $router->get('/doctors', function(\Illuminate\Http\Request $request){
            $getData = $request->all();

            return App\Http\Patient\DoctorCtl::getDoctorList($getData);
        });

        //type获取配置列表
        $router->get('/options', function(\Illuminate\Http\Request $request){
            $getData = $request->all();
            $type = $getData['type'];
            return App\Http\Patient\OptionsCtl::getOptionsByType($type);
        });

        //医生出诊信息
        $router->get('/visits', function(\Illuminate\Http\Request $request){
            $getData = $request->all();
            $doctorId = $getData['doctor_id'];
            return App\Http\Patient\DoctorCtl::getDoctorVisitList($doctorId);
        });

        //医生团队
        $router->get('/teamdoctor', function(\Illuminate\Http\Request $request){
            $getData = $request->all();
            $doctorId = $getData['doctor_id'];
            return App\Http\Patient\DoctorCtl::getDoctorTeam($doctorId);
        });

        //患者详情
        $router->get('/patients/{patientId}', function($patientId){

            return App\Http\Patient\PatientCtl::getPatientInfo($patientId);
        });

        //修改患者信息
        $router->put('/patients', function(\Illuminate\Http\Request $request){
            $putData = $request->all();
            $putData = $putData['data'];
            return App\Http\Patient\PatientCtl::updatePatientInfo($putData);
        });

        //用户手机号注册
        $router->post('/register', function(\Illuminate\Http\Request $request){
            $postData = $request->all();
            $postData = $postData['data'];
            return App\Http\Patient\PatientCtl::phoneRegister($postData['phone'],$postData['code']);
        });

        //用户手机号登录
        $router->post('/login', function(\Illuminate\Http\Request $request){
            $postData = $request->all();
            $postData = $postData['data'];
            return App\Http\Patient\PatientCtl::phoneCodeLogin($postData['phone'],$postData['code']);
        });

        //发送短信验证码
        $router->group(['prefix'=> 'send'],function () use ($router){
            //发送注册验证码
            $router->post('/registercode', function(\Illuminate\Http\Request $request){
                $postData = $request->all();
                $postData = $postData['data'];
                return App\Http\Patient\PatientCtl::phoneRegisterCode($postData['phone']);
            });

            //发送登录验证码
            $router->post('/logincode', function(\Illuminate\Http\Request $request){
                $postData = $request->all();
                $postData = $postData['data'];
                return App\Http\Patient\PatientCtl::phoneLoginCode($postData['phone']);
            });
        });

        //添加历史记录
        $router->post('/historys', function(\Illuminate\Http\Request $request){
            $postData = $request->all();
            $postData = $postData['data'];
            return App\Http\Patient\PatientCtl::addHistory($postData);
        });

        //添加意见反馈
        $router->post('/suggests', function(\Illuminate\Http\Request $request){
            $postData = $request->all();
            $postData = $postData['data'];
            return App\Http\Patient\PatientCtl::addSuggest($postData);
        });

        //地区三级列表
        $router->get('/area',function (\Illuminate\Http\Request $request){

            return \App\Http\Patient\AreaCtl::getAreaList();
        });

        //输入邀请码
        $router->put('/invitation',function (\Illuminate\Http\Request $request){
            $putData = $request->all();
            $putData = $putData['data'];
            $patientId = $putData['patient_id'];
            $inviteCode = trim($putData['invite_code']);

            return \App\Http\Patient\PatientCtl::addInvitation($patientId,$inviteCode);
        });

        //用户举报
        $router->post('/accusations', function(\Illuminate\Http\Request $request){
            $postData = $request->all();
            $postData = $postData['data'];

            return App\Http\Patient\PatientCtl::addAccusation($postData);
        });
        //测试邀请码
//        $router->get('/test/{patientId}',function ($patientId){
//
//            return \App\Http\Patient\PatientCtl::test($patientId);
//        });

    });


    //医生端api
    $router->group(['prefix'=> 'doctor'], function() use ($router){
        //用户手机号登录
        $router->post('/login', function(\Illuminate\Http\Request $request){
            $postData = $request->all();
            $postData = $postData['data'];
            return App\Http\Doctor\DoctorCtl::phoneLogin($postData['phone'],$postData['code']);
        });

        $router->group(['prefix'=> 'send'], function() use ($router){
            //发送登录验证码
            $router->post('/logincode', function(\Illuminate\Http\Request $request){
                $postData = $request->all();
                $postData = $postData['data'];
                return App\Http\Doctor\DoctorCtl::phoneLoginCode($postData['phone']);
            });

            //发送申请入驻验证码
            $router->post('/applycode', function(\Illuminate\Http\Request $request){
                $postData = $request->all();
                $postData = $postData['data'];
                return App\Http\Doctor\DoctorCtl::sendApplyCode($postData['phone']);
            });
        });


        //申请入驻
        $router->post('/application', function(\Illuminate\Http\Request $request){
            $postData = $request->all();
            $postData = $postData['data'];

            return App\Http\Doctor\DoctorCtl::apply($postData);
        });

        $router->group(['prefix'=> 'doctors'], function() use ($router){

            //医生详情
            $router->get('/{doctorId}', function($doctorId){


                return App\Http\Doctor\DoctorCtl::getDoctorInfo($doctorId);
            });

            $router->put('', function(\Illuminate\Http\Request $request){
                $putData = $request->all();
                $putData = $putData['data'];

                return App\Http\Doctor\DoctorCtl::updateDoctor($putData);
            });

            $router->get('/base/{doctorId}', function($doctorId){


                return App\Http\Doctor\DoctorCtl::getDoctorBase($doctorId);
            });

        });


        //医生出诊列表
        $router->get('/visits', function(\Illuminate\Http\Request $request){
            $getData = $request->all();


            return App\Http\Doctor\DoctorCtl::getDoctorVisitList($getData['doctor_id']);
        });

        //患者列表
        $router->get('/patients', function(\Illuminate\Http\Request $request){
            $getData = $request->all();
            $doctorId = $getData['doctor_id'];
            $tagId = isset($getData['tag_id'])? $getData['tag_id'] : 0;
            $page = $getData['page'];
            $size = $getData['size'];

            return App\Http\Doctor\PatientCtl::getPatientList($doctorId,$page,$size,$tagId);
        });

        $router->group(['prefix'=> 'tags'], function() use ($router){
            //医生标签列表
            $router->get('', function(\Illuminate\Http\Request $request){
                $getData = $request->all();
                $doctorId = $getData['doctor_id'];

                return App\Http\Doctor\TagsCtl::getTagsList($doctorId);
            });

            //医生新增标签分组
            $router->post('', function(\Illuminate\Http\Request $request){
                $postData = $request->all();
                $postData = $postData['data'];
                $doctorId = $postData['doctor_id'];
                $tagName = $postData['tag_name'];

                return App\Http\Doctor\TagsCtl::addTag($doctorId,$tagName);
            });

            //删除标签分组
            $router->put('', function(\Illuminate\Http\Request $request){
                $putData = $request->all();
                $putData = $putData['data'];
                $doctorId = $putData['doctor_id'];
                $tagId = $putData['tag_id'];

                return App\Http\Doctor\TagsCtl::deleteTag($doctorId,$tagId);
            });
        });

        $router->group(['prefix'=> 'patienttags'], function() use ($router){

            //为患者打标签
            $router->post('', function(\Illuminate\Http\Request $request){
                $postData = $request->all();
                $postData = $postData['data'];
                $doctorId = $postData['doctor_id'];
                $patientId = $postData['patient_id'];
                $tagIds = $postData['tag_ids'];

                return App\Http\Doctor\TagsCtl::assignTag($doctorId,$patientId,$tagIds);
            });

            //删除患者标签
            $router->put('', function(\Illuminate\Http\Request $request){
                $putData = $request->all();
                $putData = $putData['data'];
                $doctorId = $putData['doctor_id'];
                $patientIds = $putData['patient_ids'];
                $tagId = $putData['tag_id'];

                return App\Http\Doctor\TagsCtl::delPatientTags($doctorId,$patientIds,$tagId);
            });

            //患者标签列表
            $router->get('', function(\Illuminate\Http\Request $request){
                $getData = $request->all();
                $doctorId = $getData['doctor_id'];
                $patientId = $getData['patient_id'];

                return App\Http\Doctor\TagsCtl::getPatientTags($doctorId,$patientId);
            });

        });

        //全部的总关注量
        $router->get('/attention/{doctorId}', function($doctorId){

            return App\Http\Doctor\TagsCtl::getAllTotal($doctorId);
        });


    });

    //运营后台api
    $router->group(['prefix'=> 'admin'], function() use ($router){

        //举报
        $router->group(['prefix'=>'accusations'], function() use($router){
            /*
             * 举报操作
             */
            $router->put('',function(\Illuminate\Http\Request $request){
                $postData = $request->all();
                $postData = $postData['data'];
                return \App\Http\Admin\PatientAccusationCtl::updateByID($postData);
            });

            /*
             * 获取举报列表
             */
            $router->get('',function(\Illuminate\Http\Request $request){
                $postData = $request->all();

                return \App\Http\Admin\PatientAccusationCtl::getAll($postData);
            });

            //举报详情
            $router->get('/{id}',function($id){

                return \App\Http\Admin\PatientAccusationCtl::getInfo($id);
            });

        });
        //类目列表
        $router->get('/typelist',function(\Illuminate\Http\Request $request){
            $postData = $request->all();

            return \App\Http\Admin\PatientAccusationCtl::getTypeAll($postData);
        });

        $router->group(['prefix'=>'doctors'],function () use ($router){
            //医生详情
            $router->get('/{doctorId}', function($doctorId){

                return App\Http\Open\DoctorCtl::getDoctorInfo($doctorId);
            });

            //医生列表
            $router->get('', function(\Illuminate\Http\Request $request){
                $getData = $request->all();

                return App\Http\Admin\DoctorCtl::getDoctorList($getData);
            });

            //添加医生
            $router->post('', function(\Illuminate\Http\Request $request){
                $postData = $request->all();
                $postData = $postData['data'];

                return App\Http\Admin\DoctorCtl::addDoctor($postData);
            });

            //修改医生信息
            $router->put('', function(\Illuminate\Http\Request $request){
                $putData = $request->all();
                $putData = $putData['data'];

                return App\Http\Admin\DoctorCtl::updateDoctor($putData);
            });
        });

        $router->group(['prefix'=>'visits'],function () use ($router){
            //医生出诊信息
            $router->get('', function(\Illuminate\Http\Request $request){
                $getData = $request->all();
                $doctorId = $getData['doctor_id'];
                return App\Http\Admin\DoctorVisitsCtl::getDoctorVisitList($doctorId);
            });

            //添加或修改出诊信息
            $router->post('', function(\Illuminate\Http\Request $request){
                $postData = $request->all();
                $postData = $postData['data'];

                return App\Http\Admin\DoctorVisitsCtl::updateOrAddByData($postData);
            });
        });
    });

    //子系统调用api
    $router->group(['prefix'=> 'open'], function() use ($router){

        $router->group(['prefix'=> 'doctors'], function() use ($router){
            //医生详情
            $router->get('/{doctorId}', function($doctorId){

                return App\Http\Open\DoctorCtl::getDoctorInfo($doctorId);
            });

            //医生基本信息
            $router->get('/base/{doctorId}', function($doctorId){

                return App\Http\Open\DoctorCtl::getOneDoctor($doctorId);
            });

            //医生列表
            $router->get('', function(\Illuminate\Http\Request $request){
                $getData = $request->all();
                $doctorIds = $getData['doctor_ids'];
                return App\Http\Open\DoctorCtl::getDoctorList($doctorIds);
            });

            $router->put('', function(\Illuminate\Http\Request $request){
                $putData = $request->all();
                $putData = $putData['data'];

                return App\Http\Open\DoctorCtl::updateDoctor($putData);
            });
        });


        //医生列表基本信息
        $router->get('/doctorsbase', function(\Illuminate\Http\Request $request){
            $getData = $request->all();
            $doctorIds = $getData['doctor_ids'];
            return App\Http\Open\DoctorCtl::getDoctorListBase($doctorIds);
        });

        //获取患者医生信息
        $router->get('/patientdoctor', function(\Illuminate\Http\Request $request){
            $getData = $request->all();

            return App\Http\Open\PatientCtl::getPatientDoctorInfo($getData);
        });

        //批量获取患者医生信息
        $router->get('/patientdoctors', function(\Illuminate\Http\Request $request){
            $getData = $request->all();

            return App\Http\Open\PatientCtl::getPatientDoctorInfos($getData);
        });

        $router->group(['prefix'=> 'patients'], function() use ($router){
            //患者列表
            $router->get('', function(\Illuminate\Http\Request $request){
                $getData = $request->all();
                $patientIds = $getData['patient_ids'];
                return App\Http\Open\PatientCtl::getPatientList($patientIds);
            });

            //患者详情
            $router->get('/{patientId}', function($patientId){

                return App\Http\Open\PatientCtl::getPatientInfo($patientId);
            });

            //患者详情
            $router->put('', function(\Illuminate\Http\Request $request){
                $putData = $request->all();
                $putData = $putData['data'];

                return App\Http\Open\PatientCtl::updatePatientInfo($putData);
            });

            //减积分操作
            $router->put('/decrease/intergral', function(\Illuminate\Http\Request $request){
                $putData = $request->all();
                $putData = $putData['data'];

                return App\Http\Open\PatientCtl::decrease($putData);
            });
        });
    });
});



