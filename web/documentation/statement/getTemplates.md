**Save signature document**
----
  Returns json data: list of statement templates

* **URL**

  /v1/statements/templates
  
* **Method:**

  `GET`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    

* **Data Params**


* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
     {
         "status": "success",
         "data": [
             {
                 "id": "1",
                 "title": "Signed Document",
                 "json": null,
                 "default": "1",
                 "will_be_json_changed": "0",
                 "templates": []
             },
             {
                 "id": "2",
                 "title": "Certificate (generic)",
                 "json": "{\"id\":\"\",\"type\":[\"Credential\",\"Certificate\"],\"issued\":\"\",\"presentationTemplate\":\"https://validbook.org/certificate-green-nice-template\",\"claim\":{\"name\":\"...Certificate...\",\"description\":\"For successful completion of the...\",\"recipient\":\"did:vb:recipient_did\"}}",
                 "default": "0",
                 "will_be_json_changed": "0",
                 "templates": [
                     {
                         "id": "1",
                         "title": "Certificate - green",
                         "link": "http://api-futurama1x.validbook.org/templates/1/tamplate.html"
                     }
                 ]
             },
             {
                 "id": "3",
                 "title": "Certificate (Open Badge Standard)",
                 "json": "{\"id\":\"\",\"type\":[\"Credential\",\"Certificate\"],\"issued\":\"\",\"presentationTemplate\":\"https://validbook.org/certificate-green-nice-template\",\"claim\":{\"name\":\"...Certificate...\",\"description\":\"For successful completion of the...\",\"recipient\":\"did:vb:recipient_did\"}}",
                 "default": "0",
                 "will_be_json_changed": "0",
                 "templates": [
                     {
                         "id": "1",
                         "title": "Certificate - green",
                         "link": "http://api-futurama1x.validbook.org/templates/1/tamplate.html"
                     }
                 ]
             },
             {
                 "id": "4",
                 "title": "Certificate of File Signing",
                 "json": "{\"id\":\"random UUID\",\"type\":[\"Statement\",\"FileSigning\"],\"issued\":\"2017-06-29T14:58:57.461422+00:00\",\"presentationTemplate\":\"https://validbook.org/file-signing-nice-template\",\"claim\":{\"fileHash\":\"ai235erowyw8rt4wq83ry4w37yr4\",\"hashType\":\"SHA3-256\",\"description\":\"I, Validbook identity – jibmroCry777, referred to as the \\\"Signer\\\", by signing this statement certify, that I have read, understand and agree to comply with all terms and conditions written in the digital file \\\"ContractName1.doc\\\", referred to as the \\\"Signed File\\\".The Signed File is uniquely identified by the hash value: sha3 - 256 = \\\"fa84yoq34hfqo8374rq87r4d3fg235dfgry6e54g34q8r7g\\\"\"}}",
                 "default": "0",
                 "will_be_json_changed": "1",
                 "templates": []
             }
         ]
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
      url: "/v1/statements/templates",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```