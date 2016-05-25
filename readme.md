# App, templates, modules, migrations, models, business logic  GENERATOR

It is a web application for Laravel framework that helps to scaffold laravel project for faster development experience.
The main purpose is to popularize framework among the devs, communities and eventually the business owners or clients.

## Structure
The main folder is Core. The main business logic is situated there.
 such folder should follow such design realizations:
1. Components - the folder for partial view components or the components that have their own scaffolding.  
___
2. Contracts  - basically these are interfaces that are used for the business logic. They shouldn't be implemented by the concrete repositories. 
The main goal is to provide interfaces for classes that include some specific logic like drivers for example.
Also one more example is to use tightened interfaces and traits all up together in one class.
___
3. FacadeAccessors these the Laravel Facades for classes.
___
4. Facades these are the classes that scaffold different classes in one place and perform the logic with a help of them instead of injecting
concrete repository classes or their interfaces into controllers for example. 
Would be better just to inject specific facade into a controller.
___
5. Mail Composers just the folder for mail composers the classes that ease up a little bit sending mail.
___
6. Models Just The folder for Core Models
___
7. Presenters - classes that  implement different sort of transformers. Used to help output the data to view or client in general.
___
8. Transformers - preform models transformation while trying to the transformed model data.
___
9. Repositories - concrete repository class that maps data, adds some model data feature methods. Used basically for one model.
___
10. RepositoryInterfaces - additional interfaces for concrete repositories in our case prefixed with Eloquent word in the end to the name of repository interface.
___
11. ServiceIntegrations - the folder to integrate different sort of services as for example facebook, youtube etc.
__
12. Traits - code methods and blocks that can be reused but don't need to be concrete or abstracted.

## Contributing

If you like the idea of fast project scaffolding and like laravel  please contribute it would be really appreciated.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Nikolenko Ivan at nikoleivan@gmail.com.

## License

*MIT*

