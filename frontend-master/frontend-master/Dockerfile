FROM node:lts-alpine3.10 as build-stage
WORKDIR /opt/app
ARG registry=''
ENV NODE_ENV=production
ENV NODE_OPTIONS=--max-old-space-size=2048
COPY yarn.lock package.json .browserslistrc ./
RUN yarn config set registry $registry
RUN yarn install --frozen-install
COPY src src
COPY types types
COPY .version babel.config.js giszp.iml tsconfig.json vue.config.js ./
COPY public/index.html public/
RUN yarn build
COPY public/files dist/files
COPY public/configs dist/configs
COPY public/icons dist/icons
COPY public/images dist/images
COPY public/logo dist/logo
COPY public/favicon.ico dist/

FROM nginx:stable-alpine as production-stage
COPY gzip.conf /etc/nginx/conf.d/gzip.conf
COPY --from=build-stage /opt/app/dist /usr/share/nginx/html
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
