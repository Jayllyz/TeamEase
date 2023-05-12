all: build run clean

build:
	gcc -o EXEC src/api/main.c -lcurl

run:
	./EXEC

clean:
	rm EXEC
