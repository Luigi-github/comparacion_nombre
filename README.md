## Algoritmo Jaro-Winkler

El algoritmo Jaro-Winkler es un método para calcular la similitud entre dos cadenas de texto. Este algoritmo se utiliza comúnmente en aplicaciones de emparejamiento de registros y deduplicación de datos.

El algoritmo Jaro-Winkler calcula la similitud entre dos cadenas de texto basándose en la cantidad de caracteres coincidentes y la cantidad de transposiciones necesarias para convertir una cadena en la otra. La similitud se expresa como una puntuación entre 0 y 1, donde 0 indica que las cadenas son completamente diferentes y 1 indica que las cadenas son idénticas.

Referencia:

Winkler, W. E. (1990). String Comparator Metrics and Enhanced Decision Rules in the Fellegi-Sunter Model of Record Linkage. Journal of the American Statistical Association, 85(411), 194-204.

## Pre-requisitos

- MySQL 5.7 o superior
- PHP 8.0 o superior
- Composer
- Nodejs v18 o superior

## Instalación

- Instalación de dependencias Servidor
```
composer install
```  

- Instalación de dependencias Cliente (Opcional)

```
npm install && npm run dev
```  

- Ejecución  

```
php artisan serve
```  

### Configuración de autenticación con Laravel Passport

- Generar clientes y claves privadas  

```
# php artisan passport:install
```  

- Configurar claves de acceso mediante clientes en .env (Se realiza por facilidad para esta prueba)  

```
PERSONAL_CLIENT_ID=1
PERSONAL_CLIENT_SECRET=mM52YwvoOlvv2mATFb5FzLo1MfCuHudw1IuWMIFp
PASSWORD_CLIENT_ID=2
PASSWORD_CLIENT_SECRET=VXVixg2yt8fa05Yezj3kHhEPBv6T2AfcM5NePfNG
```

### Metodos disponibles

1. Obtener token de autenticación  

**Metodo:** POST 
**Endpoint:** /api/v1/users/token  
**Headers:**  

| Key | Value |
| --- | --- |
| Content-Type | application/json |  

**Request Body:**  

```
{
    "email": "luigi@test.com",
    "password": "test01"
}
```  

**Response Body:**  

```
{
    "token_type": "Bearer",
    "expires_in": 1296000,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiNTU2ZjFkM2Q4YmNhM2U5NTVmMTdkNDhkNWMwN2VkMTc0ZDU0YjkzNWJiZTgwNjViYzJlZjZlODA4YTRkODZjZjE3OTliZGY1OGZlYTEzMDUiLCJpYXQiOjE2Nzg2Njg0MDIuNTcxNTk0LCJuYmYiOjE2Nzg2Njg0MDIuNTcxNjAxLCJleHAiOjE2Nzk5NjQ0MDIuNTU3MDE5LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.cqXpu3kL9X_2VQtrgTQ8jdqFZ3I-IHZJ8CLhUuVEOYr_7-_KpbO0MmrIs-mZIzwMTIvu7fo5T97XNV4w0OHKyuccjc3EMaE5zQ5RHsLAMfuLCwFrdOxl5iFnFRRsnlkFFmfiOeSZZCGgDg1-Ujy7tlH86vsDZgmTjL6IjX58kSkhrKMgN3knLO24V0Li8edmZM6CFU3OZunehp3AdeElYNgOHmLcjm1nhf7MLOXXT6v_nbEaPenTYRMe4Psz2814090tMKYfethwC1S7mCzLiP49tuj-fat1iPi9CtgrW-jmUUW-LYjAZGsnmxP4vzef93tsqyMteX2naSYdSpC6Xjmf7ey5JPf4SkkX7j53oHi9qyhvgavrsxz_LLGh7i71uaGn05FnM-9RQYsIgQIomhBzPurWQEJh0UFP5RkDDrzPIk2-a-Uhu5W9oP4IIflLRu3Did4qe7c-N-kmF136Uo2jZshzl2eLP2BGSNXKIw7qQE6xODqz8HF76cj3S7p4fXIRzwFaLLXJ3oPBqRsaCXQ8GhHFSuP8wPOoOXgbw11hZ47_FXXknx_25W_OGyWAQzLYyEGgXHvc87IIYr2aiiLcaDbvxBNk4KmP1qazq-V9yXA4hjkd1WWuOKtWEJkWnfju-Nf198cvp8thtLJ7JXRaxSOZaBiZ8bKGjTLP5vE",
    "refresh_token": "def5020012eac85e6d6ca4f710691604555ba9508dc77f965b64422b8ed5990d0b4b41e4fe9322f1fc192f3e613597437a9842fee86d6ef09dd66c97e227f05913da628776201a32adcef50564b7fc189a0b519e7673c2b1cd08954660d0db4d79028d8580cd2985099a8bf454c344c5ad9bfdf5c18d6202fc0d65f02e61a5ae5419e5dcf4aabde890acab42bd6e14aa8ea41f2000fbed1269b2c5998a6c275c186e432821951c99cd8421bb5fc515b04d2ae43ad3b4d30623d3ac4c531f60e190a1529bb1f7544b609715533dd592ba7f9cce2374350996e4f6f15003c3453308d4cc8fc2d55232653eb440b116f64ecd45e3472e24f13fda004c2787e6960c85d67da485cc0ed5cd6687feb4d018055f953f2639c22da93d5f6a27f6dcd8089a8a542db250de5373f6fa5a0754fb4787e70e4b76d9b02014d4cf3146e140c469b2b225e09e2ce962ef78eeb08e744dd2de63286540f57703e028004a6ffb6a9b"
}
```  

2. Obtener coincidencias para un nombre  

**Metodo:** GET 
**Endpoint:** /api/v1/match-names  
**Headers:**  

| Key | Value |
| --- | --- |
| Content-Type | application/json |
| Authorization | Bearer {access_token} |  

**Request Body:**  

```
{
    "name": "Oscar Noriega Redondo",
    "match_percentage": 90
}
```  

**Response Body:**  

```
{
    "id": 1, // UUID
    "name": "Oscar Noriega Redondo",
    "match_percentage": 90,
    "execution_state": "Registros encontrados",
    "coincidences": [
        {
            "name": "Oscar Noriega Redondo",
            "match_percentage": 100,
            "department": "SANTANDER",
            "location": "NO APLICA",
            "town": "RIONEGRO",
            "active_years": 6,
            "person_type": "PREFERENTE",
            "position_type": "POLITICO"
        }
    ]
}
```  

3. Obtener resultados de busqueda anteriores por identificador  

**Metodo:** GET 
**Endpoint:** /api/v1/{uuid}/match-names  
**Headers:**  

| Key | Value |
| --- | --- |
| Content-Type | application/json |
| Authorization | Bearer {access_token} |  

**Response Body:**  

```
{
    "id": 1, // UUID
    "name": "Oscar Noriega Redondo",
    "match_percentage": 90,
    "execution_state": "Registros encontrados",
    "coincidences": [
        {
            "name": "Oscar Noriega Redondo",
            "match_percentage": 100,
            "department": "SANTANDER",
            "location": "NO APLICA",
            "town": "RIONEGRO",
            "active_years": 6,
            "person_type": "PREFERENTE",
            "position_type": "POLITICO"
        }
    ]
}
```  

### Ejecución de pruebas unitarias

```
php artisan test --filter JaroWinklerTest
```
