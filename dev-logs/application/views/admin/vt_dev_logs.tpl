[{include file="vt_dev_header.tpl"}]

[{if $error}]
    <h1>[{$error|var_dump}]</h1>
[{/if}]

<div class="container" id="devutils-logs" layout="column" layout-fill ng-controller="logsCtrl">
    <div flex>
        [{$exLog|var_dump}]
        [{*
        <md-content>
        <pre>
            
        </pre>
        </md-content>
        *}]
    </div>
    <div flex>
        [{*
        <md-list>
                <md-item ng-repeat="item in webserverlog">
                    <md-item-content>
                        <!--
                        <div class="md-tile-left">
                            <img ng-src="{{item.face}}" class="face" alt="{{item.who}}">
                        </div>
                        -->
                        <div class="md-tile-content">
                            <h3>{{item.header}}</h3>
                            <h4 ng-bind-html-unsafe="item.text"></h4>
                            <p>{{item.date}} {{item.client}}</p>
                        </div>
                    </md-item-content>
                </md-item>
            </md-list>
*}]
lalala
    </div>
</div>


[{capture assign="ctrl"}]
    var app = angular.module('devutils', ['ngMaterial']);
    app.controller("logsCtrl", function($scope, $http)
    {
    $http.get("[{ $oViewConf->getSelfLink()|oxaddparams:"cl=vtdev_logs&fnc=getWebserverErrorLog"|replace:"&amp;":"&" }]")
    .then(function(res) {
    $scope.webserverlog = res.data;
    console.log(res);
    });
    });
[{/capture}]
[{oxscript add=$ctrl}]


[{include file="vt_dev_footer.tpl"}]