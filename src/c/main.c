#include <stdio.h>
#include <curl/curl.h>

void curlApi(char *url){
    CURL *curl;
    CURLcode res;

    curl = curl_easy_init();
    if(curl) {
        curl_easy_setopt(curl, CURLOPT_URL, url);
        res = curl_easy_perform(curl);
        if(res != CURLE_OK)
            fprintf(stderr, "curl_easy_perform() failed: %s", curl_easy_strerror(res));

        curl_easy_cleanup(curl);
    }
}


int main(void)
{
    int choice;
    do{
    printf("\n1. Affiche les tables de la base de données\n2. Affiche le contenu de la table activité\n0. Quitter\n");

    printf("Votre choix : ");
    scanf("%d", &choice);

    switch(choice)
    {
        case 1:
            curlApi("http://localhost:8000/tables");
            break;
        case 2:
            curlApi("http://localhost:8000/activite");
            break;
    }}while(choice != 0);

  return 0;
}