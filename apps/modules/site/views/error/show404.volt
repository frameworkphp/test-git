<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Oops, This Page Could Not Be Found!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PHP Framework">
    <meta name="author" content="Phan Nguyen">

    {{ stylesheet_link('plugins/bootstrap/css/bootstrap.min.css') }}
    {{ stylesheet_link('plugins/font-awesome/css/font-awesome.css') }}
    {{ stylesheet_link('css/admin/main.css') }}
</head>

<body>
<div id="">
    <div class="padding-md" style="margin-top:50px;">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 text-center">
                <div class="h5">Oops, This Page Could Not Be Found!</div>
                <h1 class="m-top-none error-heading">404</h1>

                <h4>Search Our Website</h4>
                <div>Can't find what you need?</div>
                <div class="m-bottom-md">Try searching for the page here</div>
                <div class="input-group m-bottom-md">
                    <input type="text" class="form-control input-sm" placeholder="search here...">
						<span class="input-group-btn">
							<button class="btn btn-default btn-sm" type="button"><i class="fa fa-search"></i></button>
						</span>
                </div><!-- /input-group -->
                <a class="btn btn-success m-bottom-sm" href="index.html"><i class="fa fa-home"></i> Back to Dashboard</a>
                <a class="btn btn-success m-bottom-sm" href="contact.html"><i class="fa fa-envelope"></i> Contact Us</a>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.padding-md -->
</div><!-- /wrapper -->

</body>
</html>