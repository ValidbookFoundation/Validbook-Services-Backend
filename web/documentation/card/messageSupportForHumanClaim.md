**Message For Support Human Claim**
----
  Returns json data about a status of message for support card human claim.

* **URL**

  /v1/card/`address`/support-human-claim-message
  
* **Method:**

  `GET`
  
*  **URL Params**
    
    `address=[string]`
    
* **Data Params**


* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
        "status": "success",
        "data": {
            "message": "{\"supportType\":\"You are human\",\"givenToClaimHash\":\"0xe7208ce236397defd3aedb5e31cb3fb39a040c17232b123090ab772542a09681\",\"supportsCard\":\"0x7a182db6b0205e144c84dc409463eddcaa57c565\",\"supportFromCard\":\"0x7e2b048628ee1e00c8fc85e32caa323028b7c79f\",\"msgDescriptiveText\":\"I support claim of 0x7a182db6b0205e144c84dc409463eddcaa57c565 card, that it uniquely represents a human individual known to me as Test Testovich on Validbook.\",\"msgCreateTimestamp\":\"21 Nov 2017 13:23:16\"}"
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
      url: "/v1/card/0x7e2b048628ee1e00c8fc85e32caa323028b7c79f/support-human-claim-message",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```