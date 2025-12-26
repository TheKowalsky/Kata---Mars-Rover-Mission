# Mars Rover Mission
This practical test is based on a mission of a rover sent to Mars.

The project is divided into two main parts. The first part includes a backend developed using a **Laravel API**, while the second part consists of a frontend created with **Vue.js**. This project follows a clear structure and aims to show the main knowledge learned during the course.
## BACKEND
The backend includes several key technical features. First, a user authentication system has been implemented. When a user registers or logs in, the system generates an authentication token that is required to access the rover functionalities. Without this token, the user cannot interact with the system.

The backend database contains two important related tables. The first one is the rover table, which is linked to the users table. When a new user account is created, a rover is automatically assigned to that user. Each time the user logs in, the rover keeps the last saved position from the previous session.

In addition, there is another related table called obstacles. This table allows users to create and manage the obstacles used in their rover environment. Each user can only access the obstacles they have created, ensuring data separation between different users. In summary, every user can configure their own rover and environment according to their specific needs.

## FRONTEND
The frontend is divided into three main pages:

### Login:
When users access the website for the first time, they are automatically redirected to the login page. This page has a visual design inspired by Mars rover missions. On the left side, some example or previous fake missions are displayed, while on the right side there is a form that allows users to log in to the system.

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
