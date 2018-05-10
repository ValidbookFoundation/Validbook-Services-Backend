**Sign statement**
----
  Returns json data: link to signed statement (JSON file)

* **URL**

  /v1/statements/sign
  
* **Method:**

  `POST`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    

* **Data Params**
    ```
    {
   	"signature": "0xe3954b59340b92a01a2258251c56098cc6c485cc",
   	"template_id": 1,
   	"statement" : "{"@context": "https://w3id.org/credentials/v1",
                    "id": "http://example.gov/credentials/3732",
                    "type": ["Credential", "ProofOfAgeCredential"],
                    "issuer": "https://dmv.example.gov",
                    "issued": "2010-01-01",
                    "claim": {
                      "id": "did:vb:ebfeb1f712ebc6f1c276e12ec21",
                      "text": "test"
                    }}"
    }
    ```

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
     {
         "status": "success",
         "data": {
             "nonce": "d85fcaa6-0693-11e8-889e-74c63b466c69",
             "timestamp": "2018-34-31 14:01:29"
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
      url: "/v1/statements/sign",
      dataType: "json",
      data: {
        signature : "0xe3954b59340b92a01a2258251c56098cc6c485cc",
        template_id: 1,
        statement: "JSON"
    },
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```