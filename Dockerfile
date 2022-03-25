FROM ubuntu:20.04
MAINTAINER Partir.com <partir.com>

# Install dependencies for running web service
RUN apt-get update
RUN apt-get install -y python2
RUN apt-get install -y curl
RUN curl https://bootstrap.pypa.io/pip/2.7/get-pip.py --output get-pip.py
RUN python2 get-pip.py
RUN pip install --index-url=https://pypi.org/simple/ werkzeug executor gunicorn
# wkhtmltopdf 0.12.5
ARG DEBIAN_FRONTEND=noninteractive
ENV TZ=Europe/France
#RUN apt-get -y install wkhtmltopdf
# wkhtmltopdf 0.12.6
RUN apt-get -y install wget
RUN wget https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6-1/wkhtmltox_0.12.6-1.focal_amd64.deb
RUN apt-get -y install fontconfig libfreetype6 libjpeg-turbo8 libpng16-16 libx11-6 libxcb1 libxext6 libxrender1 xfonts-75dpi xfonts-base
RUN dpkg -i ./wkhtmltox_0.12.6-1.focal_amd64.deb

ADD app.py /app.py
EXPOSE 4920

ENTRYPOINT ["usr/local/bin/gunicorn"]

# Show the extended help
CMD ["-b", "0.0.0.0:80", "--timeout", "180", "--log-file", "-", "app:application"]