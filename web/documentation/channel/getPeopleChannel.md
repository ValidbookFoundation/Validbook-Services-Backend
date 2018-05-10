**Show Following People in Channel**
----
  Returns json data about a  channel following people.

* **URL**

  /v1/channels/`channel_id`/following-people

* **Method:**

  `GET`
  
*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
  
*  **URL Params**

   `page=[integer]` - page = 1 by default

   **Required:**
   
   `channel_id=[integer]`

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```
   
    ```
 
* **Error Response:**

  * **Code:** 404 <br />
    **Content:** 
    ```
    {
      "status": "error",
      "errors": [
        {
          "message": "Channel doesn't exist",
          "code": 404
        }
      ]
    }
    ```

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/channels/222/following-people",
      dataType: "json",
      type : "GET",
      data: {"page": 2},
      success : function(r) {
        console.log(r);
      }
    });
  ```