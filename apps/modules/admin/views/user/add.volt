<div class="padding-md">
    {{ content() }}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <form method="post" class="form-horizontal form-border no-margin" id="basic-constraint" data-validate="parsley"
                      novalidate>
                    <div class="panel-heading">
                        Add User
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-md-2">Name</label>

                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control input-sm" data-required="true" placeholder="Name"
                                       value="{{formData['name']}}">
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /form-group -->
                        <div class="form-group">
                            <label class="control-label col-md-2">Email</label>

                            <div class="col-md-8">
                                <input type="text" name="email" class="form-control input-sm" data-required="true" data-type="email" placeholder="Email"
                                       value="{{formData['email']}}">
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /form-group -->
                        <div class="form-group">
                            <label class="control-label col-md-2">Password</label>

                            <div class="col-md-8">
                                <input type="password" name="password" class="form-control input-sm"
                                       placeholder="Password" data-required="true" value="">
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /form-group -->
                        <div class="form-group">
                            <label class="control-label col-md-2">Gender</label>

                            <div class="col-md-8">
                                <label class="label-radio inline">
                                    <input type="radio" name="gender" value="male" checked>
                                    <span class="custom-radio"></span>
                                    Male
                                </label>
                                <label class="label-radio inline">
                                    <input type="radio" name="gender" value="female">
                                    <span class="custom-radio"></span>
                                    Female
                                </label>
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">User Group</label>
                            <div class="col-lg-8">
                                <select class="form-control chzn-select" name="role">
                                    {% for name, value in roles %}
                                        <option value="{{name}}">{{value}}</option>
                                    {% endfor %}
                                </select>
                            </div><!-- /.col -->
                        </div><!-- /form-group -->

                    </div>
                    <div class="panel-footer clearfix">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                    <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">
                </form>
            </div>
            <!-- /panel -->
        </div>
    </div>
</div>
