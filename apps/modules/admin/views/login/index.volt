<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Login Administrator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PHP Framework">
    <meta name="author" content="Phan Nguyen">

    {{ stylesheet_link('plugins/bootstrap/css/bootstrap.min.css') }}
    {{ stylesheet_link('plugins/font-awesome/css/font-awesome.css') }}
    {{ stylesheet_link('css/admin/endless.min.css') }}
</head>

<body>
<div class="login-wrapper">
    <div class="text-center">
        <h2 class="fadeInUp animation-delay8" style="font-weight:bold">
            <span class="text-success">PHP Framework</span> <span
                style="color:#ccc; text-shadow:0 1px #fff">Admin</span>
        </h2>
    </div>
    <div class="login-widget animation-delay1">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <div class="pull-left">
                    <i class="fa fa-lock fa-lg"></i> Login
                </div>
            </div>
            <div class="panel-body">
                {{ content() }}
                <form id="formValidate1" action="" method="post" class="form-login" data-parsley-validate>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" placeholder="Email" name="femail" value="{{formData['femail']}}"
                               class="form-control input-sm bounceIn animation-delay2" data-required="true" data-type="email">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" placeholder="Password" name="fpassword"
                               class="form-control input-sm bounceIn animation-delay4" data-required="true" data-minlength="6">
                    </div>
                    <div class="form-group">
                        <label class="label-checkbox inline">
                            <input type="checkbox" name="fremember" class="regular-checkbox chk-delete"/>
                            <span class="custom-checkbox info bounceIn animation-delay4"></span>

                        </label>
                        Remember me
                    </div>

                    <div class="seperator"></div>
                    <div class="form-group">
                        Forgot your password?
                    </div>

                    <hr/>

                    <button type="submit" class="btn btn-success btn-sm bounceIn animation-delay5 login-link pull-right"><i
                            class="fa fa-sign-in"></i> Sign in</button>

                    <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">
                </form>
            </div>
        </div>
        <!-- /panel -->
    </div>
    <!-- /login-widget -->
</div>
<!-- /login-wrapper -->

<!-- Jquery -->
{{javascript_include('public/plugins/jquery/jquery-1.10.2.min.js')}}
<!-- Parsley -->
{{javascript_include('public/js/admin/parsley.min.js')}}

<script>
    $( document ).ready(function() {
        $('#formValidate1').parsley( { listeners: {
            onFormSubmit: function ( isFormValid, event ) {
                if(isFormValid)	{
                }
            }
        }});
    });
</script>

</body>
</html>