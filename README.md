# docker-wkhtmltopdf-aas

wkhtmltopdf in a docker container as a web service.

## Running the service

```sh
cd partirpdf
docker-compose up -d --build
```

NGINX configuration

```nginx
        location /html2pdf {
                proxy_pass              http://192.168.1.xx:4920;
                proxy_set_header Host $host;
                proxy_set_header X-Real-IP $remote_addr;
                proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
                proxy_set_header X-Forwarded-Proto $scheme;
                proxy_read_timeout 300;
                proxy_connect_timeout 300;
                proxy_send_timeout 300;
        }
```
