FROM postgres:17-alpine

# Copy init sql files to container
COPY ./conf/* /docker-entrypoint-initdb.d
