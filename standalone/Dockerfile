# SPDX-License-Identifier: GPL-2.0-or-later
FROM docker.io/library/node:22-bookworm-slim
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
EXPOSE 5173 3001
CMD ["npm", "run", "server"]
