server {
    listen 80;
    server_name {{ site.domain }} www.{{ site.domain }};

    location / {
        root /var/www/{{ site.www_dir }}/current/web;
        index index.html;
        try_files $uri /index.html;
    }
}
