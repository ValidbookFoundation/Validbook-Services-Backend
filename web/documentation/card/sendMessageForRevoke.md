**Send Message For Revoke Human Card**
----
  Returns json data about a status of message for revoke human card.

* **URL**

  /v1/human-card/`public_address`/message-for-revoke
  
* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
     
*  **URL Params**
    
   **Required:**
    
   `public_address=[string]` <br/>

* **Data Params**


* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
       "status": "success",
       "data": "<?--- START REVOCATION MESSAGE ---?>\n\n\"payload\": \"Revoke Human Card\"\n\n\"address\": \"0x726eaedca081fa6ec0c3c99e4a0c0d83fdee49a3\"\n\n\"timestamp\": \"21-Sep-2017 14:09:06\"\n\n<?--- END REVOCATION MESSAGE ---?>"
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

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/draft-human-card/1/message-for-revoke?access_token=Fkc5AVudvdGj1dHUEy6w3tTwVqYjkues",
      dataType: "json",
      type : "PATCH",
      success : function(r) {
        console.log(r);
      }
    });
  ```