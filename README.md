# CiGen
Code generates following files in CodeIgniter 4 : models, controllers, routes 

Created to be used with React DataTable CRUD : https://www.npmjs.com/package/react-datatable-crud

#Installing

Because the package it's still under development in order to install it you'd need first to:
```shell
composer config minimum-stability dev
```
This will allow you to install dev packages. 

```shell
composer require muravian/cigen
```

Once installed you need to make sure that you connected to db and created your tables. 

CiGen will generate models that will extend a base model in your app folder.

To run the generator you'll need to:
```shell
php spark cigen:publish
```

After this you can successfully install https://www.npmjs.com/package/react-datatable-crud and enjoy your REACT DATATABLE CRUD component with backend API in Codeigniter. 