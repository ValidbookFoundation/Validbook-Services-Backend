**Revoke Linked Digital Property of Card**
----
  Returns json data about a status of revoke linked digital property of card.

* **URL**

  /v1/card/`public_address`/revoke-digital-property
  
* **Method:**

  `PATCH`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
   **Required:**
    
   `public_address=[string]` <br/>

* **Data Params**
    ```
     {
       "property_id": 6,
     }
    ```

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
        "status": "success",
        "data": true
    }
    ```
 
* **Error Response:**

   * **Code:** 400 Bad Request <br />
   * **Code:** 401 Unauthorized <br />
   * **Code:** 404 NOT FOUND<br />
   * **Code:** 422 Unprocessable Entity <br />
   * **Code:** 500 Internal Server Error<br />
     **Content:** 
     ```
        {
          "status": "error",
          "errors": [
                  {
                      "code": Code,
                      "message": string or []
                  }
              ]
        }
     ```