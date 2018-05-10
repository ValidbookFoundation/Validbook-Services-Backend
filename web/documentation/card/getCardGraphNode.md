**Get Card Ego Graph Node**
----
  Returns json data about card ego graph data.

* **URL**

  /v1/card/`public_address`/graph-node:node_id
  
* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
   `public_address=[string]` <br/>
   `node_id=[integer]` <br/>

* **Data Params**

  None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
        "status": "success",
        "data": {
            "node_id": 2,
            "level_1": ["12", "22"],
            "level_2": ["23", "24"],
            "level_3": [
                {
                    "node_id": 4,
                    "first_name": "Professor",
                    "last_name": "Hubert Farnsworth",
                    "created_at": "1510728933",
                    "slug": "professor.hubert.farnsworth",
                    "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/09/21/4/hWDt9hZkJm39VY9QJjaf38C3ZAeAw3Ru.jpg",
                    "public_address": "0xe85725c536494605C800A4bCF37cd97E36a0b9f9"
                }
            ]
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
      url: "/v1/card/0xe3954b59340b92a01a2258251c56098cc6c485cc/graph-node?node_id=2",
      dataType: "json",
      data: {},
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```