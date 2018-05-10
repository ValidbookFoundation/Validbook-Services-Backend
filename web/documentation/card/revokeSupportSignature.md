**Revoke Support Human Claim Signature**
----
  Returns json data about a status of revoke of support human claim card.

* **URL**

  /v1/human-card/`public_address`/revoke-support-signature
  
* **Method:**

  `PATCH`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
   **Required:**
    
   `public_address=[string]` <br/>

* **Data Params**
    ```
    ```

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
        "status": "success",
        "data": {
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

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/human-card/0xe3954b59340b92a01a2258251c56098cc6c485cc/revoke-support-signature",
      dataType: "json",
      type : "PATCH",
      success : function(r) {
        console.log(r);
      }
    });
  ```