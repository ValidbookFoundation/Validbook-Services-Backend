**Message For Human Claim**
----
  Returns json data about a status of message for card human claim.

* **URL**

  /v1/card/`address`/human-claim-message
  
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
            "message": "{\"claimType\":\"I am human\",\"claimerCard\":\"0x7e2b048628ee1e00c8fc85e32caa323028b7c79f\",\"msgDescriptiveText\":\"This card 0x7e2b048628ee1e00c8fc85e32caa323028b7c79f, uniquely represents on Validbook platform a human individual, known to it's community by the name Reg Registrovich. I, Reg Registrovich do not have other Validbook account cards that represent me as a human individual. This Validbook card is controlled by me, only.\",\"msgCreateTimestamp\":\"21 Nov 2017 09:08:09\"}"
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
      url: "/v1/card/0x7e2b048628ee1e00c8fc85e32caa323028b7c79f/human-claim-message",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```