<?php
return [
	'USER_DEFINE' => [



        'patient/doctors' => [
            'GET' => [
                'params' => [
                    'page' => [
                        ['default' => 1,],
                        101
                    ],
                    'size' => [
                        ['default' => 20],
                        102
                    ]

                ],
                'err_code' => [
                ],
            ]
        ],
        'patient/options' => [
            'GET' => [
                'params' => [
                    'type' => [
                        ['required' => '',],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'type is required!'
                ],
            ]
        ],

        'patient/visits' => [
            'GET' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => '',],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required!'
                ],
            ]
        ],

        'patient/teamdoctor' => [
            'GET' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => '',],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required!'
                ],
            ]
        ],
        'open/doctors' => [
            'GET' => [
                'params' => [
                    'doctor_ids' => [
                        ['required' => '',],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_ids is required!'
                ],
            ],
            'PUT' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => '','integer'=>''],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required and integer!'
                ],
            ],
        ],
        'open/doctorsbase' => [
            'GET' => [
                'params' => [
                    'doctor_ids' => [
                        ['required' => '',],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_ids is required!'
                ],
            ]
        ],

        'open/patients' => [
            'GET' => [
                'params' => [
                    'patient_ids' => [
                        ['required' => '',],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'patient_ids is required!'
                ],
            ],
            'PUT' => [
                'params' => [
                    'patient_id' => [
                        ['required' => '','integer' => ''],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'patient_id is required and integer!'
                ],
            ]
        ],

        'open/patientdoctor' => [
            'GET' => [
                'params' => [
                    'patient_id' => [
                        ['required' => '',],
                        101
                    ],
                    'doctor_id' => [
                        ['required' => '',],
                        102
                    ]
                ],
                'err_code' => [
                    101 => 'patient_id is required!',
                    102 => 'doctor_id is required!'
                ],
            ]
        ],
        'open/patientdoctors' => [
            'GET' => [
                'params' => [
                    'patient_ids' => [
                        ['required' => '',],
                        101
                    ],
                    'doctor_ids' => [
                        ['required' => '',],
                        102
                    ]
                ],
                'err_code' => [
                    101 => 'patient_ids is required!',
                    102 => 'doctor_ids is required!'
                ],
            ]
        ],
        'patient/patients' => [
            'PUT' => [
                'params' => [
                    'patient_id' => [
                        ['required' => '', 'integer'=>''],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'patient_id is required and integer!'
                ],
            ]
        ],

        'patient/register' => [
            'POST' => [
                'params' => [
                    'phone' => [
                        ['required' => '','Regx'=>'/^1[3456789]\d{9}$/'],
                        101
                    ],
                    'code' => [
                        ['required' => ''],
                        102
                    ]

                ],
                'err_code' => [
                    101 => 'phone is required and phoneNumber!',
                    102 => 'code is required!'
                ],
            ]
        ],

        'patient/login' => [
            'POST' => [
                'params' => [
                    'phone' => [
                        ['required' => '','Regx'=>'/^1[3456789]\d{9}$/'],
                        101
                    ],
                    'code' => [
                        ['required' => ''],
                        102
                    ]

                ],
                'err_code' => [
                    101 => 'phone is required and phoneNumber!',
                    102 => 'code is required!'
                ],
            ]
        ],

        'patient/historys' => [
            'POST' => [
                'params' => [
                    'patient_id' => [
                        ['required' => '', 'integer'=> ''],
                        101
                    ],
                    'search' => [
                        ['required' => ''],
                        102
                    ],
                    'type' => [
                        ['required' => '', 'integer'=> ''],
                        103
                    ]

                ],
                'err_code' => [
                    101 => 'patient_id is required and integer!',
                    102 => 'search is required!',
                    103 => 'type is required and integer'
                ],
            ]
        ],

        'patient/suggests' => [
            'POST' => [
                'params' => [
                    'patient_id' => [
                        ['required' => '', 'integer'=> ''],
                        101
                    ],
                    'reason' => [
                        ['required' => ''],
                        102
                    ]

                ],
                'err_code' => [
                    101 => 'patient_id is required and integer!',
                    102 => 'reason is required!'
                ],
            ]
        ],

        'patient/invitation' => [
            'PUT' => [
                'params' => [
                    'patient_id' => [
                        ['required' => '', 'integer'=> ''],
                        101
                    ],
                    'invite_code' => [
                        ['required' => ''],
                        102
                    ]

                ],
                'err_code' => [
                    101 => 'patient_id is required and integer!',
                    102 => 'invite_code is required!'
                ],
            ]
        ],
        'patient/send/logincode' => [
            'POST' => [
                'params' => [
                    'phone' => [
                        ['required' => '','Regx'=>'/^1[3456789]\d{9}$/'],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'phone is required and phoneNumber!'
                ],
            ]
        ],
        'patient/send/registercode' => [
            'POST' => [
                'params' => [
                    'phone' => [
                        ['required' => '','Regx'=>'/^1[3456789]\d{9}$/'],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'phone is required and phoneNumber!'
                ],
            ]
        ],

        'doctor/send/logincode' => [
            'POST' => [
                'params' => [
                    'phone' => [
                        ['required' => '','Regx'=>'/^1[3456789]\d{9}$/'],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'phone is required and phoneNumber!'
                ],
            ]
        ],

        'doctor/login' => [
            'POST' => [
                'params' => [
                    'phone' => [
                        ['required' => '','Regx'=>'/^1[3456789]\d{9}$/'],
                        101
                    ],
                    'code' => [
                        ['required' => ''],
                        102
                    ]

                ],
                'err_code' => [
                    101 => 'phone is required and phoneNumber!',
                    102 => 'code is required!'
                ],
            ]
        ],
        'patient/accusations' => [
            'POST' => [
                'params' => [
                    'patient_id' => [
                        ['required' => '', 'integer'=> ''],
                        101
                    ],
                    'type' => [
                        ['required' => '', 'integer'=> '','in'=>[1,2,3,4,5]],
                        102
                    ],
                    'accusation_id' => [
                        ['required' => '', 'integer'=> ''],
                        103
                    ],
                    'content' => [
                        ['required' => ''],
                        104
                    ]

                ],
                'err_code' => [
                    101 => 'patient_id is required|integer!',
                    102 => 'type is required|nteger|0<=type<=5',
                    103 => 'accusation_id is required|integer!',
                    104 => 'content is required!'
                ],
            ]
        ],

        'admin/accusations' => [
            'PUT' => [
                'params' => [
                    'id' => [
                        ['required' => '', 'integer'=> ''],
                        101
                    ],
                    'r_status' => [
                        ['required' => '', 'integer'=> ''],
                        103
                    ],

                ],
                'err_code' => [
                    101 => 'id is required|integer!',
                    102 => 'r_status is required|integer!',
                ],
            ],
            'GET' => [
                'params' => [
                    'type' => [
                        ['required' => ''],
                        101
                    ],
                    'status' => [
                        ['default' => 1,'in'=>[1,2]],
                        102
                    ]
                ],
                'err_code' => [
                    101 => 'type is required|integer!',
                    102 => 'status required!|integer|1<=status<=2'
                ],
            ]
        ],

        'doctor/application' => [
            'POST' => [
                'params' => [
                    'phone' => [
                        ['required' => '','Regx'=>'/^1[3456789]\d{9}$/'],
                        101
                    ],
                    'real_name' => [
                        ['required' => ''],
                        102
                    ],
                    'hospital_name' => [
                        ['required' => ''],
                        103
                    ],
                    'code' => [
                        ['required' => ''],
                        104
                    ]

                ],
                'err_code' => [
                    101 => 'phone required and phoneNumber!',
                    102 => 'real_name is required!',
                    103 => 'hospital_name is required!',
                    104 => 'code is required!',
                ],
            ]
        ],

        'doctor/send/applycode' => [
            'POST' => [
                'params' => [
                    'phone' => [
                        ['required' => '','Regx'=>'/^1[3456789]\d{9}$/'],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'phone is required and phoneNumber!'
                ],
            ]
        ],

        'doctor/visits' => [
            'GET' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => ''],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required!'
                ],
            ]
        ],

        'doctor/doctors' => [
            'PUT' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => '','integer' => ''],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required and integer!'
                ],
            ]
        ],

        'doctor/patients' => [
            'GET' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => ''],
                        101
                    ],
                    'page' => [
                        ['default' => 1],
                        102
                    ],
                    'size' => [
                        ['default' => 20],
                        103
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required!'
                ],
            ]
        ],

        'doctor/tags' => [
            'GET' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => ''],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required!'
                ],
            ],
            'POST' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => '','integer' => ''],
                        101
                    ],
                    'tag_name' => [
                        ['required' => ''],
                        102
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required and integer!',
                    102 => 'tag_name is required!',
                ],
            ],
            'PUT' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => '','integer' => ''],
                        101
                    ],
                    'tag_id' => [
                        ['required' => '','integer' => ''],
                        102
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required and integer!',
                    102 => 'tag_id is required and integer!',
                ],
            ]
        ],

        'doctor/patienttags' => [
            'POST' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => '','integer' => ''],
                        101
                    ],
                    'patient_id' => [
                        ['required' => '','integer' => ''],
                        102
                    ],
                    'tag_ids' => [
                        ['default' => ''],
                        103
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required and integer!',
                    102 => 'patient_id is required and integer!',
                ],
            ],
            'PUT' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => '','integer' => ''],
                        101
                    ],
                    'patient_ids' => [
                        ['default' => []],
                        102
                    ],
                    'tag_id' => [
                        ['required' => '','integer' => ''],
                        103
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required and integer!',
                    103 => 'tag_id is required and integer!',
                ],
            ],
            'GET' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => ''],
                        101
                    ],
                    'patient_id' => [
                        ['required' => ''],
                        102
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required!',
                    102 => 'patient_id is required!',
                ],
            ]
        ],

        'admin/doctors' => [
            'GET' => [
                'params' => [
                    'page' => [
                        ['default' => 1],
                        101
                    ],
                    'size' => [
                        ['default' => 20],
                        102
                    ]

                ],
                'err_code' => [

                ],
            ],
            'POST' => [
                'params' => [
                    'real_name' => [
                        ['required' => ''],
                        101
                    ],
                    'name' => [
                        ['required' => '','Regx'=>'/^1[3456789]\d{9}$/'],
                        102
                    ],
                    'password' => [
                        ['required' => ''],
                        103
                    ],
                    'telephone' => [
                        ['required' => '','Regx'=>'/^1[3456789]\d{9}$/'],
                        104
                    ],
                    'hospital_id' => [
                        ['required' => '','integer' => ''],
                        105
                    ],
                    'branch_id' => [
                        ['required' => '','integer' => ''],
                        106
                    ],
                    'position_id' => [
                        ['required' => '','integer' => ''],
                        107
                    ],
                    'category_ids' => [
                        ['default' => []],
                        108
                    ],
                    'img' => [
                        ['required' => ''],
                        109
                    ],
                    'good_at' => [
                        ['required' => ''],
                        110
                    ],
                    'description' => [
                        ['required' => ''],
                        111
                    ]

                ],
                'err_code' => [
                    101 => 'real_name is required!',
                    102 => 'name is required and phoneNumber!',
                    103 => 'password is required!',
                    104 => 'telephone is required and phoneNumber!',
                    105 => 'hospital_id is required and integer!',
                    106 => 'branch_id is required and integer!',
                    107 => 'position_id is required and integer!',
                    108 => 'category_ids is required!',
                    109 => 'img is required!',
                    110 => 'good_at is required!',
                    111 => 'description is required!',
                ],
            ],

            'PUT' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => '','integer' => ''],
                        101
                    ],
                    'name' => [
                        ['Regx'=>'/^1[3456789]\d{9}$/'],
                        102
                    ],
                    'telephone' => [
                        ['Regx'=>'/^1[3456789]\d{9}$/'],
                        1043
                    ],
                    'hospital_id' => [
                        ['integer' => ''],
                        104
                    ],
                    'branch_id' => [
                        ['integer' => ''],
                        105
                    ],
                    'position_id' => [
                        ['integer' => ''],
                        106
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required and integer!',
                    102 => 'name is phoneNumber!',
                    103 => 'telephone is phoneNumber!',
                    104 => 'hospital_id is integer!',
                    105 => 'branch_id is integer!',
                    106 => 'position_id is  integer!'
                ],
            ],
        ],

        'admin/visits' => [
            'GET' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => ''],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required!'
                ],
            ],

        ],

        'patient/views' => [
            'POST' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => '','integer' => ''],
                        101
                    ],
                    'patient_id' => [
                        ['required' => '','integer' => ''],
                        102
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required and integer!',
                    102 => 'patient_id is required and integer!'
                ],
            ],

        ],

        'admin/hospitals' => [
            'POST' => [
                'params' => [
                    'name' => [
                        ['required' => ''],
                        101
                    ],
                    'level' => [
                        ['required' => ''],
                        102
                    ],
                    'province_code' => [
                        ['required' => ''],
                        103
                    ],
                    'city_code' => [
                        ['required' => ''],
                        104
                    ],
                    'area_code' => [
                        ['required' => ''],
                        105
                    ],
                    'logo' => [
                        ['required' => ''],
                        106
                    ],
                    'address' => [
                        ['required' => ''],
                        107
                    ],
                    'description' => [
                        ['required' => ''],
                        108
                    ],
                    'r_status' => [
                        ['required' => '','integer' => ''],
                        109
                    ],
                    'public_hospital' => [
                        ['required' => '','integer' => ''],
                        110
                    ],

                ],
                'err_code' => [
                    101 => 'name is required!',
                    102 => 'level is required!',
                    103 => 'province_code is required!',
                    104 => 'city_code is required!',
                    105 => 'area_code is required!',
                    106 => 'logo is required!',
                    107 => 'address is required!',
                    108 => 'description is required!',
                    109 => 'r_status is required and integer!',
                    110 => 'public_hospital is required and integer!'
                ],
            ],

            'PUT' => [
                'params' => [
                    'id' => [
                        ['required' => '','integer' => ''],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'id is required and integer!'
                ],
            ],

            'GET' => [
                'params' => [
                    'page' => [
                        ['default' => 1],
                        101
                    ],
                    'size' => [
                        ['default' => 20],
                        102
                    ]

                ],
                'err_code' => [

                ],
            ],

        ],

        'admin/banners' => [
            'POST' => [
                'params' => [
                    'type' => [
                        ['required' => ''],
                        101
                    ],
                    'name' => [
                        ['required' => ''],
                        102
                    ],
                    'value' => [
                        ['required' => ''],
                        103
                    ]

                ],
                'err_code' => [
                    101 => 'type is required!',
                    102 => 'name is required!',
                    103 => 'value is required!'
                ],
            ],

            'PUT' => [
                'params' => [
                    'id' => [
                        ['required' => '','integer' => ''],
                        101
                    ]

                ],
                'err_code' => [
                    101 => 'id is required and integer!'
                ],
            ],

            'GET' => [
                'params' => [
                    'type' => [
                        ['required' => ''],
                        101
                    ],


                ],
                'err_code' => [
                    101 => 'type is required!'
                ],
            ],

        ],

        'admin/banners/top' => [
            'PUT' => [
                'params' => [
                    'id' => [
                        ['required' => '','integer' => ''],
                        101
                    ],
                    'type' => [
                        ['required' => ''],
                        102
                    ]

                ],
                'err_code' => [
                    101 => 'id is required and integer!',
                    102 => 'type is required!'
                ],
            ],

        ],
        'admin/doctorteam' => [
            'GET' => [
                'params' => [
                    'page' => [
                        ['default' => 1],
                        101
                    ],
                    'size' => [
                        ['default' => 20],
                        102
                    ]

                ],
                'err_code' => [

                ],
            ],
            'POST' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => '','integer' => ''],
                        101
                    ],
                    'doctor_team_ids' => [
                        ['default' => []],
                        102
                    ]

                ],
                'err_code' => [
                    101 => 'doctor_id is required and integer!',
                ],
            ],

        ],

        'admin/doctorteam/team' => [
            'GET' => [
                'params' => [
                    'doctor_id' => [
                        ['required' => ''],
                        101
                    ],

                ],
                'err_code' => [
                    101 => 'doctor_id is required!',
                ],
            ],

        ],

        'admin/doctorapply' => [
            'GET' => [
                'params' => [
                    'page' => [
                        ['default' => 1],
                        101
                    ],
                    'size' => [
                        ['default' => 20],
                        102
                    ],

                ],
                'err_code' => [

                ],
            ],

            'PUT' => [
                'params' => [
                    'id' => [
                        ['required' => '','integer'=>''],
                        101
                    ],
                    'apply_status' => [
                        ['required' => '','integer'=>'','in'=>[1,2,3]],
                        102
                    ],
                    'desc' => [
                        ['default' => ''],
                        103
                    ],

                ],
                'err_code' => [
                    101 => 'id is required and integer!',
                    102 => 'apply_status is required and integer and in!',
                ],
            ],

        ],
	]
];

