<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

## About

Restful API using for Members management.

1. Add new Admin
2. Register Member
3. Login OAuth2
4. Update user profile : name, birthday, avatar
5. Admin can see the Users list and all permissions
6. Admin can receive email notification from User updated and registered.

## Tech

- Framework : Laravel 6.x (php7.2)
- Database : MySQL 5.7
- Email provider : mailtrap.io
- Deployment: Heroku
- API Document: Postman

## Requirements

- Github Account
- Heroku Account
- Postman

## Deploy in Heroku

1. Login Github and fork [LINK Github](https://github.com/tkgg002/examwb.git) to Github account.

2. Deploy code in Heroku
	* Create new app
	* Deploy (Tab Deploy) :
		* Deployment method : connect to Github
		* App connected to GitHub : choice "examwb"
		* Manual deploy : Click Deploy Branch
	* Add database (Tab Resources) :
		* Add add-on Heroku Postgres
	* Config (More-> Run Console) :
		* Generate the new key : `php artisan key:generate`
		* Roll back all the migrations in your application : `php artisan migrate:reset`
		* Run all the migrations in your application : `php artisan migrate --seed`
		* Run create `php artisan passport:install`

## API Document : [LINK DOCUMENT](https://documenter.getpostman.com/view/8975014/SVtN3B8z?version=latest)

1. Add new Admin `/api/user/admin-signup`

2. Register Member `/api/user/signup`

3. Login user (OAuth2) `/api/user/login`

4. Update user profile `/api/user/update`

5. List Users `/api/user/users`



## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
