**Get List of Conversation For User**
----
  Returns json data all conversations by authorized user

* **URL**

  /v1/conversations
  
* **Method:**

  `GET`

*  **Request Headers**

    `Authorization: Bearer QWxhZGRpbjpvcGVuIHNlc2FtZQ==`
    
*  **URL Params**
    
     `page=[integer]` </br>

* **Data Params**

      None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    
    ```
    {
       "status": "success",
       "data": [
           {
               "is_seen": "0",
               "messages": [
                   {
                       "type": "message",
                       "id": 56,
                       "text": "kSYYHSBE",
                       "date": "23 Jul 2017 16:19",
                       "user": {
                           "id": 1,
                           "first_name": "Jimbo",
                           "last_name": "Jamb",
                           "slug": "jimbo.jambo",
                           "status" : 1,
                           "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/12/1/45x45.jpg"
                       },
                       "conversation_id": 7
                   }
               ],
               "receivers": [
                   {
                       "id": 3,
                       "first_name": "Denis",
                       "last_name": "Dragomirik",
                       "slug": "denis.dragomirik",
                       "status" : 1,
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/3/45x45.jpg"
                   }
               ],
               "delete_hours": 24
           },
           {
               "is_seen": "0",
               "messages": [
                   {
                       "type": "message",
                       "id": 53,
                       "text": "2AA8rb5G",
                       "date": "23 Jul 2017 16:14",
                       "user": {
                           "id": 1,
                           "first_name": "Jimbo",
                           "last_name": "Jamb",
                           "slug": "jimbo.jambo",
                           "status" : 1,
                           "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/12/1/45x45.jpg"
                       },
                       "conversation_id": 2
                   }
               ],
               "receivers": [],
                "delete_hours": 24
           },
           {
               "is_seen": "0",
               "messages": [
                   {
                       "type": "message",
                       "id": 51,
                       "text": "hi",
                       "date": "21 Jul 2017 12:24",
                       "user": {
                           "id": 1,
                           "first_name": "Jimbo",
                           "last_name": "Jamb",
                           "slug": "jimbo.jambo",
                           "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/12/1/45x45.jpg"
                       },
                       "conversation_id": 22
                   }
               ],
               "receivers": [
                   {
                       "id": 22,
                       "first_name": "Vladislav",
                       "last_name": "Borbich",
                       "slug": "vladislav.borbich",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/200x200.png"
                   }
               ],
                "delete_hours": 24
           },
           {
               "is_seen": "0",
               "messages": [
                   {
                       "type": "message",
                       "id": 50,
                       "text": "21",
                       "date": "20 Jul 2017 12:14",
                       "user": {
                           "id": 53,
                           "first_name": "Darth",
                           "last_name": "Vader",
                           "slug": "darth.vader",
                           "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/20/53/45x45.jpg"
                       },
                       "conversation_id": 21
                   }
               ],
               "receivers": [
                   {
                       "id": 53,
                       "first_name": "Darth",
                       "last_name": "Vader",
                       "slug": "darth.vader",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/20/53/45x45.jpg"
                   }
               ]
           },
           {
               "is_seen": "0",
               "messages": [
                   {
                       "type": "message",
                       "id": 27,
                       "text": "qwerty",
                       "date": "20 Jul 2017 09:56",
                       "user": {
                           "id": 1,
                           "first_name": "Jimbo",
                           "last_name": "Jamb",
                           "slug": "jimbo.jambo",
                           "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/12/1/45x45.jpg"
                       },
                       "conversation_id": 20
                   }
               ],
               "receivers": [
                   {
                       "id": 18,
                       "first_name": "Stefano",
                       "last_name": "Paluello",
                       "slug": "stefano.paluello",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/18/45x45.jpg"
                   }
               ]
           },
           {
               "is_seen": "0",
               "messages": [
                   {
                       "type": "message",
                       "id": 26,
                       "text": "qwerty",
                       "date": "20 Jul 2017 09:55",
                       "user": {
                           "id": 1,
                           "first_name": "Jimbo",
                           "last_name": "Jamb",
                           "slug": "jimbo.jambo",
                           "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/12/1/45x45.jpg"
                       },
                       "conversation_id": 19
                   }
               ],
               "receivers": [
                   {
                       "id": 12,
                       "first_name": "Deepthi",
                       "last_name": "Prasad",
                       "slug": "deepthi.prasad",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/12/45x45.jpg"
                   }
               ]
           },
           {
               "is_seen": "0",
               "messages": [
                   {
                       "type": "message",
                       "id": 25,
                       "text": "Привіт",
                       "date": "20 Jul 2017 09:52",
                       "user": {
                           "id": 1,
                           "first_name": "Jimbo",
                           "last_name": "Jamb",
                           "slug": "jimbo.jambo",
                           "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/12/1/45x45.jpg"
                       },
                       "conversation_id": 18
                   }
               ],
               "receivers": [
                   {
                       "id": 4,
                       "first_name": "Alex",
                       "last_name": "Tykhonchuk",
                       "slug": "alex.tykhonchuk",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/4/45x45.jpg"
                   }
               ]
           },
           {
               "is_seen": "0",
               "messages": [
                   {
                       "type": "message",
                       "id": 24,
                       "text": "45",
                       "date": "20 Jul 2017 08:53",
                       "user": {
                           "id": 1,
                           "first_name": "Jimbo",
                           "last_name": "Jamb",
                           "slug": "jimbo.jambo",
                           "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/12/1/45x45.jpg"
                       },
                       "conversation_id": 17
                   }
               ],
               "receivers": [
                   {
                       "id": 8,
                       "first_name": "Eugene",
                       "last_name": "Sosnovsky",
                       "slug": "eugene.sosnovsky",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/8/45x45.jpg"
                   }
               ]
           },
           {
               "is_seen": "0",
               "messages": [
                   {
                       "type": "message",
                       "id": 23,
                       "text": "67",
                       "date": "20 Jul 2017 08:48",
                       "user": {
                           "id": 1,
                           "first_name": "Jimbo",
                           "last_name": "Jamb",
                           "slug": "jimbo.jambo",
                           "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/12/1/45x45.jpg"
                       },
                       "conversation_id": 16
                   }
               ],
               "receivers": [
                   {
                       "id": 19,
                       "first_name": "Александр",
                       "last_name": "Пономарчук",
                       "slug": "aleksandr.ponomarchuk",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/19/45x45.jpg"
                   }
               ]
           },
           {
               "is_seen": "0",
               "messages": [
                   {
                       "type": "message",
                       "id": 22,
                       "text": "0",
                       "date": "20 Jul 2017 08:47",
                       "user": {
                           "id": 1,
                           "first_name": "Jimbo",
                           "last_name": "Jamb",
                           "slug": "jimbo.jambo",
                           "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/07/12/1/45x45.jpg"
                       },
                       "conversation_id": 15
                   }
               ],
               "receivers": [
                   {
                       "id": 10,
                       "first_name": "Ruslan",
                       "last_name": "Nikiforov",
                       "slug": "ruslan.nikiforov",
                       "avatar": "https://s3-us-west-2.amazonaws.com/dev.validbook/avatars/2017/06/20/10/45x45.jpg"
                   }
               ]
           }
       ]
     }
    ```
 
* **Error Response:**

    * **Code:** 401 Unauthorized <br />
    * **Code:** 404 NOT FOUND<br />
    * **Code:** 422 Unprocessable Entity <br />
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
      url: "v1/conversations?page=2",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```