"use strict";
(function () {
    angular.module('adminLTEApp.modulo', [])
            .controller('listModuloCtrl', listModuloCtrl)
            .controller('modalModuloInstanceCtrl', modalModuloInstanceCtrl);


    listModuloCtrl.$inject = ['$scope', '$state', 'moduloService', 'GridExternal', 'uiGridConstants', '$uibModal', '$timeout', '$log'];
    function listModuloCtrl($scope, $state, moduloService, GridExternal, uiGridConstants, $uibModal, $timeout, $log) {
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

        function loadModulos() {
            var columnsDefs = [
                {name: 'Id', field: 'idmodulo', width: '60'},
                {name: 'Nombre', field: 'nombre_modulo', maxwidth: '300'},
            ];

            GridExternal.getGrid(vm, columnsDefs, $scope, function () {
                moduloService.GetIndex(vm.filter).then(function (modulos) {
                
                    vm.gridOptions.totalItems = modulos.total;
                    vm.gridOptions.data = modulos.lista;
                    $timeout(function () {
                        if (vm.gridApi.selection.selectRow) {
                            vm.gridApi.selection.selectRow(vm.gridOptions.data[0]);
                        }
                    });
                    GridExternal.setInfoPagina(vm, vm.gridApi.pagination, vm.gridOptions, modulos.lista.length);
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
                controller: 'modalModuloInstanceCtrl',
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
                    loadModulos();
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        };


        loadModulos();

    }


    modalModuloInstanceCtrl.$inject = ['$scope', '$uibModalInstance', 'modalParam', 'moduloService', 'Notification', '$state'];
    function modalModuloInstanceCtrl($scope, $uibModalInstance, modalParam, moduloService, Notification, $state) {
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
                moduloService.Create(vm.OBJmodulo).then(function (modulos) {
                    
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
                moduloService.Delete($scope.modulo.idmodulo).then(function (modulos) {
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