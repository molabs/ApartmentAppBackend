Webservice with Symfony Standard Edition 3.3
========================

REST Webservice to communicate with Mysql/SQLite Database to CRUD aparments in the apartment Database.
After creating a new record, the user receives an e-mail with a security key to edit and delete the record.

Dependencies
--------------

In addition to the Symfony Standard Edition, the following dependencies were used:

  * FOS\RestBundle

  * JMS\SerializerBundle

  * Nelmio\CorsBundle
  
  
Installing
--------------

  * **git clone https://github.com/molabs/ApartmentAppBackend** Clone Repository
  
  * **cd ApartmentAppBackend/** Change directory
  
  * **composer install** Install application with composer. At the end of the installation enter MySQL and SMPT informations
  
  * **php bin/console server:run** Run the application
  
  * Open your Browser, enter 127.0.0.1/apartments

Endpoints
--------------

The webservice provides the following endpoints

  * **/apartments** GET, Returns a list of all apartments
  
  * **/apartment/{id}** GET, Returns the apartment with the given id
  
  * **/apartment** POST, Creates new apartment in the database
  
  * **/apartment/{id}/{token}** PUT, Updates the apartment with the given id
  
  * **/apartment/{id}/{token}** DELETE, Deletes the apartment with the given id
  
Building the application, the most important steps
--------------
  
  * composer create-project symfony/framework-standard-edition animus_project "3.3.*"
  
  * php bin/console generate:bundle --namespace=Moci/ApartemensBundle
  
  * composer dump-autoload
  
  * php bin/console doctrine:database:create
  
  * php bin/console doctrine:generate:entity
  
  * composer require friendsofsymfony/rest-bundle
  
  * composer require jms/serializer-bundle
  
  * composer require nelmio/cors-bundle
  
  * php bin/console generate:controller --controller=MociApartemensBundle:Apartments
  
  * composer require doctrine/doctrine-migrations-bundle
  
  * php bin/console doctrine:database:drop --force
  
  * php bin/console doctrine:database:create
  
  * php bin/console doctrine:migrations:diff
  
  * php bin/console doctrine:migrations:migrate