Webservice with Symfony Standard Edition 3.3
========================

REST Webservice to communicate with Mysql/SQLite Database to CRUD aparments in the apartment Database.
After creating a new record, the user receives an e-mail with a security key to edit and delete the record.

Dependencies
--------------

The Symfony Standard Edition is configured with the following defaults:

  * FOS\RestBundle

  * JMS\SerializerBundle

  * Nelmio\CorsBundle

Endpoints
--------------

The webservice provides the following endpoints

  * **/apartments** GET, Returns a list of all apartments
  
  * **/apartment/{id}** GET, Returns the apartment with the given id
  
  * **/apartment** POST, Creates new apartment in the database
  
  * **/apartment/{id}/{token}** PUT, Updates the apartment with the given id
  
  * **/apartment/{id}/{token}** DELETE, Deletes the apartment with the given id