**Update channel**
----
  Returns json data about status of updating story.

* **URL**

  /v1/stories/`story_id`

* **Method:**

  `PATCH`
  
*  **URL Params**

   **Required:**
   
  `story_id=[integer]`

* **Data Params**

  ```
    {
        "name": "Edited Story",
        "description": "New description"
    }
  ```
    
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
  ```
    {
      "status": "ok",
      "data": {
        "id": 10,
        "name": "Edited Story"
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

  ```
    $.ajax({
      url: "/v1/stories/2",
      dataType: "json",
      data: {
          name: "Edited Story", 
          description: "New description"
      },
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```