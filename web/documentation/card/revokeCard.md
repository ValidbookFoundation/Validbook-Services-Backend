**Revoke Human Card**
----
  Returns json data about a status of revoke of human card.

* **URL**

  /v1/human-card/`public_address`/revoke
  
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
      	"public_address": "0x726eaedca081fa6ec0c3c99e4a0c0d83fdee49a3",
      	"signature": "0xaa273193c513d16da20251255cce636ab37b812bb093dc5504bfc8f2bf818bd067e49f4c45ebe83262f3cf9c8f3e32b5f1599cdb1d72e8197776fd2a08a516bb1c"
      }
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
      url: "/v1/human-card/0xe3954b59340b92a01a2258251c56098cc6c485cc/revoke",
      dataType: "json",
      type : "PATCH",
      success : function(r) {
        console.log(r);
      }
    });
  ```