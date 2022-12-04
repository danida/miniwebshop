FROM maven:3.8.6-jdk-11
WORKDIR /app
COPY . .
RUN mvn clean install
CMD sleep 10 && mvn spring-boot:run