# Mars Rover Mission
This practical test is based on a mission of a rover sent to Mars.

The project is divided into two main parts. The first part includes a backend developed using a **Laravel API**, while the second part consists of a frontend created with **Vue.js**. This project follows a clear structure and aims to show the main knowledge learned during the course.
## BACKEND
The backend includes several key technical features. First, a user authentication system has been implemented. When a user registers or logs in, the system generates an authentication token that is required to access the rover functionalities. Without this token, the user cannot interact with the system.

The backend database contains two important related tables. The first one is the rover table, which is linked to the users table. When a new user account is created, a rover is automatically assigned to that user. Each time the user logs in, the rover keeps the last saved position from the previous session.

In addition, there is another related table called obstacles. This table allows users to create and manage the obstacles used in their rover environment. Each user can only access the obstacles they have created, ensuring data separation between different users. In summary, every user can configure their own rover and environment according to their specific needs.

### Tech Stack
- PHP 8.2+
- Laravel 12
- Laravel Sanctum
- MySQL


## FRONTEND
The frontend is divided into three main pages:

### Login:
When users access the website for the first time, they are automatically redirected to the login page. This page has a visual design inspired by Mars rover missions. On the left side, some example or previous fake missions are displayed, while on the right side there is a form that allows users to log in to the system.

![Login screen](docs/login.png)

### Register:
This page is very similar to the login page, but it is used to create a new user account. When a user completes the registration process successfully, they are redirected to the dashboard page.

### Dashboard: 
This page can only be accessed by authenticated users. Access is controlled using the authentication token assigned to each user. If the token is valid, the user can enter the dashboard; otherwise, access is denied.

On this page, several important elements are displayed. On the left side, the current position of the rover can be seen. There is also a form to send movement commands to the rover, as well as another form used to create and manage obstacles. These tools allow the user to control and configure their own rover environment.

### Final Structure:
- `/login`
  - Login form (email + password)
  - On success → redirects to `/`
  - Link to `/register` if the user has no account

- `/register`
  - Register form (name, email, password, password confirmation)
  - On success → auto-login and redirects to `/`

- `/`
  - Dashboard (protected route)
  - Shows rover state, obstacle management and the Mars map

### Tech Stack
- Vue 3 (Composition API)
- Pinia (state management)
- Vue Router
- Vite
- HTML Canvas (map rendering)


## Project Structure

| Layer     | Path                | Description |
|----------|---------------------|-------------|
| Backend  | backend/app         | Laravel application logic |
| Backend  | backend/routes      | API routes |
| Backend  | backend/database    | Migrations and seeders |
| Frontend| frontend/src/api    | Axios client and API helpers |
| Frontend| frontend/src/views  | Login, Register, Dashboard |
| Frontend| frontend/src/stores | Pinia stores |
| Frontend| frontend/src/components | Reusable components |

## ROVER LOGIC
The rover logic works as follows. When a user account is created, a rover is automatically placed in the center of the map at position (X: 100, Y: 100). From that point, the user can send movement commands to control the rover.

The available commands are F (Forward), L (Left), and R (Right). These commands can be combined into a single sequence, allowing multiple movements at once. For example, a valid command sequence could be: FFFFFRRFLF.

Additionally, the user can create obstacles anywhere on the map. During the execution of a command sequence, if the rover detects that the next movement would place it on a position occupied by an obstacle, the rover changes its status to “aborted”, stops all remaining movements, and notifies the user that the mission has been aborted.

To continue the mission after this situation, the user must change the rover’s direction and send new movement commands to move in another direction.

## API Endpoints

### Auth
- POST /api/register
- POST /api/login
- GET /api/me
- POST /api/logout

### Rover
- GET /api/rover
- POST /api/rover/commands

### Obstacles
- GET /api/obstacles
- POST /api/obstacles
- DELETE /api/obstacles/{id}

## RUN PROJECTE STEP BY STEP
### CLONE REPOSITORI:

- git clone <REPOSITORY_URL>
- cd <PROJECT_FOLDER>

### BACKEND INSTALL AND RUN:

- Install **Laravel Herd** or open this one(required to serve '.test' domains).
- cd backend
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate
- Try this link, in theory it should just show you that Laravel is running. If not, you have some kind of error: http://mars-rover-mission.test
  
### FRONTEND INSTALL AND RUN:

- cd ..
- cd frontend
- npm install
- npm run dev

### ACCES APP
- http://localhost:5173

## Notes

- Code comments are written in Catalan, while this documentation is provided in English.


