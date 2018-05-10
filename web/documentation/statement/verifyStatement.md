**Sign statement**
----
  Returns json data: boolean (true/false) - status of statement verification

* **URL**

  /v1/statements/verify
  
* **Method:**

  `POST`
  
*  **Request Headers**

    
*  **URL Params**
    

* **Data Params**
    ```
    {
   	"message" : "{
                     "id": "http://example.gov/credentials/3732",
                     "type": ["Credential", "ProofOfAgeCredential"],
                     "issuer": "https://dmv.example.gov",
                     "issued": "2010-01-01",
                     "claim": {
                       "id": "did:example:ebfeb1f712ebc6f1c276e12ec21",
                       "ageOver": 21
                     },
                     "revocation": {
                       "id": "http://example.gov/revocations/738",
                       "type": "SimpleRevocationList2017"
                     },
                     "proof": {
                       "type": "LinkedDataSignature2015",
                       "created": "2016-06-18T21:19:10Z",
                       "creator": "did:vb:jimboFry777",
                       "domain": "json-ld.org",
                       "nonce": "598c63d6",
                       "signatureValue": "BavEll0/I1zpYw8XNi1bgVg/sCneO4Jugez8RwDg/+
                       MCRVpjOboDoe4SxxKjkCOvKiCHGDvc4krqi6Z1n0UfqzxGfmatCuFibcC1wps
                       PRdW+gGsutPTLzvueMWmFhwYmfIFpbBu95t501+rSLHIEuujM/+PXr9Cky6Ed
                       +W3JT24="
                     }
                   }"
    }
    ```

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
     {
         "status": "success",
         "data": {
             true
         }
     }
    ```
 
* **Error Response:**

   * **Code:** 400 Bad Request <br />
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
      url: "/v1/statements/verify",
      dataType: "json",
      data: {
        message: "JSON"
    },
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```