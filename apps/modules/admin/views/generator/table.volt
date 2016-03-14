<div class="padding-md">
    {{ content() }}
    {{ flash.output() }}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default table-responsive">
                <div class="panel-heading clearfix">
                    <div class="input-group pull-left">
                        <h3 class="panel-title"><i class="fa fa-table"></i> Table {{ table }}</h3>
                    </div>
                    <div class="input-group pull-right">
                        <a href="{{ url('admin/add') }}" class="btn btn-sm btn-success"> Generate</a>
                    </div>
                </div>
                <div class="panel-body">
                    <form method="post" class="form-horizontal">
                        <h3 class="headline generator-header">Model setting</h3>
                        <div class="form-group">
                            <label for="inputModelNamespace" class="col-lg-2 control-label">Namespace</label>
                            <div class="col-lg-3">
                                <input type="text" value="{{ formParam['modelNamespace'] }}" name="modelNamespace" class="form-control input-sm" id="inputModelNamespace" placeholder="Namespace">
                            </div><!-- /.col -->
                        </div><!-- /form-group -->
                        <div class="form-group">
                            <label for="inputModelClass" class="col-lg-2 control-label">Class</label>
                            <div class="col-lg-3">
                                <input type="text" value="{{ formParam['modelClass'] }}" name="modelClass" class="form-control input-sm" id="inputModelClass" placeholder="Class">
                            </div><!-- /.col -->
                            <label for="inputModelExtends" class="col-lg-1 control-label"><code>Extends</code></label>
                            <div class="col-lg-3">
                                <input type="text" value="{{ formParam['modelExtents'] }}" name="modelExtents" class="form-control input-sm" id="inputModelExtends" placeholder="Extends">
                            </div><!-- /.col -->
                        </div><!-- /form-group -->
                        <div class="form-group">
                            <label for="inputTableAlias" class="col-lg-2 control-label">Table alias</label>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-addon input-sm">{{table}}</span>
                                    <input type="text" value="{{ formParam['tableAlias'] }}" name="tableAlias" class="form-control input-sm" id="inputTableAlias" placeholder="Table alias">
                                </div>
                            </div><!-- /.col -->
                        </div><!-- /form-group -->

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Mapping</label>
                            <div class="col-lg-10">
                                <table class="table table-striped" id="responsiveTable">
                                    <thead>
                                    <tr>
                                        <th>Column name</th>
                                        <th>Class property</th>
                                        <th>Filter</th>
                                        <th>Sort</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {% for map in mapping %}
                                        <tr>
                                            <td>
                                                <b>{{ map['name'] }}</b>
                                                <span class="label label-primary">{{ map['typeName'] }}{% if map['size']> 0 %}({{ map['size'] }}){% endif %}</span>
                                                {% if map['isPrimary'] == true %}
                                                    <span class="label label-danger">Primary</span>
                                                {% endif %}
                                                {% if map['isIndex'] == true %}
                                                    <span class="label label-info">index</span>
                                                {% endif %}
                                            </td>
                                            <td><input type="text" value="{{ map['property'] }}" name="property[{{map['name']}}]" class="form-control input-sm"></td>
                                            <td>
                                                <label class="label-checkbox inline">
                                                <input type="checkbox" name="filterable[{{map['name']}}]" class="regular-checkbox" {% if map['isFilterable'] == true %}checked{% endif %}>
                                                <span class="custom-checkbox info bounceIn animation-delay4"></span>
                                                    Filterable
                                                </label>
                                            </td>
                                            <td>
                                                <label class="label-checkbox inline">
                                                    <input type="checkbox" name="sortable[{{map['name']}}]" class="regular-checkbox" {% if map['isSortable'] == true %}checked{% endif %}>
                                                    <span class="custom-checkbox info bounceIn animation-delay4"></span>
                                                    Sortable
                                                </label>
                                            </td>
                                            <td>
                                                {% if map['isPrimary'] == false %}
                                                    {% if map['isNumeric'] == true %}
                                                        <input type="text" value="{{ map['constant'] }}" name="constant[{{map['name']}}]" class="form-control input-sm"
                                                               data-toggle="tooltip" data-placement="top"
                                                               placeholder="Constant value" title="CONSTANT1 : value : label, CONSTANT2 : value : label, ...">
                                                    {% else %}
                                                        <label class="label-checkbox inline">
                                                            <input type="checkbox" name="searchable[{{map['name']}}]" class="regular-checkbox" {% if map['isSearchable'] == true %}checked{% endif %}>
                                                            <span class="custom-checkbox info bounceIn animation-delay4"></span>
                                                            Searchable Text
                                                        </label>
                                                    {% endif %}
                                                {% endif %}
                                            </td>
                                        </tr>
                                    {% endfor %}
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- /form-group -->
                        <div class="form-group">
                            <label for="inputCreateController" class="col-lg-2 control-label">Enable create controller</label>
                            <div class="col-lg-3 form-control-static">
                                <label class="label-checkbox inline">
                                    <input type="checkbox" name="isCreateController" id="inputCreateController" class="regular-checkbox" {% if map['isCreateController'] == true %}checked{% endif %}>
                                    <span class="custom-checkbox info bounceIn animation-delay4"></span>

                                </label>
                            </div><!-- /.col -->
                        </div><!-- /form-group -->
                        <h3 class="headline generator-header">Controller setting</h3>
                        <div class="form-group">
                            <label for="inputControllerIcon" class="col-lg-2 control-label">Font Awesome Icon</label>
                            <div class="col-lg-2">
                                <input type="text" value="{{ formParam['ctrIcon'] }}" name="ctrIcon" class="form-control input-sm" id="inputControllerIcon" placeholder="fa-user">
                            </div><!-- /.col -->
                        </div><!-- /form-group -->
                        <div class="form-group">
                            <label for="inputControllerNamespace" class="col-lg-2 control-label">Namespace</label>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <input type="text" value="{{ formParam['ctrNamespace'] }}" name="ctrNamespace" class="form-control input-sm" id="inputControllerNamespace" placeholder="Namespace">
                                    <span class="input-group-addon input-sm"><code>\Controller</code></span>
                                </div>
                            </div><!-- /.col -->
                        </div><!-- /form-group -->
                        <div class="form-group">
                            <label for="inputControllerClass" class="col-lg-2 control-label">Class name</label>
                            <div class="col-lg-3">
                                <input type="text" value="{{ formParam['ctrClass'] }}" name="ctrClass" class="form-control input-sm" id="inputControllerClass" placeholder="Class">
                            </div><!-- /.col -->
                            <label for="inputControllerExtends" class="col-lg-1 control-label"><code>Extends</code></label>
                            <div class="col-lg-3">
                                <input type="text" value="{{ formParam['ctrExtends'] }}" name="ctrExtends"  class="form-control input-sm" id="inputControllerExtends" placeholder="Extends">
                            </div><!-- /.col -->
                        </div><!-- /form-group -->
                        <div class="form-group">
                            <label for="inputRecordPerPage" class="col-lg-2 control-label">Record Per Page</label>
                            <div class="col-lg-3">
                                <input type="text" value="{{ formParam['recordPerPage'] }}" name="recordPerPage" class="form-control input-sm" id="inputRecordPerPage" placeholder="RecordPerPage">
                            </div><!-- /.col -->
                        </div><!-- /form-group -->

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Mapping</label>
                            <div class="col-lg-12">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Column name</th>
                                        <th>Label</th>
                                        <th></th>
                                        <th></th>
                                        <th>Validating in Add/Edit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {% for map in mapping %}
                                    <tr>
                                        <td>
                                            <b>{{ map['name'] }}</b>
                                            <span class="label label-primary">{{ map['typeName'] }}{% if map['size']> 0 %}({{ map['size'] }}){% endif %}</span>
                                            {% if map['isPrimary'] == true %}
                                                <span class="label label-danger">Primary</span>
                                            {% endif %}
                                            {% if map['isIndex'] == true %}
                                                <span class="label label-info">index</span>
                                            {% endif %}
                                        </td>
                                        <td><input type="text" value="{{ map['label'] }}" name="label[{{map['name']}}]" class="form-control input-sm"></td>
                                        <td>
                                            <label class="label-checkbox inline">
                                                <input type="checkbox" name="indexExclude[{{map['name']}}]" class="regular-checkbox" {% if map['isIndexExclude'] == true %}checked{% endif %}>
                                                <span class="custom-checkbox info bounceIn animation-delay4"></span>
                                                Index exclude
                                            </label>
                                        </td>
                                        <td>
                                            <label class="label-checkbox inline">
                                                <input type="checkbox" name="addEditExclude[{{map['name']}}]" class="regular-checkbox" {% if map['isAddEditExclude'] == true %}checked{% endif %}>
                                                <span class="custom-checkbox info bounceIn animation-delay4"></span>
                                                Add/Edit exclude
                                            </label>
                                        </td>
                                        <td>
                                            <select class="form-control input-sm" name="validatingAddEdit[{{map['name']}}]">
                                                <option value="notNeed" {% if map['validatingAddEdit'] == 'notNeed'%}selected{% endif %}>Not need</option>
                                                <option value="email" {% if map['validatingAddEdit'] == 'email'%}selected{% endif %}>Email Address</option>
                                                <option value="notEmpty" {% if map['validatingAddEdit'] == 'notEmpty'%}selected{% endif %}>Not Empty String</option>
                                                <option value="greaterThanZero" {% if map['validatingAddEdit'] == 'greaterThanZero'%}selected{% endif %}>Number greater than zero (0)</option>
                                            </select>
                                        </td>
                                    </tr>
                                    {% endfor %}
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- /form-group -->
                        <div class="panel-footer clearfix">
                            <div class="pull-right">
                                <button type="submit" class="btn btn-sm btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /panel -->
        </div>
        <!-- /.col -->
    </div>
</div>
