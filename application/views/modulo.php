<style>
    .grid{ 
        width: 100%;
        height: 580px;
    }  
    .required{
        color: #F00;
        font-size: 11px; 
    }    
    .searchlike{
        width: 300px; color:#000 !important; font-weight: bold;
    }
</style>

<script type="text/ng-template" id="myModalContent.html">  
    <form name="miForm" ng-submit="save();" novalidate>
    <div class="modal-header">
        <h3 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Eliminación</h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                ¿Seguro de eliminar a: <strong>{{modulo.nombre_modulo}}</strong> ?
            </div>
        </div>
    </div>     
    <div class="modal-footer">            
        <button class="btn btn-primary" ng-disabled="miForm.$submitted">
            <span>Si, eliminar</span>  
        </button>    
        <button class="btn btn-warning" type="button" ng-click="cancel()">Cancelar</button>
    </div>
    </form>
</script> 

<script type="text/ng-template" id="modalModulo.html">  
    <form name="miForm" ng-submit="save();" novalidate>
    <div class="modal-header">
        <h3 class="modal-title" ng-if="accion == 'add'"><i class="fa fa-sitemap"></i> Nuevo Módulo</h3>
        <h3 class="modal-title" ng-if="accion == 'edit'"><i class="fa fa-sitemap"></i> Editar Módulo</h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label> Nombre <span class="text-red">(*)</span>:</label>
                    <input type="text" class="form-control"  name="nombre_modulo" ng-model="modulo.nombre_modulo" required />
                    <span class="glyphicon glyphicon-pencil form-control-feedback"></span>                        
                    <div ng-messages="miForm.nombre_modulo.$error" class="required" role="alert" ng-show="miForm.nombre_modulo.$dirty">
                        <div ng-message="required">El campo es requerido</div>
                    </div>
                </div>
            </div>
        </div>
    </div>     
    <div class="modal-footer">            
        <button class="btn btn-primary" ng-disabled="miForm.$invalid || miForm.$submitted">
            <span>aceptar</span>  
        </button>    
        <button class="btn btn-warning" type="button" ng-click="cancel()">Cancelar</button>
    </div>
    </form>
</script> 



<section class="content-header">
    <h1>
        <i class="{{$state.current.data.pageHeader.icono}}"></i> {{$state.current.data.pageHeader.title}}
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
        <li data-ng-repeat="breadcrumb in $state.current.data.breadcrumbs">{{breadcrumb.title}}</li>
    </ol>
</section>


<div ng-show="$state.includes('modulo.list')">
    <div class="content">
        <div class="row">
            <div class="col-md-12">  
                <div class="mb-10" style="margin-bottom:5px">                 
                    <a class="btn btn-sm btn-info"  ng-click='btnToggleFiltering()'>Buscar</a>
                    <div class="pull-right">                
                        <a class="btn btn-sm btn-danger" tooltip-placement="top" uib-tooltip="Eliminar" ng-if="vm.filaSelecciona.length == 1" ng-click="vm.openModal('delete', vm.filaSelecciona[0])"><i class="fa fa-trash-o"></i> </a>
                        <a ng-click="vm.openModal('edit', vm.filaSelecciona[0])" class="btn btn-sm btn-warning" tooltip-placement="top" uib-tooltip="Editar" ng-if="vm.filaSelecciona.length === 1"><i class="fa fa-pencil"></i> </a> 
                        <a ng-click="vm.openModal('add')" class="btn btn-sm btn-primary">Nuevos</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div ui-grid="vm.gridOptions" ui-grid-selection ui-grid-pagination ui-grid-resize-columns ui-grid-auto-resize class="grid fs-mini-grid" style="background: #fff"></div>                
            </div>
        </div> 
    </div>
</div>
<div ui-view></div>