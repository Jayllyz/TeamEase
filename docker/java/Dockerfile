FROM openjdk:11
RUN apt-get update && apt-get install -y \
    openjdk-11-jdk \
    wget \
    unzip

RUN wget https://sourceforge.net/projects/jfreechart/files/latest/download -O jfreechart.zip \
    && unzip jfreechart.zip -d /opt \
    && rm jfreechart.zip

ENV CLASSPATH="/libs/jfreechart-1.5.4.jar:${CLASSPATH}"

CMD [ "java", "-cp", "/home/java/libs/jfreechart-1.5.4.jar:/home/java/src", "dashboard.Dashboard" ]