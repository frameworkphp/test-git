<div class="padding-md">
    {{ content() }}
    {{ flashSession.output() }}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default table-responsive">
                <div class="panel-heading clearfix">
                    <div class="input-group pull-left">
                        <h3 class="panel-title"><i class="fa fa-user"></i> Manage {{CONTROLLER_NAME}}s</h3>
                    </div>
                    <div class="input-group pull-right">
                        <a href="{{url('{{REDIRECT_INDEX}}/add')}}" class="btn btn-sm btn-success"> Add {{CONTROLLER_NAME}}</a>
                    </div>
                </div>
                <div class="panel-body clearfix">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">Filter {{CONTROLLER_NAME}} <span class="caret"></span></button>
                            <div class="dropdown-menu dropdown-filter-box " >
                                <h3>Show all {{CONTROLLER_NAME}} where:</h3>
                                <div class="form-group inline">
                                    <select id="filter" class="input-sm custom-form">
                                        <option value="">Select a filter..</option>
{{FILTER_OPTION}}
                                    </select>
                                </div>
                                {{FILTER_VIEW}}
                            </div>
                        </div>

                        <input type="text" name="q" id="search" class="form-control input-sm" value="{{ parameter['keyword'] }}" placeholder="Search {{CONTROLLER_NAME}} by {{PLACEHOLDER_SEARCH}}..." aria-controls="dataTable">
                                <span class="input-group-btn">
                                    <button id="btnSearch" class="btn btn-default btn-sm" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>

                    </div>
                    {% if count(filterTag) %}
                    <div class="filter-tags">
                        <ul class="col-md-12 active-filters horizontal">
                            {% for tag in filterTag %}
                            <li class="tag">
                                    <span>
                                        <em>{{tag['name']}} is equal to <b>{{tag['value']}}</b></em>
                                        <span class="close closeFilter" data-type="{{tag['key']}}"><i class="fa fa-times"></i></span>
                                    </span>
                            </li>
                            {% endfor %}
                        </ul>
                    </div>
                    {% endif %}
                </div>

                <form method="post" name="appForm">
                    {% if {{VARIABLE_NAME}}s.total_items > 0 %}
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
                        <td>
                            <label class="label-checkbox">
                                <input type="checkbox" class="chk-row" name="cid[]" value="{{ {{VARIABLE_NAME}}.id }}">
                                <span class="custom-checkbox"></span>
                            </label>
                        </td>
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
                            <p>No {{CONTROLLER_NAME}} found.</p>
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
