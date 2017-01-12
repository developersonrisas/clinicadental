(function () {
    'use strict';
    
    angular
            .module('adminLTE')
            .factory('moduloService', moduloService);

    moduloService.$inject = ['$http', '$rootScope'];     
    function moduloService($http, $rootScope) {
        var service = {}; 
        
        service.GetIndex = GetIndex;  
        service.Create = Create;
        service.Delete = Delete;           
        return service; 
        
        // GRID
        function GetIndex(request) {  
            return $http.get($rootScope.api + '/modulo', {params: request}).then(handleSuccess, handleError('Error getting all modulos'));             
        }

            
        // GRABAR O EDITAR
        function Create(OBJmodulo) {  
            return $http.post($rootScope.api + '/modulo/accion', OBJmodulo).then(handleSuccess, handleError('Error creating entidad'));
        }

        // ELIMINAR
        function Delete(id, request) {
            return $http.post($rootScope.api + '/modulo/eliminar/' + id).then(handleSuccess, handleError('Error deleting user'));
        }

        // private functions
        function handleSuccess(res) { 
            return res.data;
        }

        function handleError(error) {
            return function () {
                return {success: false, message: error};
            };
        }
    }
})();
