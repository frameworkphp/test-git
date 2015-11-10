<ul class="pagination pagination-xs m-top-none">
    {% set mid_range = 7 %}

    {% if pagination.total_pages > 1 %}
        {% if pagination.current != 1%}
            {% set pageString = '<li>'~ linkTo(""~paginateUrl~"page="~pagination.before, "«") ~'</li>' %}
        {% else %}
            {% set pageString = '<li class="disabled"><a>«</a></li>' %}
        {% endif %}

        {% set start_range = pagination.current - (mid_range / 2) %}
        {% set end_range = pagination.current + (mid_range / 2) %}

        {% if start_range <= 0 %}
            {% set end_range = end_range + (start_range)|abs + 1 %}
            {% set start_range = 1 %}
        {% endif %}

        {% if end_range > pagination.total_pages %}
            {% set start_range = start_range - (end_range - pagination.total_pages) %}
            {% set end_range = pagination.total_pages %}
        {% endif %}

        {% set range = range(start_range, end_range) %}

        {% for i in 1..pagination.total_pages %}
            {% if i == 1 or i == pagination.total_pages or i in range %}
                {% if i == pagination.current %}
                    {% set pageString = pageString ~ '<li class="active"><a>'~ i ~'</a></li>' %}
                {% else %}
                    {% set pageString = pageString ~ '<li>'~ linkTo(""~paginateUrl~"page="~i, ""~i) ~'</li>' %}
                {% endif %}
            {% endif %}
        {% endfor %}

        {% if pagination.current != pagination.total_pages %}
            {% set pageString = pageString ~ '<li>'~ linkTo(""~paginateUrl~"page="~pagination.next, "»") ~'</li>' %}
        {% else %}
            {% set pageString = pageString ~ '<li class="disabled"><a>»</a></li>' %}
        {% endif %}
        {{ pageString }}
    {% endif %}
</ul>