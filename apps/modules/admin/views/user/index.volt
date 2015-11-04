<div class="padding-md">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default table-responsive">
                <div class="panel-heading">
                    <div class="input-group pull-left">
                        <h3 class="panel-title"><i class="fa fa-user"></i> Manage users</h3>
                    </div>
                    <div class="input-group pull-right">
                        <a href="{{url('admin/user/add')}}" class="btn btn-xs btn-success"> Add User</a>
                    </div>
                </div>
                <div class="panel-body">
                    <form method="get">
                        <div class="input-group pull-right">
                            <input type="text" name="keyword" class="form-control input-sm" value="{{keyword}}" placeholder="search user by name, email..." aria-controls="dataTable"><span
                                class="input-group-btn">
                            <button class="btn btn-default btn-sm" type="submit">
                                <i class="fa fa-search"></i>
                            </button></span>
                        </div>
                    </form>
                </div>
                    <table class="table table-striped" id="responsiveTable">
                        <thead>
                        <tr>
                            <th>
                                <label class="label-checkbox">
                                    <input type="checkbox" id="chk-all">
                                    <span class="custom-checkbox"></span>
                                </label>
                            </th>
                            <th>ID</th>
                            <th class="{% if sortBy == 'name' %}sorted{% else %}sortable{% endif %}">
                                <a href="{{url(orderUrl)}}sortby=name&{% if sortBy == 'name' and sortType|lower == 'asc' %}sorttype=desc{% else %}sorttype=asc{% endif %}{% if pagination.current > 1 %}&page={{pagination.current}}{% endif %}">
                                    Name {% if sortBy == 'name' and sortType|lower == 'asc' %}<i class="fa fa-caret-down"></i>{% else %}<i class="fa fa-caret-up"></i>{% endif %}
                                </a>
                            </th>
                            <th class="{% if sortBy == 'email' %}sorted{% else %}sortable{% endif %}">
                                <a href="{{url(orderUrl)}}sortby=email&{% if sortBy == 'email' and sortType|lower == 'asc' %}sorttype=desc{% else %}sorttype=asc{% endif %}{% if pagination.current > 1 %}&page={{pagination.current}}{% endif %}">
                                    Email {% if sortBy == 'email' and sortType|lower == 'asc' %}<i class="fa fa-caret-down"></i>{% else %}<i class="fa fa-caret-up"></i>{% endif %}
                                </a>
                            </th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Date Register</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for user in users.items %}
                            <tr>
                                <td>
                                    <label class="label-checkbox">
                                        <input type="checkbox" class="chk-row">
                                        <span class="custom-checkbox"></span>
                                    </label>
                                </td>
                                <td>{{user.id}}</td>
                                <td>{{user.name}}</td>
                                <td>{{user.email}}</td>
                                <td>{{user.getRoleName()}}</td>
                                <td><span class="label {{user.getStatusLabel()}}">{{user.getStatusName()}}</span></td>
                                <td>{{ date('M d, Y', user.dateCreated) }}</td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>

                <div class="panel-footer clearfix">
                    <div class="pull-left">Showing {{(users.current - 1) * users.limit + 1}} to 10 of {{users.total_items}} entries</div>
                    {% if pagination.items is defined and pagination.total_pages > 1 %}
                        {% include "layouts/pagination.volt" %}
                    {% endif %}
                </div>
            </div>
            <!-- /panel -->
        </div>
        <!-- /.col -->
    </div>
</div>
