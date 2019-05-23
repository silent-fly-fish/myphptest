<?php
return [
	'USER_DEFINE' => [

        'syzs/ofdoctor/s6/stick' => [
            'GET'=>[
                'params' => [
                    'doctor_id' => [
                        ['required' => '',],
                        101
                    ],
                ],
                'err_code' => [
                    101 => 'doctor_id required!'
                ],
            ],
        ],

        'wechatusers' => [
            'GET' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => ''],
                        101
                    ]
                ],
                'err_code' => [
                    101 => 'doctor_id required!'
                ],
            ]
        ],
	]
];

