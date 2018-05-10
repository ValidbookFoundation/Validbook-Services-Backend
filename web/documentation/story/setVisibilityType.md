**Set Visibility Type To Story**
----
    Returns json data about status of putting visibility type to story.
    Allowed visibility types: ['public', 'custom', 'private']
    If visibility_type = custom, `users_custom_visibility` must be set to post params. Else `users_ids` is an empty array

* **URL**

    /v1/stories/`story_id`/update-visibility

* **Method:**

    `POST`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**

    **Required**
    `story_id=[integer]`

* **Data Params**

    ```
    {
        "visibility": 'public',
        "users_custom_visibility": []
    }
    ```

* **Success Response:**

* **Code:** 200 <br />
**Content:**
    ```
    {
        "status": "success"
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
        url: "/v1/stories/10/update-visibility",
        dataType: "json",
        type : "POST",
    success : function(r) {
        console.log(r);
    }
    });
    ```