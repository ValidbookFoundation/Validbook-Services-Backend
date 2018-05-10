**Register user**
----
  Returns json data about status of user registration.

* **URL**

  /v1/registration

* **Method:**

  `POST`
  
*  **URL Params**

  None  

* **Data Params**

  ```
  {
	   "first_name" : "John",
	   "last_name" : "Daves",
	   "address": "0x6cf0cf5d690ce96492947a46c57d986853f99675",
	   "backup_address": "0x2342424634746756754675467"
  }
  ```
    
* **Success Response:**

  * **Code:** 201 Created <br />
    **Content:** 
  ```
   {
       "status": "success",
       "data": {
           "id": 9,
           "first_name": "John",
           "last_name": "Daves",
           "slug": "john.daves.5",
           "avatar230": "https://s3-us-west-2.amazonaws.com/dev.validbook/default-avatars/user-230.png",
           "avatar48": "https://s3-us-west-2.amazonaws.com/dev.validbook/default-avatars/user-48.png",
           "avatar32": "https://s3-us-west-2.amazonaws.com/dev.validbook/default-avatars/user-32.png",
           "is_follow": false,
           "cover": {
               "picture_original": null,
               "picture_small": null,
               "color": ""
           },
           "identity": {
               "name": "john.daves.5",
               "public_address": "0xD67108d529676Be614d056acBaA248c955f0559E"
           },
           "token": "61qy7KLATRAc31bAxVR_XWVNzig19aBA"
       }
   }
  ```
 
* **Error Response:**

  * **Code:** 404 <br />
    **Content:** 
  ```
    {
      "status": "error",
      "errors": [
        {
          "message": {
            "address": [
              "Address \"john@smith.com\" has already been taken."
            ]
          },
          "code": 404
        }
      ]
    }
  ```

* **Sample Call:**

  ```javascript
    $.ajax({
      url: "/v1/registration",
      dataType: "json",
      type : "POST",
      success : function(r) {
        console.log(r);
      }
    });
  ```