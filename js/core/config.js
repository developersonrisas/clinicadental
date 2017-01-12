'use strict';
angular.module('adminLTEConfig', ['ui-notification'])
        .factory('settings', ['$rootScope', function ($rootScope) {
		    // Cuando subas a un servidor publico, poner ejemplo: http://www.lagranescuela.com 		
            var baseURL = '/clinicadental', 
                settings = {
                    baseURL: baseURL,
                    pluginPath: baseURL + '/bower_components',
                    globalImagePath: baseURL + '/assets/dist/img',
                    adminImagePath: baseURL + '/assets/admin/img',
                    cssPath: baseURL + '/assets/dist/css',
                    dataPath: baseURL + '/data',
                    dirViews : baseURL + 'application/views/'
                };
                
            $rootScope.settings = settings;
            return settings;
        }])

        .config(function (NotificationProvider) {
            NotificationProvider.setOptions({
                delay: 10000,
                startTop: 20,
                startRight: 10,
                verticalSpacing: 20,
                horizontalSpacing: 20,
                positionX: 'center',
                positionY: 'top'
            });
        })

        .config(['$ocLazyLoadProvider', function ($ocLazyLoadProvider) {
                $ocLazyLoadProvider.config({
                    events: false,
                    debug: false,
                    cache: false,
                    cssFilesInsertBefore: 'ng_load_plugins_before',
                    modules: [
                        {
                            name: 'blankonApp.core.demo',
                            files: ['js/modules/core/demo.js']
                        }
                    ]
                });
            }])
        

        .config(function ($stateProvider, $urlRouterProvider, $httpProvider) {
            $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';

            $urlRouterProvider.otherwise('/dashboard');

            $stateProvider
                .state('dashboard',{
                    url: '/dashboard',
                    templateUrl: './application/views/dashboard.php'
                })
                .state('dashboard2',{
                    url: '/dashboard2',
                    templateUrl:'views/dashboard2.html'
                })

                .state('signin', {
                    url: '/sign-in',
                    templateUrl: 'views/sign/sign-in.html',
                    data: {
                        pageTitle: 'SIGN IN',
                        isPublic: true
                    },
                    controller: 'SigninCtrl',
                    controllerAs: 'vm',
                    resolve: {
                        deps: ['$ocLazyLoad', 'settings', function ($ocLazyLoad, settings) {
                                var cssPath = settings.cssPath,
                                        pluginPath = settings.pluginPath;

                                return $ocLazyLoad.load(
                                        [
                                            {
                                                insertBefore: '#load_css_before',
                                                files: [
                                                    cssPath + '/pages/sign.css'
                                                ]
                                            },
                                            {
                                                name: 'blankonApp.account.signin',
                                                files: [
                                                    'js/modules/sign/signin.js',
                                                    'app-services/enterprise.service.js'
                                                ]
                                            }
                                        ]
                                        );
                            }]
                        
                    }
                })

                // =========================================================================
                // MÓDULOS
                // =========================================================================
                .state('modulo', {
                    url: '/modulo',
                    abstract: true,
                    templateUrl: './application/views/modulo.php',
                    data: {
                        pageTitle: 'Modulos',
                        pageHeader: {
                            icono: 'fa fa-sitemap',
                            title: 'Gestión de Modulos',
                            subtitle: ''
                        },
                        breadcrumbs: [
                            {title: 'Seguridad'}, {title: 'Modulos'}
                        ]
                    },
                    controller: 'listModuloCtrl',
                    controllerAs: 'vm',
                    resolve: {
                        deps: ['$ocLazyLoad', 'settings', function ($ocLazyLoad, settings) {
                                return $ocLazyLoad.load(
                                        [
                                            {
                                                name: 'adminLTEApp.modulo',
                                                files: [
                                                    'js/appControllers/modulo.js',
                                                    'appServices/modulo.service.js'
                                                    
                                                    
                                                ]
                                            }
                                        ]
                                        );
                            }]
                    }
                })
                .state('modulo.list', {url: ''})

                // =========================================================================
                // ROLES
                // =========================================================================
                .state('rol', {
                    url: '/rol',
                    abstract: true,
                    templateUrl: './application/views/rol.php',
                    data: {
                        pageTitle: 'Roles',
                        pageHeader: {
                            icono: 'fa fa-cog',
                            title: 'Gestión de Roles',
                            subtitle: ''
                        },
                        breadcrumbs: [
                            {title: 'Seguridad'}, {title: 'Roles'}
                        ]
                    },
                    controller: 'listRolCtrl',
                    controllerAs: 'vm',
                    resolve: {
                        deps: ['$ocLazyLoad', 'settings', function ($ocLazyLoad, settings) {
                                return $ocLazyLoad.load(
                                        [
                                            {
                                                name: 'adminLTEApp.rol',
                                                files: [
                                                    'js/appControllers/rol.js',
                                                    'appServices/rol.service.js'
                                                    
                                                    
                                                ]
                                            }
                                        ]
                                        );
                            }]
                    }
                })
                .state('rol.list', {url: ''})
                
                // =========================================================================
                // GRUPOS
                // =========================================================================
                .state('grupo', {
                    url: '/grupo',
                    abstract: true,
                    templateUrl: './application/views/grupo.php',
                    data: {
                        pageTitle: 'Grupos',
                        pageHeader: {
                            icono: 'fa fa-users',
                            title: 'Gestión de Grupos',
                            subtitle: ''
                        },
                        breadcrumbs: [
                            {title: 'Seguridad'}, {title: 'Grupos'}
                        ]
                    },
                    controller: 'listGrupoCtrl',
                    controllerAs: 'vm',
                    resolve: {
                        deps: ['$ocLazyLoad', 'settings', function ($ocLazyLoad, settings) {
                                return $ocLazyLoad.load(
                                        [
                                            {
                                                name: 'adminLTEApp.grupo',
                                                files: [
                                                    'js/appControllers/grupo.js',
                                                    'appServices/grupo.service.js'
                                                    
                                                    
                                                ]
                                            }
                                        ]
                                        );
                            }]
                    }
                })
                .state('grupo.list', {url: ''})


                .state('about', {
                    url: '/about',
                    template: '<div>Bienvenido al sistema.</div>'
                })

        })

    .run(["$rootScope", "settings", "$state", "$location", "$stateParams",  function ($rootScope, settings, $state, $location, $stateParams) {

       var urlpage = $location.path().split('/')[1];

       $rootScope.$state = $state;
       $rootScope.$stateParams = $stateParams;
       $rootScope.settings = settings;

       $rootScope.urlente = 'multident';
      
       //$rootScope.urlente = $location.host().split('.')[0];
       $rootScope.servidor = '/clinicadental/apilumen/public/' + $rootScope.urlente;
       $rootScope.api = '/clinicadental/ci.php';
       //$rootScope.api = '/clinicadental/ci.php';

      //$rootScope.servidor = 'http://lumenionic.pe/' + $rootScope.urlente;
      //$rootScope.api = 'http://lumenionic.pe';

      
      //$rootScope.viewLogin = true;
    }]);

