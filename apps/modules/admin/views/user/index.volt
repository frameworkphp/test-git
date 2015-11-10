<div class="padding-md">
    {{ content() }}
    {{ flashSession.output() }}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default table-responsive">
                <div class="panel-heading clearfix">
                    <div class="input-group pull-left">
                        <h3 class="panel-title"><i class="fa fa-user"></i> Manage users</h3>
                    </div>
                    <div class="input-group pull-right">
                        <a href="{{url('admin/user/add')}}" class="btn btn-sm btn-success"> Add User</a>
                    </div>
                </div>
                <div class="panel-body clearfix">
                    <div class="col-md-2 form-filter">
                        <select name="role" class="input-sm form-control">
                            <option>Select a role</option>
                            {% for name, value in roles %}
                                {% if name == parameter['role'] %}
                                    <option selected value="{{name}}">{{value}}</option>
                                {% else %}
                                    <option value="{{name}}">{{value}}</option>
                                {% endif %}
                            {% endfor %}
                        </select>
                    </div>

                    <div class="col-md-2 form-filter">
                        <select name="status" class="input-sm form-control">
                            <option >Select a user state</option>
                            {% for id, value in status %}
                                {% if id == parameter['status'] %}
                                    <option selected value="{{id}}">{{value}}</option>
                                {% else %}
                                    <option value="{{id}}">{{value}}</option>
                                {% endif %}
                            {% endfor %}
                        </select>
                    </div>

                        <a class="btn btn-default btn-sm pull-left">Filter</a>



                    <form method="get">
                        <div class="col-md-4 input-group pull-right">
                            <input type="text" name="q" class="form-control input-sm" value="{{ parameter['keyword'] }}" placeholder="search user by name, email..." aria-controls="dataTable"><span
                                class="input-group-btn">
                            <button class="btn btn-default btn-sm" type="submit">
                                <i class="fa fa-search"></i>
                            </button></span>
                        </div>
                    </form>
                </div>
                {% if users.total_items > 0 %}
                <form method="post" name="appForm">
                    <table class="table table-striped" id="responsiveTable">
                        <thead>
                        <tr>
                            <th>
                                <label class="label-checkbox">
                                    <input type="checkbox" id="chk-all">
                                    <span class="custom-checkbox"></span>
                                </label>
                            </th>
                            <th class="{% if sortBy == 'id' %}sorted{% else %}sortable{% endif %}">
                                <a href="{{url(orderUrl)}}sortby=id&{% if sortBy == 'id' and sortType|lower == 'asc' %}sorttype=desc{% else %}sorttype=asc{% endif %}{% if pagination.current > 1 %}&page={{pagination.current}}{% endif %}">
                                    ID {% if sortBy == 'id' and sortType|lower == 'desc' %}<i class="fa fa-caret-down"></i>{% else %}<i class="fa fa-caret-up"></i>{% endif %}
                                </a>
                            </th>
                            <th class="{% if sortBy == 'name' %}sorted{% else %}sortable{% endif %}">
                                <a href="{{url(orderUrl)}}sortby=name&{% if sortBy == 'name' and sortType|lower == 'asc' %}sorttype=desc{% else %}sorttype=asc{% endif %}{% if pagination.current > 1 %}&page={{pagination.current}}{% endif %}">
                                    Name {% if sortBy == 'name' and sortType|lower == 'desc' %}<i class="fa fa-caret-down"></i>{% else %}<i class="fa fa-caret-up"></i>{% endif %}
                                </a>
                            </th>
                            <th class="{% if sortBy == 'email' %}sorted{% else %}sortable{% endif %}">
                                <a href="{{url(orderUrl)}}sortby=email&{% if sortBy == 'email' and sortType|lower == 'asc' %}sorttype=desc{% else %}sorttype=asc{% endif %}{% if pagination.current > 1 %}&page={{pagination.current}}{% endif %}">
                                    Email {% if sortBy == 'email' and sortType|lower == 'desc' %}<i class="fa fa-caret-down"></i>{% else %}<i class="fa fa-caret-up"></i>{% endif %}
                                </a>
                            </th>
                            <th>Role</th>
                            <th>User state</th>
                            <th>Date Register</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for user in users.items %}
                            <tr>
                                <td>
                                    <label class="label-checkbox">
                                        <input type="checkbox" class="chk-row" name="cid[]" value="{{ user.id }}">
                                        <span class="custom-checkbox"></span>
                                    </label>
                                </td>
                                <td>{{user.id}}</td>
                                <td><a href="{{url('admin/user/edit/' ~ user.id)}}" title="Edit this user">{{user.name}}</a></td>
                                <td>{{user.email}}</td>
                                <td>{{user.getRoleName()}}</td>
                                <td><span class="label {{user.getStatusLabel()}}">{{user.getStatusName()}}</span></td>
                                <td>{{ date('M d, Y', user.dateCreated) }}</td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>

                    <div class="panel-footer clearfix">
                        <div class="pull-left form-filter">
                                <select name="bulkAction" class="input-sm form-control">
                                    <option>Bulk action</option>
                                    <option value="deletes">Delete</option>
                                </select>
                        </div>
                        <a class="btn btn-default btn-sm pull-left">Apply</a>
                        <div class="pull-right">
                            <p class="pagination-showing">
                                Showing {{(users.current - 1) * users.limit + 1}} to 10 of {{users.total_items}} entries
                            </p>
                            {% if pagination.items is defined and pagination.total_pages > 1 %}
                                {% include "layouts/pagination.volt" %}
                            {% endif %}
                        </div>
                    </div>
                    <input type="hidden" name="boxChecked" value="0" />
                </form>
                {% else %}
                <div class="col-md-12">
                    <div class="table-no-record">
                        <p>No user found!</p>
                        <i class="fa fa-user"></i>
                    </div>
                </div>
                {% endif %}
            </div>
            <!-- /panel -->
        </div>
        <!-- /.col -->
    </div>
</div>
