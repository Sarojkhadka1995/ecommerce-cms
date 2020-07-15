<?php

return  [
  // routes entered in this array are accessible by any user no matter what role is given
  'permissionGrantedbyDefaultRoutes' => [
    [
      "url" => '/home',
      "method" => 'get'
    ],
    [
      "url" => '/logout',
      "method" => 'get'
    ],
    [
      "url" => '/languages/set-language/*',
      "method" => 'get'
    ],
    [
      "url" => '/miscellaneous/scrap',
      "method" => 'get'
    ]

  ],

  // All the routes are accessible by super user by default
  // routes entered in this array are not accessible by super user
  "permissionDeniedToSuperUserRoutes" => [],

  'modules' => [
    [
      'name' => 'Dashboard',
      'icon' => "<i class='fa fa-home'></i>",
      'hasSubmodules' => false,
      'route' => '/home'
    ],
    [
      "name" => 'User Management',
      "icon" => "<i class='fa fa-user'></i>",
      "hasSubmodules" => true,
      "submodules" => [
        [
          "name" => 'Users',
          "icon" => "<i class='fa fa-users'></i>",
          "hasSubmodules" => false,
          "route" => '/users',
          "permissions" => [
            [
              "name" => 'View Users',
              "route" => [
                "url" => '/users',
                "method" => 'get'
              ]
            ],
            [
              "name" => 'Create Users',
              "route" => [
                [
                  "url" => '/users/create',
                  "method" => 'get'
                ],
                [
                  "url" => '/users',
                  "method" =>  'post'
                ],
              ]
            ],
            [
              "name" => 'Edit Users',
              "route" => [
                [
                  "url" =>  '/users/*/edit',
                  "method" => 'get'
                ],
                [
                  "url" => '/users/*',
                  "method" => 'put'
                ],
              ]
            ],
            [
              "name" => 'Delete Users',
              "route" => [
                "url" => '/users/*',
                "method" => 'delete'
              ],
            ]
          ]
        ],
        [
          "name" => 'Roles',
          "icon" => "<i class='fa fa-tags'></i>",
          "hasSubmodules" => false,
          "route" => '/roles',
          "permissions" => [
            [
              "name" => 'View Roles',
              "route" => [
                "url" => '/roles',
                "method" => 'get'
              ]
            ],
            [
              "name" => 'Create Roles',
              "route" => [
                [
                  "url" => '/roles/create',
                  "method" => 'get'
                ],
                [
                  "url" => '/roles',
                  "method" =>  'post'
                ],
              ]
            ],
            [
              "name" => 'Edit Roles',
              "route" => [
                [
                  "url" =>  '/roles/*/edit',
                  "method" => 'get'
                ],
                [
                  "url" => '/roles/*',
                  "method" => 'put'
                ],
              ]
            ],
            [
              "name" => 'Delete Roles',
              "route" => [
                "url" => '/roles/*',
                "method" => 'delete'
              ],
            ]
          ]
        ],
      ]
    ],
    [
        "name" => 'Language Management',
        "hasSubmodules" => true,
        "icon" => "<i class='fa fa-language' aria-hidden='true'></i>",
        "submodules" => [
          [
            "name" => 'Languages',
            "icon" => "<i class='fa fa-language' aria-hidden='true'></i>",
            "hasSubmodules" => false,
            "route" => '/languages',
            "permissions" => [
              [
                "name" => 'View Languages',
                "route" => [
                  "url" => '/languages',
                  "method" => 'get'
                ]
              ],
              [
                "name" => 'Create Languages',
                "route" => [
                  [
                    "url" => '/languages/create',
                    "method" => 'get'
                  ],
                  [
                    "url" => '/languages',
                    "method" => 'post'
                  ],
                ]
              ],
              [
                "name" => 'Delete Languages',
                "route" =>  [
                  "url" => '/languages/*',
                  "method" => 'delete'
                ],
              ]
            ]
          ],

        ]
      ],
    [
      "name" => 'System configs',
      "icon" => "<i class='fa fa-cogs' aria-hidden='true'></i>",
      "hasSubmodules" => true,
      "submodules" => [
        [
          "name" => 'Email Templates',
          "icon" => "<i class='fa fa-envelope' aria-hidden='true'></i>",
          "route" => '/email-templates',
          "hasSubmodules" => false,
          "permissions" => [
            [
              "name" => 'View Email Templates',
              "route" => [
                "url" => '/email-templates',
                "method" => 'get'
              ]
            ],
            [
              "name" => 'Create Email Templates',
              "route" => [
                [
                  "url" => '/email-templates/create',
                  "method" => 'get'
                ],
                [
                  "url" => '/email-templates',
                  "method" => 'post'
                ]
              ]
            ],
            [
              "name" => 'Edit Email Templates',
              "route" => [
                [
                  "url" => '/email-templates/*/edit',
                  "method" => 'get'
                ],
                [
                  "url" => '/email-templates/*',
                  "method" => 'put'
                ]
              ]
            ],
            [
              "name" => 'Delete Email Templates',
              "route" => [
                "url" => '/email-templates/*',
                "method" => 'delete'
              ]
            ],
          ]
        ],
        [
          "name" => 'Configs',
          "icon" => '<i class="fa fa-cog" aria-hidden="true"></i>',
          "route" => '/configs',
          "hasSubmodules" => false,
          "permissions" => [
            [
              "name" => 'View Configs',
              "route" => [
                "url" => '/configs',
                "method" => 'get'
              ]
            ],
            [
              "name" => 'Create Config',
              "route" => [
                "url" => '/configs',
                "method" => 'post'
              ]
            ],
            [
              "name" => 'Edit Config',
              "route" => [
                "url" => '/configs/*',
                "method" => 'put'
              ]
            ],
            [
              "name" => 'Delete Config',
              "route" => [
                "url" => '/configs/*',
                "method" => 'delete'
              ]
            ]
          ]
        ]
      ]
    ],
  ]
];
