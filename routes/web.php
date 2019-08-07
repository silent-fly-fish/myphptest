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
//        $router->post('/register', function(\Illuminate\Http\Request $request){
//            $postData = $request->all();
//            $postData = $postData['data'];
//            return App\Http\Patient\PatientCtl::phoneRegister($postData['phone'],$postData['code']);
//        });

        //用户手机号登录/注册/微信账号绑定
        $router->post('/login', function(\Illuminate\Http\Request $request){
            $postData = $request->all();
            $header = $request->header();
            $postData = $postData['data'];
            $unionid = isset($postData['unionid'])? $postData['unionid'] : '';
            $udid = isset($postData['registerid'])? $postData['registerid'] : '';
            $platform = 'android'; //TODO isset($header['platform'][0])? $header['platform'][0] : ''
            return App\Http\Patient\PatientCtl::phoneCodeLogin($postData['phone'],$postData['code'],$unionid,$udid,$platform);
        });

        //退出登录
        $router->post('/logout', function(\Illuminate\Http\Request $request){
            $postData = $request->all();
            $postData = $postData['data'];
            $patientId = $postData['patient_id'];
            return App\Http\Patient\PatientCtl::logout($patientId);
        });

        //发送短信验证码
        $router->group(['prefix'=> 'send'],function () use ($router){
            //发送注册验证码
//            $router->post('/registercode', function(\Illuminate\Http\Request $request){
//                $postData = $request->all();
//                $postData = $postData['data'];
//                return App\Http\Patient\PatientCtl::phoneRegisterCode($postData['phone']);
//            });

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
        $router->get('/test/{patientId}',function ($patientId){

            return \App\Http\Patient\PatientCtl::test($patientId);
        });

        $router->post('/views',function (\Illuminate\Http\Request $request){
            $postData = $request->all();
            $postData = $postData['data'];
            $patientId = $postData['patient_id'];
            $doctorId = $postData['doctor_id'];

            return \App\Http\Patient\PatientCtl::addDoctorView($patientId,$doctorId);
        });

        $router->post('/attentions',function (\Illuminate\Http\Request $request){
            $postData = $request->all();
            $postData = $postData['data'];
            $patientId = $postData['patient_id'];
            $doctorId = $postData['doctor_id'];

            return \App\Http\Patient\PatientCtl::bindDoctorAttention($patientId,$doctorId);
        });

        //医生热度得分脚本
        $router->get('/doctor/hotsjob',function (\Illuminate\Http\Request $request){

            return \App\Http\Patient\DoctorCtl::doctorHot();
        });

        //微信登录授权
        $router->group(['prefix' => 'wechat'],function () use ($router){
            // 微信授权接口
            $router->get('/login',function(\Illuminate\Http\Request $request) {
                $getData = $request->all();
                $header = $request->header();
                $code = $getData['code'];
                $udid = isset($getData['registerid'])? $getData['registerid'] : '';
                $platform = isset($header['platform'][0])? $header['platform'][0] : '';
                return \App\Http\Patient\WechatCtl::getTokenByCode('patient',$code,$udid,$platform);
            });

            //账号绑定
            $router->put('/bind',function(\Illuminate\Http\Request $request) {
                $putData = $request->all();
                $putData = $putData['data'];
                $code = $putData['code'];
                $phone = $putData['phone'];
                $unionid = $putData['unionid'];
                return \App\Http\Patient\WechatCtl::bindPhone($phone,$code,$unionid);
            });

//            //发送绑定验证码
//            $router->post('/send/bindcode',function(\Illuminate\Http\Request $request) {
//                $postData = $request->all();
//                $postData = $postData['data'];
//                $phone = $postData['phone'];
//                return \App\Http\Patient\WechatCtl::sendBindCode($phone);
//            });
        });

        //落地页海外医院
        $router->group(['prefix' => 'overseasHospital'],function () use ($router){

            //海外医院列表
            $router->get('',function(\Illuminate\Http\Request $request) {

                return \App\Http\Patient\TempCtl::getHospitalList();
            });

            //海外医院详情
            $router->get('{id}',function($id) {

                return \App\Http\Patient\TempCtl::getHospitalInfo($id);
            });
        });

        //活动页医生列表
        $router->get('/temp/doctors',function(\Illuminate\Http\Request $request) {

            $ids = [
                1,4,5,6
            ];
            return \App\Http\Patient\TempCtl::getDoctorList($ids);
        });

        //活动页疾病详情
        $router->get('/temp/illness',function(\Illuminate\Http\Request $request) {

            return \App\Http\Patient\TempCtl::getIllnessInfo();
        });

        //用户病历模块
        $router->post('/records',function(\Illuminate\Http\Request $request) {
            $postData = $request->all();
            $postData=  $postData['data'];
            return \App\Http\Patient\RecordCtl::AddOne($postData);
        });
        $router->get('/records',function(\Illuminate\Http\Request $request) {
            $getData = $request->all();
            return \App\Http\Patient\RecordCtl::getList($getData );
        });
        $router->get('/records/completion',function(\Illuminate\Http\Request $request) {
            $getData = $request->all();
            return \App\Http\Patient\RecordCtl::getCompletion($getData );
        });

    });


    //医生端api
    $router->group(['prefix'=> 'doctor'], function() use ($router){
        //用户手机号登录
        $router->post('/login', function(\Illuminate\Http\Request $request){
            $postData = $request->all();
            $postData = $postData['data'];
            $header = $request->header();
            $udid = isset($postData['registerid'])? $postData['registerid'] : '';
            $platform = 'android';//isset($header['platform'][0])? $header['platform'][0] : '';
            return App\Http\Doctor\DoctorCtl::phoneLogin($postData['phone'],$postData['code'],$udid,$platform);
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
                $patientIds = $postData['patient_ids'];
                $tagIds = $postData['tag_ids'];

                return App\Http\Doctor\TagsCtl::assignTag($doctorId,$patientIds,$tagIds);
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

        $router->get('/branchlist',function(\Illuminate\Http\Request $request){
            $postData = $request->all();

            return \App\Http\Admin\SysOptionsCtl::getBranchList($postData);
        });

        $router->get('/positionlist',function(\Illuminate\Http\Request $request){
            $postData = $request->all();

            return \App\Http\Admin\SysOptionsCtl::getPositionList($postData);
        });

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

                return App\Http\Admin\DoctorCtl::getDoctorInfo($doctorId);
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

        $router->group(['prefix'=>'doctorhots'],function () use ($router){
            $router->post('', function(\Illuminate\Http\Request $request){
                $postData = $request->all();
                $postData = $postData['data'];
                $doctorId = $postData['doctor_id'];
                $artificialScore = $postData['artificial_score'];

                return \App\Http\Admin\DoctorCtl::updateDoctorHotScore($doctorId,$artificialScore);
            });
        });

        $router->group(['prefix'=>'patients'],function () use ($router){
            //患者详情
            $router->get('/{patientId}', function($patientId){

                return App\Http\Admin\PatientCtl::getPatientInfo($patientId);
            });

            //患者列表
            $router->get('', function(\Illuminate\Http\Request $request){
                $getData = $request->all();

                return App\Http\Admin\PatientCtl::getPatientList($getData);
            });
            //给患者打标签
            $router->post('', function(\Illuminate\Http\Request $request){
                $putData = $request->all();
                $putData = $putData['data'];

                return App\Http\Admin\PatientCtl::addPatientTag($putData);
            });

            //患修改标签
            $router->put('', function(\Illuminate\Http\Request $request){
                $putData = $request->all();
                $putData = $putData['data'];

                return App\Http\Admin\PatientCtl::updatePatientTag($putData);
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


            $router->delete('', function(\Illuminate\Http\Request $request){
                $postData = $request->all();
                $postData = $postData['data'];

                return App\Http\Admin\DoctorVisitsCtl::delByID($postData);
            });
        });

        $router->group(['prefix'=>'hospitals'],function () use ($router) {

            //添加医院
            $router->post('',function(\Illuminate\Http\Request $request) {
                $postData = $request->all();
                $postData = $postData['data'];

                return \App\Http\Admin\HospitalCtl::addHospital($postData);
            });
            //修改医院信息
            $router->put('',function(\Illuminate\Http\Request $request) {
                $putData = $request->all();
                $putData = $putData['data'];

                return \App\Http\Admin\HospitalCtl::updateHospital($putData);
            });

            //获取医院列表
            $router->get('',function(\Illuminate\Http\Request $request) {
                $getData = $request->all();

                return \App\Http\Admin\HospitalCtl::getHospitalList($getData);
            });

            //获取医院详情
            $router->get('/{hospitalId}',function($hospitalId) {

                return \App\Http\Admin\HospitalCtl::getHospitalInfo($hospitalId);
            });

        });

        $router->group(['prefix'=>'banners'],function () use ($router) {

            //添加首页banner或运营banner
            $router->post('',function(\Illuminate\Http\Request $request) {
                $postData = $request->all();
                $postData = $postData['data'];

                return \App\Http\Admin\OptionsCtl::addOption($postData);
            });
            //删除首页banner和运营banner
            $router->put('',function(\Illuminate\Http\Request $request) {
                $putData = $request->all();
                $putData = $putData['data'];

                return \App\Http\Admin\OptionsCtl::delOption($putData);
            });

            //获取首页banner和运营banner列表
            $router->get('',function(\Illuminate\Http\Request $request) {
                $getData = $request->all();
                $type = $getData['type'];

                return \App\Http\Admin\OptionsCtl::getOptionList($type);
            });

            //置顶操作
            $router->put('/top',function(\Illuminate\Http\Request $request) {
                $putData = $request->all();
                $putData = $putData['data'];
                $type = $putData['type'];
                $id = $putData['id'];
                return \App\Http\Admin\OptionsCtl::top($type,$id);
            });

        });

        $router->group(['prefix'=>'doctorteam'],function () use ($router) {

            //医生团队分配
            $router->post('',function(\Illuminate\Http\Request $request) {
                $postData = $request->all();
                $postData = $postData['data'];
                $doctorId = $postData['doctor_id'];
                $doctorTeamIds = $postData['doctor_team_ids'];

                return \App\Http\Admin\DoctorTeamCtl::assignDoctorTeam($doctorId,$doctorTeamIds);
            });

            //医生团队列表
            $router->get('',function(\Illuminate\Http\Request $request) {
                $getData = $request->all();


                return \App\Http\Admin\DoctorTeamCtl::getDoctorTeamList($getData);
            });

            //医生团队分配列表
            $router->get('/team',function(\Illuminate\Http\Request $request) {
                $getData = $request->all();
                $doctorId = $getData['doctor_id'];

                return \App\Http\Admin\DoctorTeamCtl::getTeamList($doctorId);
            });

        });

        $router->group(['prefix'=>'doctorapply'],function () use ($router) {

            //审核入驻医生
            $router->put('',function(\Illuminate\Http\Request $request) {
                $putData = $request->all();
                $putData = $putData['data'];
                $id = $putData['id'];
                $applyStatus = $putData['apply_status'];
                $desc = $putData['desc'];

                return \App\Http\Admin\DoctorCtl::checkDoctor($id,$applyStatus,$desc);
            });

            //申请入驻列表
            $router->get('',function(\Illuminate\Http\Request $request) {
                $getData = $request->all();


                return \App\Http\Admin\DoctorCtl::applyDoctorList($getData);
            });

        });

        $router->group(['prefix'=>'referees'],function () use ($router) {

            //添加销售员
            $router->post('',function(\Illuminate\Http\Request $request) {
                $postData = $request->all();
                $postData = $postData['data'];

                return \App\Http\Admin\RefereesCtl::addOne($postData);
            });

            //销售人员列表
            $router->get('',function(\Illuminate\Http\Request $request) {
                $getData = $request->all();


                return \App\Http\Admin\RefereesCtl::getRefereeList($getData);
            });

            //修改或删除销售人员
            $router->put('',function(\Illuminate\Http\Request $request) {
                $putData = $request->all();
                $putData = $putData['data'];

                return \App\Http\Admin\RefereesCtl::update($putData);
            });


        });

        $router->group(['prefix'=>'accounts'],function () use ($router) {
            //添加账号
            $router->post('',function(\Illuminate\Http\Request $request) {
                $postData = $request->all();
                $postData = $postData['data'];

                return \App\Http\Admin\AccountCtl::addOne($postData);
            });

            $router->put('',function(\Illuminate\Http\Request $request) {
                $postData = $request->all();
                $postData = $postData['data'];

                return \App\Http\Admin\AccountCtl::updateOne($postData);
            });
        });

        $router->post('login',function(\Illuminate\Http\Request $request) {
            $postData = $request->all();
            $postData = $postData['data'];

            return \App\Http\Admin\AccountCtl::login($postData);
        });

        $router->group(['prefix'=>'admin'],function () use ($router) {
            //添加账号
            $router->post('',function(\Illuminate\Http\Request $request) {
                $postData = $request->all();
                $postData = $postData['data'];

                return \App\Http\Admin\AdminCtl::addAdmin($postData);
            });

            //登录操作
            $router->post('/login',function(\Illuminate\Http\Request $request) {
                $postData = $request->all();
                $postData = $postData['data'];
                $username = $postData['username'];
                $password = $postData['password'];
                $ip = $request->getClientIp();
                return \App\Http\Admin\AdminCtl::adminLogin($username,$password,$ip);
            });
        });

        $router->group(['prefix'=>'suggests'],function () use ($router) {
            $router->get('',function(\Illuminate\Http\Request $request) {
                $getData = $request->all();

                return \App\Http\Admin\PatientSuggestCtl::getPatientSuggestList($getData);
            });

            $router->get('/{suggest_id}', function ($suggest_id){


                return \App\Http\Admin\PatientSuggestCtl::getPatientSuggestInfo($suggest_id);
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

            //批量更新医生信息（跑好评率脚本）
            $router->put('/update/batch', function(\Illuminate\Http\Request $request){
                $putData = $request->all();
                $putData = $putData['data'];

                return \App\Http\Open\DoctorCtl::updateBatchDoctor($putData);
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
//            $router->put('/decrease/intergral', function(\Illuminate\Http\Request $request){
//                $putData = $request->all();
//                $putData = $putData['data'];
//
//                return App\Http\Open\PatientCtl::decrease($putData);
//            });
        });
    });
});



