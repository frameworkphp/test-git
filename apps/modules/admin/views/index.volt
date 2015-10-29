<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        {{ get_title() }}
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="PHP Framework">
        <meta name="author" content="Phan Nguyen">

        <!-- Bootstrap core CSS -->
        {{ stylesheet_link('plugins/bootstrap/css/bootstrap.min.css') }}

        <!-- Font Awesome -->
        {{ stylesheet_link('plugins/font-awesome/css/font-awesome.min.css') }}

        <!-- Chosen -->
        {{ stylesheet_link('css/admin/chosen.min.css') }}

        <!-- Pace -->
        {{ stylesheet_link('css/admin/pace.css') }}

        <!-- Color box -->
        {{ stylesheet_link('css/admin/colorbox.css') }}

        <!-- Morris -->
        {{ stylesheet_link('css/admin/morris.css') }}

        <!-- Endless -->
        {{ stylesheet_link('css/admin/main.css') }}
        {{ stylesheet_link('css/admin/endless-skin.css') }}
    </head>
    <body class="overflow-hidden">
        <!-- Overlay Div -->
        <div id="overlay" class="transparent"></div>
        <div id="wrapper" class="preload">
            {% include "layouts/header.volt" %}
            {% include "layouts/sidebar.volt" %}
            <div id="main-container">
                <div id="breadcrumb">
                    <ul class="breadcrumb">
                        {% for bc in breadcrumbs %}
                        <li>
                            {% if (bc['text'] == 'Home') %}
                                <i class="fa fa-home"></i>
                            {% endif %}
                            {% if (bc['active']) %}
                                {{ bc['text'] }}
                            {% else %}
                                <a href="{{ bc['link'] }}"> {{ bc['text'] }}</a>
                            {% endif %}
                        </li>
                        {% endfor %}
                    </ul>
                </div><!-- /breadcrumb-->

                {{ content() }}
            </div><!-- /main-container -->
            {% include "layouts/footer.volt" %}


        </div><!-- /wrapper -->

        <a href="" id="scroll-to-top" class="hidden-print"><i class="fa fa-chevron-up"></i></a>

        <!-- Placed at the end of the document so the pages load faster -->

        <!-- Jquery -->
        {{javascript_include('public/plugins/jquery/jquery-1.10.2.min.js')}}

        <!-- Bootstrap -->
        {{javascript_include('public/plugins/bootstrap/js/bootstrap.js')}}

        <!-- Flot -->
        <!--{{javascript_include('public/js/admin/jquery.flot.min.js')}}-->

        <!-- Chosen -->
        {{javascript_include('public/js/admin/chosen.jquery.min.js')}}

        <!-- Morris -->
        {{javascript_include('public/js/admin/rapheal.min.js')}}
        {{javascript_include('public/js/admin/morris.min.js')}}

        <!-- Colorbox -->
        {{javascript_include('public/js/admin/jquery.colorbox.min.js')}}

        <!-- Sparkline -->
        {{javascript_include('public/js/admin/jquery.sparkline.min.js')}}

        <!-- Pace -->
        {{javascript_include('public/js/admin/pace.js')}}

        <!-- Popup Overlay -->
        {{javascript_include('public/js/admin/jquery.popupoverlay.min.js')}}

        <!-- Slimscroll -->
        {{javascript_include('public/js/admin/jquery.slimscroll.min.js')}}

        <!-- Modernizr -->
        {{javascript_include('public/js/admin/modernizr.min.js')}}

        <!-- Cookie -->
        {{javascript_include('public/js/admin/jquery.cookie.min.js')}}

        <!-- Endless -->
        <!--{{javascript_include('public/js/admin/endless_dashboard.js')}}-->
        {{javascript_include('public/js/admin/main.js')}}
        {{javascript_include('public/js/admin/parsley.min.js')}}
    </body>
</html>
