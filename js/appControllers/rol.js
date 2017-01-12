"use strict";
(function () {
    angular.module('adminLTEApp.rol', [])
            .controller('listRolCtrl', listRolCtrl)
            .controller('modalRolInstanceCtrl', modalRolInstanceCtrl);


    listRolCtrl.$inject = ['$scope', '$state', 'rolService', 'GridExternal', 'uiGridConstants', '$uibModal', '$timeout', '$log'];
    function listRolCtrl($scope, $state, rolService, GridExternal, uiGridConstants, $uibModal, $timeout, $log) {
        var vm = this;
        vm.filter = {};
        vm.stateparent = $state.$current.parent.self.name;
        $scope.$on('handleBroadcast', function () {
            vm.getPage();
        });


        $scope.btnToggleFiltering = function(){
            vm.gridOptions.enableFiltering = !vm.gridOptions.enableFiltering;
            vm.gridApi.core.notifyDataChange( uiGridConstants.dataChange.COLUMN );            
        };

        function loadRoles() {
            var columnsDefs = [
                {name: 'Id', field: 'idrol', width: '60'},
                {name: 'Icono', field: 'idrol', width: '80'},
                {name: 'Rol', field: 'nombre_rol', width: '150'},
                {name: 'Sub Rol', field: 'idrol', width: '130'},
                {name: 'URL', field: 'idrol', width: '130'},
                {name: 'Modulo', field: 'idrol', maxwidth: '120'},
            ];

            GridExternal.getGrid(vm, columnsDefs, $scope, function () {
                rolService.GetIndex(vm.filter).then(function (roles) {
                
                    vm.gridOptions.totalItems = roles.total;
                    vm.gridOptions.data = roles.lista;
                    $timeout(function () {
                        if (vm.gridApi.selection.selectRow) {
                            vm.gridApi.selection.selectRow(vm.gridOptions.data[0]);
                        }
                    });
                    GridExternal.setInfoPagina(vm, vm.gridApi.pagination, vm.gridOptions, roles.lista.length);
                });
            });
        }

        vm.openModal = function (accion, data) {
            var sizeModal = 'sm';
            if(accion == 'add' || accion == 'edit'){ sizeModal = 'sm';}
            var modalM = 'myModalContent.html';
            if(accion == 'add' || accion == 'edit'){ modalM = 'modalModulo.html';}

            var modalInstance = $uibModal.open({
                templateUrl: modalM,
                controller: 'modalRolInstanceCtrl',
                windowClass: 'modal-default',
                size: sizeModal,
                resolve: {
                    modalParam: function () {
                        return {
                            accion: accion,
                            data: data
                        };
                    }
                }
            });

            modalInstance.result.then(function (reload) {
                if (reload)
                    loadRoles();
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        };


        loadRoles();

    }


    modalRolInstanceCtrl.$inject = ['$scope', '$uibModalInstance', 'modalParam', 'rolService', 'Notification', '$state'];
    function modalRolInstanceCtrl($scope, $uibModalInstance, modalParam, rolService, Notification, $state) {
        var vm = this;
        $scope.accion = modalParam.accion;
        $scope.modulo = {};
        var reload = false;

        if(modalParam.data != null){
            $scope.modulo = {
                idmodulo: modalParam.data.idmodulo,
                nombre_modulo: modalParam.data.nombre_modulo
            };
        }
        

        $scope.save = function () {
            vm.OBJmodulo = {
                modulo: $scope.modulo,

            };

            if(modalParam.accion == 'add' || modalParam.accion == 'edit'){
                rolService.Create(vm.OBJmodulo).then(function (modulos) {
                    
                    if (modulos.type === '1') {
                        reload = true;
                        Notification.primary({message: modulos.mensaje, title: '<i class="fa fa-check"></i>'});
                        $uibModalInstance.close(reload);
                    } else {
                        Notification.error({message: modulos.mensaje, title: '<i class="fa fa-ban"></i>'});
                        $scope.miForm.$submitted = false;
                    }
                    
                });
            }else{
                rolService.Delete($scope.modulo.idmodulo).then(function (modulos) {
                    var reload = false;
                    if (modulos.type === '1') {
                        //reload = true;
                        Notification.primary({message: modulos.mensaje, title: '<i class="fa fa-check"></i>'});
                        $state.reload();
                        $uibModalInstance.dismiss('cancel');
                    } else {
                        Notification.error({message: modulos.mensaje, title: '<i class="fa fa-ban"></i>'});
                        $scope.miForm.$submitted = false;
                    }
                    
                });
            }

            
        };

        $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };
    }


})();