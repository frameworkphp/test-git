<div class="padding-md">
    {{ content() }}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <form method="post" enctype="multipart/form-data" class="form-horizontal form-border no-margin" id="basic-constraint" data-validate="parsley"
                      novalidate>
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-plus"></i> Add {{MODEL_NAME}}</h3>
                    </div>
                    <div class="panel-body">
                        {{FORM_ADD_VIEW}}
                    </div>
                    <div class="panel-footer clearfix">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-sm btn-success">Submit</button>
                        </div>
                    </div>
                    <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">
                </form>
            </div>
            <!-- /panel -->
        </div>
    </div>
</div>
