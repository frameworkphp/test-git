<div class="padding-md">
    {{ content() }}
    {{ flashSession.output() }}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default table-responsive">
                <div class="panel-heading clearfix">
                    <div class="input-group pull-left">
                        <h3 class="panel-title"><i class="fa fa-user"></i> Manage {{VARIABLE_NAME}}s</h3>
                    </div>
                    <div class="input-group pull-right">
                        <a href="{{url('{{LINK_CONTROLLER}}/add')}}" class="btn btn-sm btn-success"> Add {{MODEL_NAME}}</a>
                    </div>
                </div>
                <div class="panel-body clearfix">
                    {{FILTER_VIEW}}

                    {{SEARCH_VIEW}}
                </div>

                <form method="post" name="appForm">
                    {% if users.total_items > 0 %}
                    <table class="table table-striped" id="responsiveTable">
                        <thead>
                        <tr>
                            <th>
                                <label class="label-checkbox">
                                    <input type="checkbox" id="chk-all">
                                    <span class="custom-checkbox"></span>
                                </label>
                            </th>
                            {{TABLE_HEADER_VIEW}}
                        </tr>
                        </thead>
                        <tbody>
                        {{TABLE_BODY_VIEW}}
                        </tbody>
                    </table>

                    <div class="panel-footer clearfix">
                        <div class="pull-left form-filter">
                                <select name="selectBulkAction" class="input-sm form-control">
                                    <option value="0">Bulk action</option>
                                    <option value="deletes">Delete</option>
                                </select>
                        </div>
                        <a id="bulk-action" class="btn btn-default btn-sm pull-left">Apply</a>
                        <div class="pull-right">
                            {% if pagination.items is defined and pagination.total_pages > 1 %}
                                {% include "layouts/pagination.volt" %}
                            {% endif %}
                        </div>
                    </div>
                    {% else %}
                    <div class="col-md-12">
                        <div class="table-no-record">
                            <p>No {{VARIABLE_NAME}} found.</p>
                            <i class="fa {{CONTROLLER_ICON}}"></i>
                        </div>
                    </div>
                    {% endif %}
                    <input type="hidden" name="boxChecked" value="0" />
                    <input type="hidden" name="sort" value="{{ sort }}" />
                    <input type="hidden" name="dir" value="{{ dir }}" />
                </form>
            </div>
            <!-- /panel -->
        </div>
        <!-- /.col -->
    </div>
</div>
