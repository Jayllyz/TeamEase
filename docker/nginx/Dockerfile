FROM nginx:latest

RUN sudo snap install core; sudo snap refresh core

RUN sudo snap install --classic certbot

RUN sudo ln -s /snap/bin/certbot /usr/bin/certbot

RUN sudo certbot --nginx

EXPOSE 8000