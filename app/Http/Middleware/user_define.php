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
                    'reason' => [
                        ['required' => ''],
                        102
                    ]

                ],
                'err_code' => [
                    101 => 'id is required|integer!',
                    102 => 'reason is required!'
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
	]
];

