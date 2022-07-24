<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Setup Laravel Project 
```
laravel new laravel_survey
```
### connect to database 
Check the database name in .env file 
```
DB_DATABASE=laravel_survey
```
Migrate the initial tables
```
php artisan migrate
```
## config .editorconfig file
`.editorconfig` file is used to keep consistency in the coding environment when you work with multiple team members. It helps you to keep all the code in the same formating.

So as per my view, If you are working with multiple developers then you should not ignore it else you can ignore it.

In our Project we will change the indent_size so make the code more organized.
```
[*.{js,css,less,scss,vue}]
indent_size = 2
```
## Setup Vuejs Project
We will run this command then I will choose vue then it will ask me to choose vue type, I will choose vue only.
```
npm init vite vue
```
Then run vue in our Project
```
cd vue
npm install
npm run dev
```
## Setup Vuex
To install the latest version 
```
npm install -S vuex@next
```
### Creating data stores
A Vuex `store` is an object that wraps all of our application’s state and enables us access to features such as mutations, actions, and getters to use in our components to access or update the global state.

Create vuex store in vue/src/store/index.js by calling the `Vuex.Store` constructor with an object including the states and mutations that we want to add to create a basic store.
`States` are properties that store data in our Vuex store; they let us access the data from anywhere in our Vue 3 app.
Once we’ve created our store, we import it in vue/src/main.js and use store `createApp`. The `app.use(store);` method call lets us use the store in our Vue 3 app. 

### check if store is working correctly
add users with data in state of store
```
    state: {
        user: {
            data: {name: "Qabas"},
            token:null
        }
    },
```
Then open App.vue. First, import mapState, 

