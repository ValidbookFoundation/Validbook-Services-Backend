**Add Linked Digital Property of Card**
----
  Returns json data about a status of add linked digital properties of account card.

* **URL**

  /v1/card/`public_address`/add-digital-property
  
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
       "property": "facebook",
       "url_to_property": "htttps://facebook.com/jimbo.fry"
     }
    ```

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
        "status": "success",
        "data": {
            "id": 9,
            "property": "facebook",
            "url_property": "htttps://facebook.com/jimbo.fry",
            "url_proof": null,
            "random_number": 121212112121,
            "created": "24 Oct 2017",
            "is_valid": 0
        }
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