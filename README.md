## About Concert Manager

Conert Manager is a multi-tenant SaaS platform for managing events. It was built by a team at the 2024 [Code Network](https://www.codenetwork.co/) [Winter Hackathon](https://www.codenetwork.co/hackathon).

This is not production-ready software and may introduce breaking changes at any time.
USE AT YOUR OWN RISK.

It handles*:
- Artist/Performer Management
- Venue Management
- Sponsor Management
- Volunteer Management
- Performance scheduling across multiple stages/rooms/event spaces and even multiple venues!

* Functionality may not currently be present

### Installation

Once the code is deployed in your chosen location you can use the following to generate a working development environment:
- composer install
- npm install
- npm run build
- cp .env.example .env
- php artisan storage:link
- php artisan migrate
- php artisan db:seed
- php artisan db:seed --class=DevelopmentSeeder
- php artisan key:generate
- php artisan serve
In a separate terminal:
- npm run dev

For email functionality you will require a Google API Project with Gmail API permissions

### Contributors

- Aimi Hobson - Architect & Fullstack Development
- Farzad Hayat - Front End Development
- Phat Tran - Front End Development
- Desmond Lo - Graphic Design & prototyping
- Helen Nguyen - Graphic Design

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Aimi Hobson via [minervavine@gmail.com](mailto:minervavine@gmail.com). All security vulnerabilities will be promptly triaged.

## License

Concert Manager is open-sourced software licensed under the [GPL license](https://www.gnu.org/licenses/gpl-3.0.en.html).
