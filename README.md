<p align="center">Phase 1</p>


## About Phase1

Experimenting with a multilevel game concept

## Instalation

1. Install Docker Desktop
2. Install Git client and add the cmd line tools
3. Install composer globally
4. Create a new folder (maybe stlc)
5. Create sub-folders src, php, nginx
6. In the main folder run: git clone https://github.com/mrdatawolf/Phase1.git src
7. Cd to src and run composer install
8. Laravel needs a .env file in its main directory (src) so make that.
9. Make directory db in src/database
10. Touch core.sqlite in the new folder
11. Go into src/config and edit database.php change default connection from mysql to sqlite. Also change env('DB_DATABASE', database_path('database.sqlite')) to database_path(env('DB_DATABASE')
12. In src run composer require laravel/sanctum
13. Then add "maatwebsite/excel": "^3.1" to your composer.json in the require section.
13. Now setup sail following https://laravel.com/docs/8.x/sail
14. Still in src run php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
15. Still in src run composer require laravel/jetstream
16. Still in src run php artisan jetstream:install livewire --teams
17. In an admin console run Set-ExecutionPolicy Unrestricted
18. Still in src run npm install then npm run dev
19. In the admin console run Set-ExecutionPolicy Restricted to return it to default.
24. Fix any errors shown.
25. Goto 127.0.0.1 and you should see a laravel welcome screen.
    note: There is no .env in this repo.  You take the example env and copy it to .env .  Also you will need to create an app key.

## License

MIT
