<div class="padding-md">
    {{ content() }}
    {{ flashSession.output() }}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default table-responsive">
                <div class="panel-heading clearfix">
                    <div class="input-group pull-left">
                        <h3 class="panel-title"><i class="fa fa-list"></i> Select a table to generate</h3>
                    </div>
                </div>
                {% if count(listTable) > 0 %}
                <div class="panel-body clearfix">
                    <div class="row">
                        {% for table in listTable %}
                            <div class="col-md-3 col-sm-3">
                                <a class="generator-table" href="{{ url('admin/generator/table/' ~ table) }}" title="{{table}}">
                                    <i class="fa fa-table"></i> {{table}}
                                </a>
                            </div>
                        {% endfor %}
                    </div>
                </div>
                {% else %}
                <div class="col-md-12">
                    <div class="table-no-record">
                        <p>No table found.</p>
                        <i class="fa fa-list"></i>
                    </div>
                </div>
                {% endif %}
            </div>
            <!-- /panel -->
        </div>
        <!-- /.col -->
    </div>
</div>
