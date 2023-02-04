#include <curl/curl.h>
#include <stdio.h>

#define URL "http://127.0.0.1:5432/"
#define DB_NAME "TeamEase"
#define USER_NAME "project"
#define PASSWORD "Respons11"

int main(void)
{
    CURL *curl;
    CURLcode res;

    curl = curl_easy_init();
    if (curl) {
        curl_easy_setopt(curl, CURLOPT_URL, URL ":" DB_NAME);
        curl_easy_setopt(curl, CURLOPT_USERPWD, USER_NAME ":" PASSWORD);
        res = curl_easy_perform(curl);

        if (res != CURLE_OK)
            fprintf(stderr, "curl_easy_perform() failed: %s\n", curl_easy_strerror(res));
        else
            printf("curl_easy_perform() success: %s\n", curl_easy_strerror(res));

        curl_easy_cleanup(curl);
    }

    return 0;
}
