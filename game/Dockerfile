FROM node:14-alpine

WORKDIR /home/app/cardgame
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build
EXPOSE 3000
ENV NUXT_HOST=0.0.0.0
ENV NUXT_PORT=3000
CMD [ "npm", "start" ]

# ENV NUXT_VERSION=2.15.7
# WORKDIR /app/cardgame
# COPY ./game/ .

# RUN : \
#   # && yarn install \
#   && yarn build \
#   # && rm -rf node_modules \
# #   && rm package.json \
#   && yarn add "nuxt-start@${NUXT_VERSION}" \
#   && yarn cache clean \
#   && :







# ENV NUXT_VERSION=2.15.7

# WORKDIR /app/cardgame

# COPY . .
# RUN : \
#   && yarn install \
#   && yarn build \
#   && rm -rf node_modules \
# #   && rm package.json \
#   && yarn add "nuxt-start@${NUXT_VERSION}" \
#   && yarn cache clean \
#   && :

# ENTRYPOINT ["yarn", "start"]
# EXPOSE 3000


# COPY ./package.json ./
# RUN yarn install
# COPY . .
# RUN yarn build

# FROM node:lts-alpine
# RUN yarn add "nuxt-start@${NUXT_VERSION}"

# COPY --from=frontend-build /.nuxt /app/cardgame/.nuxt
# COPY --from=frontend-build /nuxt.config.ts /app/cardgame/
# # COPY --from=frontend-build /static /app/cardgame/

# ENTRYPOINT ["npx", "nuxt-start"]
# EXPOSE 4567
