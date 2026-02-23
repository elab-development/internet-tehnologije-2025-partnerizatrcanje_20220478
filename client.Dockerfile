FROM node:20-alpine

WORKDIR /usr/src/app

# Copy only package files first for better caching
COPY client/package*.json ./

RUN npm install

# Copy the rest (in dev we also mount volumes, but this helps build)
COPY client/ ./

EXPOSE 3000

CMD ["npm", "start"]