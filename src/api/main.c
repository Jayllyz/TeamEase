#include "cJSON.c"
#include <curl/curl.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

struct MemoryStruct {
    char *memory;
    size_t size;
};

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

            printf("Receive => %s\n", chunk.memory);

            cJSON *json;
            json = cJSON_Parse(chunk.memory);
            if (!json) {
                fprintf(stderr, "Error Parsing JSON\n");
            }
            else {
                // cJSON *success = cJSON_GetObjectItem(json,"success");
                // if(cJSON_IsFalse(success)) {
                //     printf("Je suis désolé, je n'ai pas compris votre question. Veuillez réessayer.\n");
                // }
                // cJSON *message = cJSON_GetObjectItem(json,"message");
                // cJSON *data = cJSON_GetObjectItem(json,"data");
                // int numResults = cJSON_GetArraySize(data);

                // printf("\n %s \n", message->valuestring);

                // cJSON *result;
                // cJSON *name;
                // for (int i = 0; i < numResults; i++) {
                //     result = cJSON_GetArrayItem(data, i);

                //      name = cJSON_GetObjectItem(result,"name");
                //     printf(" - activité : => %s" , name->valuestring);

                // }
            }
            cJSON_Delete(json);
        }
    }

    curl_easy_cleanup(curl);
}

int main(int argc, char *argv[])
{
    printf("Bienvenue sur l'interface de notre BOT FAQ !\n");

    char *input = malloc(200);
    printf("Votre question ? : \n");
    scanf("%s", input);

    curlApi("http://localhost:80/api/api.php/activities", input);

    printf("Exiting...\n");

    return 0;
}