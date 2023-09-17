<script>
    $( document ).ready(function() {
        var data_get_map = {
            'frm_GeneralSettings': '/api/i2pd/settings/get',
            'frm_httpWebconsoleSettings': '/api/i2pd/settings/get',
            'frm_httpProxySettings': '/api/i2pd/settings/get'
        };
        mapDataToFormUI(data_get_map).done(function(data){
            // place actions to run after load, for example update form styles.
        });

        // link save button to API set action
        [
            {'selector': '#generalSaveAct', 'endpoint': '/api/i2pd/settings/set', 'formid': 'frm_GeneralSettings'},
            {'selector': '#webconsoleSaveAct', 'endpoint': '/api/i2pd/settings/set', 'formid': 'frm_httpWebconsoleSettings'},
            {'selector': '#proxySaveAct', 'endpoint': '/api/i2pd/settings/set', 'formid': 'frm_httpProxySettings'}
        ].forEach(function (cfg) {
            $(cfg.selector).click(function(){
                saveFormToEndpoint(url=cfg.endpoint, formid=cfg.formid, callback_ok=function(){
                    // action to run after successful save, for example reconfigure service.
                    $(".saveAct_progress").addClass("fa fa-spinner fa-pulse");
                    ajaxCall(url="/api/i2pd/service/reload", sendData={},callback=function(data,status) {
                        // action to run after reload
                        $(".saveAct_progress").removeClass("fa fa-spinner fa-pulse");
                        //$("#responseMsg").html(data['message']).removeClass("hidden");
                    });
                });
            });
        });
    });
</script>

<!--
<div class="alert alert-info hidden" role="alert" id="responseMsg">
</div>
-->

<ul class="nav nav-tabs" data-tabs="tabs" id="maintabs">
    <li class="active"><a data-toggle="tab" href="#general">{{ lang._('General') }}</a></li>
    <li><a data-toggle="tab" href="#httpWebconsole">{{ lang._('HTTP Webconsole') }}</a></li>
    <li><a data-toggle="tab" href="#httpProxy">{{ lang._('HTTP proxy') }}</a></li>
</ul>

<div class="tab-content content-box tab-content" style="padding-bottom: 1.5em;">
    <div id="general" class="tab-pane fade in active">
        {{ partial("layout_partials/base_form",['fields':generalForm,'id':'frm_GeneralSettings'])}}
        <div class="col-md-12">
            <hr />
            <button class="btn btn-primary" id="generalSaveAct" type="button"><b>{{ lang._('Save') }}</b> <i class="saveAct_progress"></i></button>
        </div>
    </div>
    <div id="httpWebconsole" class="tab-pane fade in">
        {{ partial("layout_partials/base_form",['fields':httpWebconsoleForm,'id':'frm_httpWebconsoleSettings'])}}
        <div class="col-md-12">
            <hr />
            <button class="btn btn-primary" id="webconsoleSaveAct" type="button"><b>{{ lang._('Save') }}</b> <i class="saveAct_progress"></i></button>
        </div>
    </div>
    <div id="httpProxy" class="tab-pane fade in">
        {{ partial("layout_partials/base_form",['fields':httpProxyForm,'id':'frm_httpProxySettings'])}}
         <div class="col-md-12">
            <hr />
            <button class="btn btn-primary" id="proxySaveAct" type="button"><b>{{ lang._('Save') }}</b> <i class="saveAct_progress"></i></button>
        </div>
    </div>
</div>
