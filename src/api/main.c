#include "cJSON.c"
#include <curl/curl.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

struct MemoryStruct {
    char *memory;
    size_t size;
};

void clean_stdin(void)
{
    int c;
    do {
        c = getchar();
    } while (c != '\n' && c != EOF);
}

static size_t WriteMemoryCallback(void *ptr, size_t size, size_t nmemb, void *data)
{
    size_t realsize = size * nmemb;
    struct MemoryStruct *mem = (struct MemoryStruct *)data;

    mem->memory = realloc(mem->memory, mem->size + realsize + 1);
    if (mem->memory == NULL) {
        fprintf(stderr, "not enough memory (realloc returned NULL)\n");
        exit(EXIT_FAILURE);
    }

    memcpy(&(mem->memory[mem->size]), ptr, realsize);
    mem->size += realsize;
    mem->memory[mem->size] = 0;

    return realsize;
}

void curlApi(char *url, char *input)
{
    struct MemoryStruct chunk;
    chunk.memory = malloc(1);
    chunk.size = 0;

    curl_global_init(CURL_GLOBAL_ALL);

    char *json_input;
    json_input = (char *)malloc(strlen(input) + 40);
    sprintf(json_input, "{\"question\":\"%s\"}", input);

    CURL *curl;
    CURLcode res;

    curl = curl_easy_init();
    if (curl) {
        curl_easy_setopt(curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_easy_setopt(curl, CURLOPT_URL, url);

        struct curl_slist *headers = NULL;
        headers = curl_slist_append(headers, "Accept: application/json");
        headers = curl_slist_append(headers, "Content-Type: application/json");
        headers = curl_slist_append(headers, "charsets: utf-8");
        curl_easy_setopt(curl, CURLOPT_HTTPHEADER, headers);

        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, json_input);

        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, WriteMemoryCallback);

        curl_easy_setopt(curl, CURLOPT_WRITEDATA, (void *)&chunk);

        res = curl_easy_perform(curl);
        if (res != CURLE_OK)
            fprintf(stderr, "curl_easy_perform() failed: %s", curl_easy_strerror(res));
        else {

            cJSON *json;
            json = cJSON_Parse(chunk.memory);
            if (!json) {
                fprintf(stderr, "Je suis desole une erreur est survenue\n");
            }
            else {
                cJSON *success = cJSON_GetObjectItem(json, "success");
                cJSON *error = NULL;
                if (cJSON_IsFalse(success)) {
                    error = cJSON_GetObjectItem(json, "error");
                    if (error != NULL) {
                        printf("\n %s \n", error->valuestring);
                        return;
                    }
                    printf("Je suis désolé, je n'ai pas compris votre question. Veuillez réessayer.\n");
                }
                cJSON *message = cJSON_GetObjectItem(json, "message");

                printf("\n %s \n", message->valuestring);
                cJSON_Delete(json);
            }
        }
    }

    free(json_input);
    curl_easy_cleanup(curl);
}

int main(int argc, char *argv[])
{
    printf("Bienvenue sur l'interface de notre BOT FAQ !\n\n");

    char *input = malloc(200);
    do {
        printf("Votre question ? : \n");
        fgets(input, 200, stdin);
        input[strcspn(input, "\n")] = '\0';
        clean_stdin();

        if (input != NULL) {
            curlApi("http://localhost:80/api/api.php/activities", input);
        }

        free(input);
        input = malloc(200);
    } while (strcmp(input, "quitter") != 0);

    printf("\nAu revoir...\n");

    return 0;
}